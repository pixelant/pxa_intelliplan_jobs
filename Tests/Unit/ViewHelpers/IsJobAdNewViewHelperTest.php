<?php

namespace Pixelant\PxaIntelliplanJobs\Tests\Unit\ViewHelpers;

use Nimut\TestingFramework\TestCase\ViewHelperBaseTestcase;
use PHPUnit\Framework\MockObject\MockObject;
use Pixelant\PxaIntelliplanJobs\Domain\Model\Job;
use Pixelant\PxaIntelliplanJobs\ViewHelpers\IsJobAdNewViewHelper;
use TYPO3\CMS\Fluid\Core\Rendering\RenderingContextInterface;

/**
 * Class IsJobAdNewViewHelperTest
 * @package Pixelant\PxaIntelliplanJobs\Tests\Unit\ViewHelpers
 */
class IsJobAdNewViewHelperTest extends ViewHelperBaseTestcase
{
    /**
     * @var IsJobAdNewViewHelper|MockObject
     */
    protected $viewHelper = null;

    protected function setUp()
    {
        parent::setUp();
        $this->viewHelper = $this->createPartialMock(IsJobAdNewViewHelper::class, ['renderChildren']);
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
    public function jobIsNewWhilePubDateIsNewerThanGivenNumberOfDays()
    {
        $closure = function () {};

        $pubDate = new \DateTime();
        $pubDate->modify('-3 days'); // One day added, because of API error

        $job = new Job();
        $job->setPubDate($pubDate);

        $argc = [
            'newForDays' => 3,
            'job' => $job
        ];

        $mocRenderingContextInterface = $this->createMock(RenderingContextInterface::class);

        $result = IsJobAdNewViewHelper::renderStatic($argc, $closure, $mocRenderingContextInterface);

        $this->assertTrue($result);
    }

    /**
     * @test
     */
    public function jobIsNewWhilePubDateSameAsGivenNumberOfDays()
    {
        $closure = function () {};

        $pubDate = new \DateTime();
        $pubDate->modify('-2 days');

        $job = new Job();
        $job->setPubDate($pubDate);

        $argc = [
            'newForDays' => 2,
            'job' => $job
        ];

        $mocRenderingContextInterface = $this->createMock(RenderingContextInterface::class);

        $result = IsJobAdNewViewHelper::renderStatic($argc, $closure, $mocRenderingContextInterface);

        $this->assertTrue($result);
    }

    /**
     * @test
     */
    public function jobIsNotNewIfPubDateIsOlderThanGivenNumberOfDays()
    {
        $closure = function () {};

        $pubDate = new \DateTime();
        $pubDate->modify('-7 days'); // One day added, because of API error

        $job = new Job();
        $job->setPubDate($pubDate);

        $argc = [
            'newForDays' => 5,
            'job' => $job
        ];

        $mocRenderingContextInterface = $this->createMock(RenderingContextInterface::class);

        $result = IsJobAdNewViewHelper::renderStatic($argc, $closure, $mocRenderingContextInterface);

        $this->assertFalse($result);
    }
}
