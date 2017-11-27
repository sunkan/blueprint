<?php
namespace Blueprint;

class Layout extends Extended
{
    protected $content = null;

    public function setContent(TemplateInterface $tpl)
    {
        $this->content = $tpl;
        return $this;
    }

    public function render($file = null): string
    {
        if ($this->content instanceof TemplateInterface) {
            $this->assign('content', $this->content->render());
        }
        return parent::render($file);
    }
}
