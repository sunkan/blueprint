<?php
namespace Blueprint;

interface ITemplate
{
    public function render();
    public function assign($key, $value = null);
}
