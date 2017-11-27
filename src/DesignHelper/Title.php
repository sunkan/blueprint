<?php
namespace Blueprint\DesignHelper;

use Blueprint\Helper\AbstractHelper;

class Title extends AbstractHelper
{
    protected $title = '';

    public function getName(): string
    {
        return 'title';
    }

    public function run(array $argv)
    {
        $argc = count($argv);
        if ($argc == 0) {
            return $this;
        }
        return $this->set($argv[0]);
    }

    public function set($spec)
    {
        $this->title = $spec;
        return $this;
    }

    public function render()
    {
        return $this->title;
    }

    public function __toString()
    {
        return $this->render();
    }
}
