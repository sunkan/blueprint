<?php

namespace Blueprint\Helper;

use Blueprint\Exception\HelperNotFoundException;
use Blueprint\TemplateInterface;

class ResolverList extends \ArrayObject implements ResolverInterface
{
    /**
     * @param $method
     * @param TemplateInterface $template
     * @return HelperInterface
     * @throws HelperNotFoundException
     */
    public function resolve(string $method, TemplateInterface $template): HelperInterface
    {
        /** @var ResolverInterface $resolver */
        foreach ($this as $resolver) {
            try {
                $helper = $resolver->resolve($method, $template);
                if ($helper) {
                    return $helper;
                }
            } catch (HelperNotFoundException $hnfe) {
            }
        }

        throw new HelperNotFoundException("Could't find helper: " . $method);
    }
}
