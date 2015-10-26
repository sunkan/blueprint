<?php
namespace Blueprint\Helper;

use Blueprint\ITemplate;

abstract class AHelper implements IHelper
{
    protected $template_engine;

    public function setTemplate(ITemplate $template)
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
        return $this->template_engine instanceof ITemplate;
    }
}
