<?php
namespace Blueprint\Helper;

use Blueprint\TemplateInterface;

abstract class AHelperInterface implements HelperInterface
{
    protected $template_engine;

    public function setTemplate(TemplateInterface $template)
    {
        $this->template_engine = $template;
        return $this;
    }

    public function getTemplate()
    {
        return $this->template_engine;
    }

    public function hasTemplate()
    {
        return $this->template_engine instanceof TemplateInterface;
    }
}
