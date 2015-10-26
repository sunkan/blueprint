<?php

namespace Blueprint;

class Simple implements ITemplate, ICallable
{
    protected $_helperResolver = array();
    protected $_payload = [];

    public function addResolver(Helper\IResolver $resolver)
    {
        $this->_helperResolver[] = $resolver;
    }

    public function setResolvers(array $resolvers)
    {
        $this->_helperResolver = [];
        foreach ($resolvers as $resolver) {
            if ($resolver instanceof Helper\IResolver) {
                $this->addResolver($resolver);
            }
        }
    }

    public function callHelper($method, $args)
    {
        $helper = false;
        foreach ($this->_helperResolver as $resolver) {
            $helper = $resolver->resolve($method, $this);
            if ($helper) {
                break;
            }
        }
        if (!$helper) {
            return false;
        }
        if ($helper instanceof Helper\IHelper) {
            return $helper->run($args);
        }
        return call_user_func_array(
            $helper,
            $args
        );
    }

    public function escape($value)
    {
        return htmlspecialchars(
            $value,
            ENT_QUOTES,
            'UTF-8'
        );
    }
    public function __call($method, $args)
    {
        return $this->callHelper($method, $args);
    }

    public function __get($key)
    {
        return isset($this->_payload[$key])?$this->_payload[$key]:null;
    }

    public function __set($var, $value)
    {
        $this->_payload[$var] = $value;
    }

    public function assign($spec, $value = null)
    {
        if (is_string($spec)) {
            if ($spec[0] != "_") {
                $this->_payload[$spec] = $value;
                return true;
            }
        }
        if (is_array($spec)) {
            foreach ($spec as $key => $val) {
                if ($key[0] != "_") {
                    $this->_payload[$key] = $val;
                }
            }
            return true;
        }
        if (is_object($spec)) {
            foreach (get_object_vars($spec) as $key => $val) {
                if ($key[0] != "_") {
                    $this->_payload[$key] = $val;
                }
            }
            return true;
        }
        return false;
    }

    public function render($file = null)
    {
        try {
            ob_start();
            $view = $this;
            extract($this->_payload);
            require $file;

            $data = ob_get_clean();
            return $data;
        } catch (\Exception $e) {
        }
    }
}
