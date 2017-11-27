<?php

namespace Blueprint\Helper;

use Blueprint\Exception\HelperNotFoundException;
use Blueprint\TemplateInterface;
use PHPUnit\Framework\TestCase;

class ResolverListTest extends TestCase
{
    public function testEmptyList()
    {
        /** @var TemplateInterface $engine */
        $engine = $this->getMockForAbstractClass(TemplateInterface::class);

        $this->expectException(HelperNotFoundException::class);
        $list = new ResolverList();

        $list->resolve('test', $engine);
    }

    public function testResolve()
    {
        /** @var TemplateInterface $engine */
        $engine = $this->getMockForAbstractClass(TemplateInterface::class);

        $helper = $this->getMockForAbstractClass(AbstractHelper::class);

        $resolver = $this->getMockForAbstractClass(ResolverInterface::class);
        $resolver->expects($this->any())->method('resolve')->will($this->returnValue($helper));
        $list = new ResolverList();
        $list[] = $resolver;

        $returnedHelper = $list->resolve('test', $engine);

        $this->assertSame($helper, $returnedHelper);
    }
}
