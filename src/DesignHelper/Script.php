<?php
namespace Blueprint\DesignHelper;

use Blueprint\Helper\AHelper;

class Script extends AHelper
{
    public function getName()
    {
        return 'script';
    }

    /*
     * $argv[0] = src
     * $argv[1] = type
     * $argv[2] = pos
     */
    public function run($argv)
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
        if ($type == 'dev') {
            $mode = 'dev';
            $type = 'text/javascript';
        } elseif ($type == 'prod') {
            $mode = 'prod';
            $type = 'text/javascript';
        }
        $obj = new \stdClass;
        $obj->src = $src;
        $obj->type = $type;
        $obj->position = $pos;
        $obj->mode = $mode;
        $this->_scripts[md5($src)] = $obj;

        return $this;
    }
    protected $on = [
        'load' => '',
        'config' => ''
    ];

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
        $scripts = $this->_scripts;
        $scripts = array_filter($scripts, function ($obj) use ($pos) {
            return ($obj->position == $pos);
        });
        return count($scripts) > 0;
    }

    public function get($pos)
    {
        $return = array();
        foreach ($this->_scripts as $obj) {
            if ($obj->position == $pos) {
                $return[] = $obj->src;
            }
        }
        return $return;
    }

    public function render($func = null, $pos = 'all')
    {
        if (is_string($func) && !is_callable($func)) {
            $pos = strtolower($func);
        }
        if ($pos=='onload') {
            return $this->on['load'];
        }
        if ($pos=='onconfig') {
            return $this->on['config'];
        }
        $scripts = $this->_scripts;
        if ($pos != 'all') {
            $scripts = array_filter($scripts, function ($obj) use ($pos) {
                return ($obj->position == $pos);
            });
        }
        if (!is_callable($func)) {
            $engine = $this->template_engine;
            $func = function ($obj) use ($engine) {
                if ($obj->mode && $engine->site_mode != $obj->mode) {
                    return '';
                }
                $tpl = '<script type="%s" src="%s"></script>'."\n";
                return sprintf($tpl, $obj->type, $obj->src);
            };
        }

        return implode("", array_map($func, $scripts));
    }

    protected $_scripts = array();
}
