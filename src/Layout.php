<?php
namespace Blueprint;

class Layout extends Extended
{
    protected $_content = null;

    public function setContent(ITemplate $tpl)
    {
        $this->_content = $tpl;
        return $this;
    }

    public function render($file = null)
    {
        if ($this->_content instanceof ITemplate) {
            $this->assign('content', $this->_content->render());
        }
        return parent::render($file);
    }
}
