<?php
namespace Pixelant\PxaIntelliplanJobs\Tests\Unit\Domain\Model;

use Nimut\TestingFramework\TestCase\UnitTestCase;
use Pixelant\PxaIntelliplanJobs\Domain\Model\ContentElement;

class ContentElementTest extends UnitTestCase
{
    /**
     * @var ContentElement
     */
    protected $subject = null;

    protected function setUp()
    {
        parent::setUp();
        $this->subject = new ContentElement();
    }

    protected function tearDown()
    {
        parent::tearDown();
        unset($this->subject);
    }

    /**
     * @test
     */
    public function uidCanBeSet()
    {
        $this->subject->_setProperty('uid', 123);

        $this->assertEquals(123, $this->subject->getUid());
    }
}
