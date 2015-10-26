<?php
namespace Blueprint\DesignHelper;

use Blueprint\Helper\AHelper;

class Title extends AHelper
{
    protected $_title = '';

    public function getName()
    {
        return 'title';
    }

    public function run($argv)
    {
        $argc = count($argv);
        if ($argc == 0) {
            return $this;
        }
        return $this->set($argv[0]);
    }

    public function set($spec)
    {
        $this->_title = $spec;
        return $this;
    }

    public function render($func = null)
    {
        return $this->_title;
    }

    public function __toString()
    {
        return $this->render();
    }
}
