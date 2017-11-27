<?php

namespace Blueprint\Helper;

class ClosureHelper extends AbstractHelper
{
    protected $clb;

    public function __construct(callable $clb)
    {
        $this->clb = $clb;
    }

    public function getName(): string
    {
        return 'closureHelper';
    }

    public function run(array $args)
    {
        $clb = $this->clb;
        return $clb($args);
    }

    public function getCallback()
    {
        return $this->clb;
    }
}
