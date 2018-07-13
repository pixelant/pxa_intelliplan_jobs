<?php

namespace Pixelant\PxaIntelliplanJobs\Tests\Unit\ViewHelpers;

use Nimut\TestingFramework\TestCase\ViewHelperBaseTestcase;
use PHPUnit\Framework\MockObject\MockObject;
use Pixelant\PxaIntelliplanJobs\ViewHelpers\ExplodeViewHelper;
use TYPO3\CMS\Fluid\Core\Rendering\RenderingContextInterface;

/**
 * Class ExplodeViewHelperTest
 * @package Pixelant\PxaIntelliplanJobs\Tests\Unit\ViewHelpers
 */
class ExplodeViewHelperTest extends ViewHelperBaseTestcase
{
    /**
     * @var ExplodeViewHelper|MockObject
     */
    protected $viewHelper = null;

    protected function setUp()
    {
        parent::setUp();
        $this->viewHelper = $this->createPartialMock(ExplodeViewHelper::class, ['renderChildren']);
        $this->injectDependenciesIntoViewHelper($this->viewHelper);
        $this->viewHelper->initializeArguments();
    }

    protected function tearDown()
    {
        parent::tearDown();
        unset($this->viewHelper);
    }

    /**
     * @test
     */
    public function explodeWithClosureWillExplode()
    {
        $closure = function () {
            return 'test,value2,value,,';
        };
        $mocRenderingContextInterface = $this->createMock(RenderingContextInterface::class);
        $expect = ['test', 'value2', 'value'];

        $result = ExplodeViewHelper::renderStatic(['delimiter' => ',', 'value' => ''], $closure, $mocRenderingContextInterface);

        $this->assertEquals($expect, $result);
    }

    /**
     * @test
     */
    public function explodeWithValueWillExplode()
    {
        $value = 'test,value2,value,,';
        $closure = function () {};

        $mocRenderingContextInterface = $this->createMock(RenderingContextInterface::class);
        $expect = ['test', 'value2', 'value'];

        $result = ExplodeViewHelper::renderStatic(['delimiter' => ',', 'value' => $value], $closure, $mocRenderingContextInterface);

        $this->assertEquals($expect, $result);
    }
}
