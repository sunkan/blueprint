<?php

namespace Blueprint\Helper;

use Blueprint\Exception\HelperNotFoundException;
use Blueprint\TemplateInterface;
use PHPUnit\Framework\TestCase;

class ResolverTest extends TestCase
{
    public function testResolveCallableFunction()
    {
        /** @var TemplateInterface $engine */
        $engine = $this->getMockForAbstractClass(TemplateInterface::class);

        $clb = function () {
            return 'clb return';
        };

        $resolver = new Resolver(function () {
            return null;
        });
        $resolver->addFunction('test', $clb);

        /** @var ClosureHelper $clbHelper */
        $clbHelper = $resolver->resolve('test', $engine);

        $this->assertInstanceOf(ClosureHelper::class, $clbHelper);
        $this->assertSame($clb, $clbHelper->getCallback());
        $this->assertSame('clb return', $clbHelper->run([]));
    }

    public function testAddClass()
    {
        /** @var TemplateInterface $engine */
        $engine = $this->getMockForAbstractClass(TemplateInterface::class);

        $helper = $this->getMockForAbstractClass(AbstractHelper::class);
        $helper->method('getName')->will($this->returnValue('test'));
        $helper->method('run')->will($this->returnValue('test-class-runner'));
        /** @var AbstractHelper $helper */

        $resolver = new Resolver(function () {
            return null;
        });
        $resolver->addClass($helper);

        $returnedHelper = $resolver->resolve('test', $engine);

        $this->assertSame($helper, $returnedHelper);
        $this->assertSame('test-class-runner', $returnedHelper->run([]));
    }

    public function testHelperNotFound()
    {
        $this->expectException(HelperNotFoundException::class);

        /** @var TemplateInterface $engine */
        $engine = $this->getMockForAbstractClass(TemplateInterface::class);

        $resolver = new Resolver(function () {
            return null;
        });
        $resolver->resolve('test', $engine);
    }

    public function testNamespaceResolve()
    {
        /** @var TemplateInterface $engine */
        $engine = $this->getMockForAbstractClass(TemplateInterface::class);

        $helper = $this->getMockForAbstractClass(AbstractHelper::class);
        $helper->method('getName')->will($this->returnValue('test'));
        $helper->method('run')->will($this->returnValue('test-namespace-runner'));
        /** @var AbstractHelper $helper */

        $resolver = new Resolver(function () use ($helper) {
            return $helper;
        });
        $resolver->setNs(['test']);
        $resolver->addNs('test//folder');

        $returnedHelper = $resolver->resolve('test', $engine);

        $this->assertSame($helper, $returnedHelper);
    }
}
