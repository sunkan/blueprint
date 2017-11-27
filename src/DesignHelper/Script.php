<?php
namespace Blueprint\DesignHelper;

use Blueprint\Helper\AbstractHelper;

class Script extends AbstractHelper
{
    const PRODUCTION = 'prod';
    const DEVELOPMENT = 'dev';

    private $mode = self::DEVELOPMENT;

    protected $scripts = [];

    protected $on = [
        'load' => '',
        'config' => ''
    ];

    public function getName(): string
    {
        return 'script';
    }

    /*
     * $argv[0] = src
     * $argv[1] = type
     * $argv[2] = pos
     */
    public function run(array $argv)
    {
        $argc = count($argv);
        if ($argc == 0) {
            return $this;
        }
        $src = $argv[0];
        $type = isset($argv[1]) ? $argv[1] : 'text/javascript';
        $pos = isset($argv[2]) ? $argv[2] : 'bottom';
        return $this->add($src, $type, $pos);
    }

    public function add($src, $type = 'text/javascript', $pos = 'bottom')
    {
        $mode = false;
        if ($type === self::DEVELOPMENT) {
            $mode = self::DEVELOPMENT;
            $type = 'text/javascript';
        } elseif ($type === self::PRODUCTION) {
            $mode = self::PRODUCTION;
            $type = 'text/javascript';
        }
        $obj = new \stdClass;
        $obj->src = $src;
        $obj->type = $type;
        $obj->position = $pos;
        $obj->mode = $mode;
        $this->scripts[md5($src)] = $obj;

        return $this;
    }

    public function start()
    {
        ob_start();
        echo "\n(function(){\n";
    }

    public function end($var = 'load')
    {
        echo "\n})();\n";
        $this->on[$var] .= ob_get_clean();
    }

    public function has($pos)
    {
        $scripts = $this->scripts;
        $scripts = array_filter($scripts, function ($obj) use ($pos) {
            return ($obj->position == $pos);
        });
        return count($scripts) > 0;
    }

    public function get($pos)
    {
        $return = array();
        foreach ($this->scripts as $obj) {
            if ($obj->position == $pos) {
                $return[] = $obj->src;
            }
        }
        return $return;
    }

    public function setMode($mode)
    {
        if (!in_array($mode, [self::DEVELOPMENT, self::PRODUCTION])) {
            throw new \InvalidArgumentException('Invalid mode');
        }
        $this->mode = $mode;
    }

    public function render($func = null, $pos = 'all')
    {
        if (is_string($func) && !is_callable($func)) {
            $pos = strtolower($func);
        }
        if (isset($this->on[substr($pos, 2)])) {
            return $this->on[substr($pos, 2)];
        }
        $scripts = $this->scripts;
        if ($pos != 'all') {
            $scripts = array_filter($scripts, function ($obj) use ($pos) {
                return ($obj->position == $pos);
            });
        }
        if (!is_callable($func)) {
            $func = function ($obj) {
                if ($obj->mode) {
                    if ($this->hasTemplate() && $this->getTemplate()->site_mode != $obj->mode) {
                        return '';
                    } elseif ($this->mode != $obj->mode) {
                        return '';
                    }
                }
                $tpl = '<script type="%s" src="%s"></script>'."\n";
                return sprintf($tpl, $obj->type, $obj->src);
            };
        }

        return implode("", array_map($func, $scripts));
    }
}
