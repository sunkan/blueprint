<?php
namespace Blueprint;

interface CallableInterface
{
    public function callHelper(string $method, array $args);
}
