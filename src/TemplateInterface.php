<?php
namespace Blueprint;

interface TemplateInterface
{
    public function render(): string;
    public function assign($key, $value = null);
}
