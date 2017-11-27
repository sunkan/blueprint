<?php

namespace Blueprint;

use Blueprint\Exception\HelperNotFoundException;
use Blueprint\Helper\ResolverList;

class Simple implements TemplateInterface, CallableInterface
{
    protected $payload = [];
    protected $resolverList;

    public function __construct(ResolverList $resolverList)
    {
        $this->resolverList = $resolverList;
    }

    public function addResolver(Helper\ResolverInterface $resolver)
    {
        $this->resolverList[] = $resolver;
    }

    public function setResolvers(array $resolvers)
    {
        $this->resolverList = [];
        foreach ($resolvers as $resolver) {
            $this->addResolver($resolver);
        }
    }

    public function callHelper(string $method, array $args)
    {
        try {
            $helper = $this->resolverList->resolve($method, $this);
            return $helper->run($args);
        } catch (HelperNotFoundException $hnfe) {
            return false;
        }
    }

    public function __call($method, $args)
    {
        return $this->callHelper($method, $args);
    }

    public function __get($key)
    {
        return isset($this->payload[$key]) ? $this->payload[$key] : null;
    }

    public function __set($var, $value)
    {
        $this->payload[$var] = $value;
    }

    public function assign($spec, $value = null): bool
    {
        if (is_string($spec)) {
            if ($spec[0] != "_") {
                $this->payload[$spec] = $value;
                return true;
            }
        }
        if (is_array($spec)) {
            foreach ($spec as $key => $val) {
                if ($key[0] != "_") {
                    $this->payload[$key] = $val;
                }
            }
            return true;
        }
        if (is_object($spec)) {
            foreach (get_object_vars($spec) as $key => $val) {
                if ($key[0] != "_") {
                    $this->payload[$key] = $val;
                }
            }
            return true;
        }
        return false;
    }

    public function render($file = null): string
    {
        try {
            ob_start();
            $view = $this;
            extract($this->payload);
            require $file;

            $data = ob_get_clean();
            return $data;
        } catch (\Exception $e) {
            return '';
        }
    }
}
