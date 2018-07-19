<?php
namespace Pixelant\PxaIntelliplanJobs\Tests\Unit\Domain\Model;

use Nimut\TestingFramework\TestCase\UnitTestCase;
use Pixelant\PxaIntelliplanJobs\Domain\Model\DTO\ShareJob;


class ShareJobTest extends UnitTestCase
{
    /**
     * @var ShareJob
     */
    protected $subject = null;

    protected function setUp()
    {
        parent::setUp();
        $this->subject = new ShareJob();
    }

    protected function tearDown()
    {
        parent::tearDown();
        unset($this->subject);
    }

    /**
     * @test
     */
    public function receiverNameCanBeSet()
    {
        $value = 'test-value';

        $this->subject->setReceiverName($value);

        $this->assertEquals($value, $this->subject->getReceiverName());
    }


    /**
     * @test
     */
    public function receiverEmailCanBeSet()
    {
        $value = 'email';


        $this->subject->setReceiverEmail($value);

        $this->assertEquals($value, $this->subject->getReceiverEmail());
    }

    /**
     * @test
     */
    public function senderNameCanBeSet()
    {
        $value = 'test-value';

        $this->subject->setSenderName($value);

        $this->assertEquals($value, $this->subject->getSenderName());
    }


    /**
     * @test
     */
    public function senderEmailCanBeSet()
    {
        $value = 'email';

        $this->subject->setSenderEmail($value);

        $this->assertEquals($value, $this->subject->getSenderEmail());
    }

    /**
     * @test
     */
    public function shareUrlCanBeSet()
    {
        $value = 'shareUrl';

        $this->subject->setShareUrl($value);

        $this->assertEquals($value, $this->subject->getShareUrl());
    }

    /**
     * @test
     */
    public function shareUrlDecodeWillDecodeUrl()
    {
        $value = 'https://shareUrl.com/test';

        $this->subject->setShareUrl(urlencode($value));

        $this->assertEquals($value, $this->subject->getDecodedShareUrl());
    }

    /**
     * @test
     */
    public function subjectCanBeSet()
    {
        $subject = 'subject';

        $this->subject->setSubject($subject);

        $this->assertEquals($subject, $this->subject->getSubject());
    }
}
