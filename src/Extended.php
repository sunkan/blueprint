<?php
namespace Blueprint;

use Blueprint\Helper\ResolverList;

class Extended extends Simple
{
    /**
     * @var FinderInterface
     */
    protected $templateFinder;

    /**
     * @var string
     */
    protected $templateFile = null;

    public function __construct(FinderInterface $finder, ResolverList $resolverList)
    {
        parent::__construct($resolverList);
        $this->templateFinder = $finder;
    }

    public function setTemplateFinder(FinderInterface $finder)
    {
        $this->templateFinder = $finder;
    }

    public function setTemplate($tpl)
    {
        $this->templateFile = $tpl;
    }

    public function getTemplate()
    {
        return $this->templateFile;
    }

    public function render($file = null): string
    {
        return parent::render($this->templateFinder->findTemplate($file ?? $this->templateFile));
    }
}
