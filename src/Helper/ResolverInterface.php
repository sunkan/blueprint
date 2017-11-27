<?php

namespace Blueprint\Helper;

use Blueprint\ITemplate;

interface IResolver
{
    public function resolve($method, ITemplate $template);
}
