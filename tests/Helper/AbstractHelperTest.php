<?php

namespace Blueprint\Helper;

use Blueprint\TemplateInterface;
use PHPUnit\Framework\TestCase;

class AbstractHelperTest extends TestCase
{
    public function testNoTemplateEngine()
    {
        $this->expectException(\TypeError::class);

        /** @var AbstractHelper $helper */
        $helper = $this->getMockForAbstractClass(AbstractHelper::class);

        $this->assertFalse($helper->hasTemplate());

        $helper->getTemplate();
    }

    public function testSetTemplateEngine()
    {
        /** @var TemplateInterface $engine */
        $engine = $this->getMockForAbstractClass(TemplateInterface::class);

        /** @var AbstractHelper $helper */
        $helper = $this->getMockForAbstractClass(AbstractHelper::class);

        $helper->setTemplate($engine);

        $this->assertTrue($helper->hasTemplate());

        $this->assertSame($engine, $helper->getTemplate());
    }
}
