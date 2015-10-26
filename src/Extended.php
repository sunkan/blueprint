<?php
namespace Blueprint;

class Extended extends Simple
{
    protected $_template_file = null;
    protected $_paths = [];
    protected $_base = null;

    public function getFilePath($file)
    {
        if (strpos($file, '/') !== 0) {
            foreach ($this->_paths as $path) {
                $tmpFile = $path . $file;
                if (file_exists($tmpFile)) {
                    return $tmpFile;
                }
            }
        }
        return $file;
    }

    public function setTemplate($tpl)
    {
        $this->_template_file = $tpl;
    }

    public function getTemplate()
    {
        return $this->_template_file;
    }

    public function render($file = null)
    {
        $file = $file ?: $this->_template_file;
        $file = $this->getFilePath($file);
        return parent::render($file);
    }

    public function addBasePath($path)
    {
        $this->_paths[] = $path;
        return $this;
    }

    public function setBasePath($path)
    {
        $this->_paths = is_array($path) ? $path : [$path];
    }

    public function fetch($file)
    {
        if (strpos($file, '://') === false) {
            $file = $this->getFilePath($file);
            return \file_get_contents($file);
        }
        return \file_get_contents($file);
    }
}
