<?php

namespace Blueprint\DesignHelper;

use PHPUnit\Framework\TestCase;

class ScriptTest extends TestCase
{
    public function testRun()
    {
        $testScript = [
            '/file.js',
            'text/javascript',
            'top'
        ];

        $helper = new Script();
        $helper->run($testScript);

        $this->assertTrue($helper->has('top'));
        $scripts = $helper->get('top');
        $this->assertSame($testScript[0], $scripts[0]);
    }

    public function testRunDefaultValues()
    {
        $testScript = [
            '/file.js'
        ];

        $helper = new Script();
        $helper->run($testScript);

        $this->assertTrue($helper->has('bottom'));
        $scripts = $helper->get('bottom');
        $this->assertSame($testScript[0], $scripts[0]);
        $this->assertSame('<script type="text/javascript" src="/file.js"></script>', rtrim($helper->render()));
    }

    public function testInlineScript()
    {
        $script = 'alert("test")';
        $helper = new Script();
        $helper->start();
        echo $script;
        $helper->end();

        $renderedScript = $helper->render('onload');
        $this->assertSame("\n(function(){\nalert(\"test\")\n})();\n", $renderedScript);
    }

    public function testMode()
    {
        $helper = new Script();
        $helper->add('file.js', 'dev');
        $helper->add('file-2.js', 'prod');
        $helper->add('file-3.js');

        $scriptTags = $helper->render('bottom');
        $expectedTags = "<script type=\"text/javascript\" src=\"file.js\"></script>\n";
        $expectedTags .= "<script type=\"text/javascript\" src=\"file-3.js\"></script>\n";
        $this->assertSame($expectedTags, $scriptTags);

        $helper->setMode(Script::PRODUCTION);

        $scriptTags = $helper->render('bottom');
        $expectedTags = "<script type=\"text/javascript\" src=\"file-2.js\"></script>\n";
        $expectedTags .= "<script type=\"text/javascript\" src=\"file-3.js\"></script>\n";
        $this->assertSame($expectedTags, $scriptTags);
    }

    public function testInvalidMode()
    {
        $this->expectException(\InvalidArgumentException::class);
        $helper = new Script();
        $helper->setMode('test');
    }

    public function testCustomRenderCallback()
    {
        $helper = new Script();
        $helper->add('file-1.js');
        $helper->add('file-2.js');

        $returnValue = $helper->render(function ($script) {
            return '[script ' . $script->src . "]\n";
        });

        $this->assertSame("[script file-1.js]\n[script file-2.js]\n", $returnValue);
    }

    public function testName()
    {
        $helper = new Script();

        $this->assertSame('script', $helper->getName());
    }

    public function testReturnSelf()
    {
        $helper = new Script();
        $this->assertSame($helper, $helper->run([]));
    }
}
