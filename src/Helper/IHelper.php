<?php
namespace Blueprint\Helper;

use Blueprint\ITemplate;

interface IHelper
{
    public function getName();
    public function run($args);
    public function setTemplate(ITemplate $template);
    public function getTemplate();
    public function hasTemplate();
}
