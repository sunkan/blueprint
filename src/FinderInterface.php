<?php

namespace Blueprint;

interface FinderInterface
{
    public function findTemplate(string $file, string $type = null): string;
}
