<?php
namespace Blueprint\DesignHelper;

use Blueprint\Helper\AHelper;

class Link extends AHelper
{
    protected $_link = [];

    public function getName()
    {
        return 'link';
    }

    public function run($args)
    {
        $argc = count($args);
        if ($argc == 0) {
            return $this;
        }
        return $this->add($args);
    }

    public function add($spec)
    {
        $this->_link[] = $spec;

        return $this;
    }

    public function set($spec)
    {
        foreach ($this->_link as $link) {
            if ($link['rel'] == $spec['rel']) {
                unset($link);
            }
        }
        $this->add($spec);
    }

    public function get($type)
    {
        $return = array();
        foreach ($this->_link as $obj) {
            if ($obj['type'] == $type) {
                $return[] = $obj['href'];
            }
        }
        return $return;
    }

    public function setCanonical($href)
    {
        $spec['rel']  = 'canonical';
        $spec['href'] = $href;

        return $this->set($spec);
    }

    public function addCss($href, $media = 'screen')
    {
        $spec['rel']  = 'stylesheet';
        $spec['type'] = 'text/css';
        $spec['href'] = $href;
        $spec['media'] = $media;

        return $this->add($spec);
    }

    public function render($func = null)
    {
        if ($func === null) {
            $func = function ($obj) {
                $tpl = '<link %s />'."\n";
                $tpl2 = ' %s="%s" ';
                $str = '';
                foreach ($obj as $key => $value) {
                    $str .= sprintf($tpl2, $key, $value);
                }
                return sprintf($tpl, $str);
            };
        }
        return implode('', array_map($func, $this->_link));
    }

    public function __toString()
    {
        return $this->render();
    }
}
