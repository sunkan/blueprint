<?php

namespace Blueprint\Helper;

use Blueprint\TemplateInterface;

interface ResolverInterface
{
    public function resolve(string $method, TemplateInterface $template): HelperInterface;
}
