<?php
namespace Blueprint\Helper;

use Blueprint\TemplateInterface;

abstract class AbstractHelper implements HelperInterface
{
    protected $templateEngine;

    public function setTemplate(TemplateInterface $template)
    {
        $this->templateEngine = $template;
        return $this;
    }

    public function getTemplate(): TemplateInterface
    {
        return $this->templateEngine;
    }

    public function hasTemplate(): bool
    {
        return $this->templateEngine instanceof TemplateInterface;
    }
}
