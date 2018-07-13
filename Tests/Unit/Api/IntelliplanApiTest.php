<?php
namespace Pixelant\PxaIntelliplanJobs\Tests\Unit\Api;

use Nimut\TestingFramework\MockObject\AccessibleMockObjectInterface;
use Nimut\TestingFramework\TestCase\UnitTestCase;
use Pixelant\PxaIntelliplanJobs\Api\IntelliplanApi;

class IntelliplanApiTest extends UnitTestCase
{
    /**
     * @var IntelliplanApi|AccessibleMockObjectInterface
     */
    protected $subject = null;

    protected function setUp()
    {
        parent::setUp();
        $this->subject = $this->getAccessibleMock(
            IntelliplanApi::class,
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
    public function apendSessionIdToUrlAddSessionId()
    {
        $sessionId = '4411aaa333';
        $url = 'https://kundenab.app.intelliplan.eu/API/CandidatePortal_v1/Languages/GetAllLanguages?parner_code=123';
        $expect =  'https://kundenab.app.intelliplan.eu/API/CandidatePortal_v1/Languages/GetAllLanguages?parner_code=123&intelliplan_session_id=4411aaa333';

        $this->assertEquals($expect, $this->subject->_call('apendSessionIdToUrl', $url, $sessionId));
    }
}
