<?php

namespace Pixelant\PxaIntelliplanJobs\Tests\Unit\Services;

use Nimut\TestingFramework\MockObject\AccessibleMockObjectInterface;
use Nimut\TestingFramework\TestCase\UnitTestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Pixelant\PxaIntelliplanJobs\Services\IntelliplanImportService;

/**
 * Class IntelliplanImportServiceTest
 * @package Pixelant\PxaIntelliplanJobs\Tests\Unit\Services
 */
class IntelliplanImportServiceTest extends UnitTestCase
{
    /**
     * @var IntelliplanImportService|AccessibleMockObjectInterface|MockObject
     */
    protected $subject = null;

    protected function setUp()
    {
        parent::setUp();
        $this->subject = $this->getAccessibleMock(
            IntelliplanImportService::class,
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
    public function getClearCacheCommandForMultipleValuesConvertClearCacheToArrayOfCommands()
    {
        $clearCache = '220,tag1,tag2,11';
        $expect = ['220', 'cacheTag:tag1', 'cacheTag:tag2', '11'];

        $this->assertEquals(
            $expect,
            $this->subject->_call('getClearCacheCommands', $clearCache)
        );
    }

    /**
     * @test
     */
    public function getClearCacheCommandForSinglePageValueConvertClearCacheToArrayOfCommands()
    {
        $clearCache = '220';
        $expect = ['220'];

        $this->assertEquals(
            $expect,
            $this->subject->_call('getClearCacheCommands', $clearCache)
        );
    }

    /**
     * @test
     */
    public function getClearCacheCommandForSingleCacheTagConvertClearCacheToArrayOfCommands()
    {
        $clearCache = 'tagJob';
        $expect = ['cacheTag:tagJob'];

        $this->assertEquals(
            $expect,
            $this->subject->_call('getClearCacheCommands', $clearCache)
        );
    }
}
