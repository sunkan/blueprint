<?php
namespace Blueprint\DesignHelper;

use Blueprint\FinderInterface;
use Blueprint\Helper\AbstractHelper;

class Design extends AbstractHelper
{
    protected $type = null;

    /**
     * @var FinderInterface
     */
    protected $templateFinder;

    public function __construct(FinderInterface $finder)
    {
        $this->templateFinder = $finder;
    }

    public function getName(): string
    {
        return 'design';
    }

    public function run(array $args)
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

    private function render($file, $type = null)
    {
        $view = $this->templateEngine;
        $file = $this->templateFinder->findTemplate('layout/' . $file, $type ?? $this->type);

        return include $file;
    }

    public function header(string $type = null)
    {
        return $this->render('header', $type);
    }

    public function footer(string $type = null)
    {
        return $this->render('footer', $type);
    }
}
