<?php
namespace Blueprint;

interface ITemplate
{
    public function render(): string;
    public function assign($key, $value = null);
}
