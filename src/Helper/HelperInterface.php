<?php
namespace Blueprint\Helper;

use Blueprint\TemplateInterface;

interface IHelper
{
    public function getName(): string;
    public function run(array $args);
    public function setTemplate(TemplateInterface $template);
    public function getTemplate(): TemplateInterface;
    public function hasTemplate(): bool;
}
