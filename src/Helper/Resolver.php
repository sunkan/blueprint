<?php

namespace Blueprint\Helper;

use Aura\Di\Container as Di;
use Blueprint\ITemplate;

class Resolver implements IResolver
{
    protected $map = [];
    protected $ns = [];

    public function __construct(Di $di)
    {
        $this->di = $di;
    }

    public function setNs(array $ns)
    {
        $this->ns = $ns;
    }

    public function addNs($ns)
    {
        $this->ns[] = $ns;
    }

    public function addClass(IHelper $class)
    {
        $this->map[$class->getName()] = $class;
    }

    public function addFunction($key, $func)
    {
        $this->map[$key] = $func;
    }

    public function resolve($method, ITemplate $template)
    {
        if (isset($this->map[$method])) {
            return $this->map[$method];
        }
        $found = false;
        foreach (array_reverse($this->ns) as $ns) {
            try {
                $className = $ns.'\\'.ucfirst($method);
                if (class_exists($className)) {
                    $helper = $this->di->newInstance($className, [], ['setTemplate'=>$template]);
                    $found = true;
                    break;
                }
            } catch (\Aura\Autoload\Exception\NotFound $e) {
                $found = false;
            }
        }
        if (!$found) {
            $this->map[$method] = false;
            return false;
        }
        return $this->map[$method] = $helper;
    }
}
