<?php

namespace Blueprint\DesignHelper;

use PHPUnit\Framework\TestCase;

class LinkTest extends TestCase
{
    public function testRun()
    {
        $testLink = [
            'rel' => 'test',
            'type' => 'test/test',
            'href' => 'test'
        ];

        $helper = new Link();
        $helper->run($testLink);

        $links = $helper->get('test/test');
        $this->assertSame($testLink['href'], $links[0]);
    }

    public function testSetLink()
    {
        $testLink = [
            'rel' => 'test',
            'type' => 'test/test',
            'href' => 'test'
        ];

        $helper = new Link();
        $helper->add($testLink);
        $helper->add($testLink);

        $this->assertCount(2, $helper->get('test/test'));

        $helper->set($testLink);
        $this->assertCount(1, $helper->get('test/test'));
    }

    public function testCanonical()
    {
        $helper = new Link();
        $helper->setCanonical('test-url');
        $helper->setCanonical('test-url-2');

        $this->assertSame("<link rel=\"canonical\" href=\"test-url-2\" />\n", (string) $helper);
    }

    public function testStylesheet()
    {
        $helper = new Link();
        $helper->addCss('test.css');
        $helper->addCss('test-print.css', 'print');

        $this->assertSame(
            "<link rel=\"stylesheet\" type=\"text/css\" href=\"test.css\" media=\"screen\" />\n<link rel=\"stylesheet\" type=\"text/css\" href=\"test-print.css\" media=\"print\" />\n",
            (string) $helper
        );
    }

    public function testName()
    {
        $helper = new Link();

        $this->assertSame('link', $helper->getName());
    }

    public function testReturnSelf()
    {
        $helper = new Link();
        $this->assertSame($helper, $helper->run([]));
    }
}
