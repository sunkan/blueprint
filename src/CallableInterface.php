<?php
namespace Blueprint;

interface ICallable
{
    public function callHelper(string $method, array $args);
}
