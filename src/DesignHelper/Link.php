<?php
namespace Blueprint\DesignHelper;

use Blueprint\Helper\AbstractHelper;

class Link extends AbstractHelper
{
    protected $links = [];

    public function getName(): string
    {
        return 'link';
    }

    public function run(array $args)
    {
        $argc = count($args);
        if ($argc == 0) {
            return $this;
        }
        return $this->add($args);
    }

    public function add($spec)
    {
        $this->links[] = $spec;

        return $this;
    }

    public function set($spec)
    {
        for ($count = count($this->links)-1; $count >= 0; $count--) {
            $link = $this->links[$count];
            if ($link['rel'] == $spec['rel']) {
                unset($this->links[$count]);
            }
        }
        $this->add($spec);
    }

    public function get($type)
    {
        $return = array();
        foreach ($this->links as $obj) {
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
                $tpl = '<link %s/>'."\n";
                $tpl2 = '%s="%s" ';
                $str = '';
                foreach ($obj as $key => $value) {
                    $str .= sprintf($tpl2, $key, $value);
                }
                return sprintf($tpl, $str);
            };
        }
        return implode('', array_map($func, $this->links));
    }

    public function __toString()
    {
        return $this->render();
    }
}
