<?php
namespace Blueprint\DesignHelper;

use Blueprint\Helper\AHelper;

class Design extends AHelper
{
    protected $type = false;

    public function getName()
    {
        return 'design';
    }

    public function run($args)
    {
        $argc = count($args);
        if ($argc == 0) {
            return $this;
        }
    }

    public function setLayout($type = false)
    {
        $this->type = $type;
    }

    public function header($file = false)
    {
        if (!$file) {
            $file = 'header';
        }
        $view = $this->template_engine;
        $file .= ($this->type && $this->type != 'default' ? '.' . $this->type : '') . '.php';
        $file = $view->getFilePath('layout/' . $file);
        return include $file;
    }

    public function footer($file = false)
    {
        if (!$file) {
            $file = 'footer';
        }
        $view = $this->template_engine;
        $file .= ($this->type && $this->type != 'default' ? '.' . $this->type : '') . '.php';
        $file = $view->getFilePath('layout/' . $file);
        return include $file;
    }
}
