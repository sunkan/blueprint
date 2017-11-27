<?php

namespace Blueprint\DesignHelper;

use PHPUnit\Framework\TestCase;

class TitleTest extends TestCase
{

    public function testRun()
    {
        $testTitle = 'test title';

        $helper = new Title();
        $helper->run([$testTitle]);

        $this->assertSame($testTitle, $helper->render());
        $this->assertSame($testTitle, (string) $helper);
    }

    public function testName()
    {
        $helper = new Title();

        $this->assertSame('title', $helper->getName());
    }

    public function testReturnSelf()
    {
        $helper = new Title();
        $this->assertSame($helper, $helper->run([]));
    }
}
