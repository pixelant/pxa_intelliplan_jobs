<?php

namespace Pixelant\PxaIntelliplanJobs\Tests\Unit;

use Nimut\TestingFramework\MockObject\AccessibleMockObjectInterface;
use Nimut\TestingFramework\TestCase\UnitTestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Pixelant\PxaIntelliplanJobs\Controller\JobController;

/**
 * Class JobAjaxControllerTest
 * @package Pixelant\PxaIntelliplanJobs\Tests\Unit
 */
class JobControllerTest extends UnitTestCase
{
    /**
     * @var JobController|AccessibleMockObjectInterface|MockObject
     */
    protected $subject = null;

    protected function setUp()
    {
        parent::setUp();
        $this->subject = $this->getAccessibleMock(
            JobController::class,
            ['dummy'],
            [],
            '',
            false
        );
    }

    protected function tearDown()
    {
        parent::tearDown();
        unset($this->subject);
    }

    /**
     * @test
     */
    public function sortArrayAlphabeticallyReturnArraySortedByValuesAndKeepKeys()
    {
        $array = [22 => 'Dce', 11 => 'Abc', 2 => 'Cdr', 3 => 'Åcb'];
        $expect = [11 => 'Abc', 3 => 'Åcb', 2 => 'Cdr', 22 => 'Dce'];

        $this->assertEquals($expect, $this->subject->_call('sortArrayAlphabetically', $array));
    }
}
