<?php

namespace Blueprint;

use Blueprint\Exception\TemplateNotFoundException;
use PHPUnit\Framework\TestCase;

class DefaultFinderTest extends TestCase
{
    /**
     * @var FinderInterface
     */
    protected $finder;

    protected function setUp()
    {
        $this->finder = new DefaultFinder(
            [
                __DIR__.'/resources/',
                __DIR__.'/resources/folder2/',
            ]
        );
    }

    public function testFindDefaultTemplate()
    {
        $tpl = $this->finder->findTemplate('tpl');
        $this->assertSame(__DIR__.'/resources/folder2/tpl.php', $tpl);
    }

    public function testFindTemplateWithCustomType()
    {
        $tpl = $this->finder->findTemplate('tpl', 'tmp');
        $this->assertSame(__DIR__.'/resources/folder2/tpl.tmp.php', $tpl);
    }

    public function testTemplateNotFound()
    {
        $this->expectException(TemplateNotFoundException::class);
        $this->finder->findTemplate('tpl', 'not-found');
    }

    public function testPathOrderIsLifo()
    {
        $tpl = $this->finder->findTemplate('tpl');
        $this->assertSame(__DIR__.'/resources/folder2/tpl.php', $tpl);

        $tpl = $this->finder->findTemplate('tpl', 'test');
        $this->assertSame(__DIR__.'/resources/tpl.test.php', $tpl);
    }

    public function testFindInSubFolder()
    {
        $tpl = $this->finder->findTemplate('folder2/tpl');
        $this->assertSame(__DIR__.'/resources/folder2/tpl.php', $tpl);
    }
}
