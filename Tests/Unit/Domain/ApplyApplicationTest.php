<?php
namespace Pixelant\PxaIntelliplanJobs\Tests\Unit\Domain\Model;

use Nimut\TestingFramework\TestCase\UnitTestCase;
use Pixelant\PxaIntelliplanJobs\Domain\Model\ApplyApplication;
use Pixelant\PxaIntelliplanJobs\Domain\Model\Job;


class ApplyApplicationTest extends UnitTestCase
{
    /**
     * @var ApplyApplication
     */
    protected $subject = null;

    protected function setUp()
    {
        parent::setUp();
        $this->subject = new ApplyApplication();
    }

    protected function tearDown()
    {
        parent::tearDown();
        unset($this->subject);
    }

    /**
     * @test
     */
    public function accountTicketCanBeSet()
    {
        $value = 'aajjajajaja';

        $this->subject->setAccountTicket($value);

        $this->assertEquals($value, $this->subject->getAccountTicket());
    }


    /**
     * @test
     */
    public function emailCanBeSet()
    {
        $value = 'email';

        $this->subject->setEmail($value);

        $this->assertEquals($value, $this->subject->getEmail());
    }

    /**
     * @test
     */
    public function jobCanBeSet()
    {
        $value = new Job();

        $this->subject->setJob($value);

        $this->assertSame($value, $this->subject->getJob());
    }
}
