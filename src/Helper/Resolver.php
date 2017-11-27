<?php

namespace Blueprint\Helper;

use Blueprint\Exception\HelperNotFoundException;
use Blueprint\TemplateInterface;

class Resolver implements ResolverInterface
{
    protected $map = [];
    protected $ns = [];

    protected $resolver;

    public function __construct(callable $resolver)
    {
        $this->resolver = $resolver;
    }

    public function setNs(array $ns)
    {
        $this->ns = $ns;
    }

    public function addNs($ns)
    {
        $this->ns[] = $ns;
    }

    public function addClass(HelperInterface $class)
    {
        $this->map[$class->getName()] = $class;
    }

    public function addFunction($key, callable $func)
    {
        $this->map[$key] = new ClosureHelper($func);
    }

    public function resolve(string $method, TemplateInterface $template): HelperInterface
    {
        if (!isset($this->map[$method])) {
            $helper = null;
            $resolver = $this->resolver;

            foreach (array_reverse($this->ns) as $ns) {
                $helper = $resolver($ns.'\\'.ucfirst($method));
                if ($helper instanceof HelperInterface) {
                    $helper->setTemplate($template);
                    break;
                }
            }
            $this->map[$method] = $helper;
        }

        if (!$this->map[$method]) {
            throw new HelperNotFoundException("Could't find helper: ".$method);
        }
        return $this->map[$method];
    }
}
