<?php
namespace Pixelant\PxaIntelliplanJobs\Tests\Unit;

use Nimut\TestingFramework\MockObject\AccessibleMockObjectInterface;
use Nimut\TestingFramework\TestCase\UnitTestCase;
use Pixelant\PxaIntelliplanJobs\Controller\JobAjaxController;
use Pixelant\PxaIntelliplanJobs\Domain\Model\DTO\ShareJob;

/**
 * Class JobAjaxControllerTest
 * @package Pixelant\PxaIntelliplanJobs\Tests\Unit
 */
class JobAjaxControllerTest extends UnitTestCase
{
    /**
     * @var JobAjaxController|AccessibleMockObjectInterface
     */
    protected $subject = null;

    protected function setUp()
    {
        parent::setUp();
        $this->subject = $this->getAccessibleMock(
            JobAjaxController::class,
            ['translate'],
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
    public function radioFieldsWillBeExcludedAfterCommentGeneration()
    {
        $settings['applyJob']['fields']['noCvRadios'] = 'radio1,radio2';
        $fields = [
            'radio1' => 'test 123',
            'radio2' => '123 342',
            'remain' => 'Remain value'
        ];
        $fieldsExpect = ['remain' => 'Remain value'];

        $this->subject->_set('settings', $settings);

        $this->subject->_callRef('generateCommentFieldAndExcludeItFromFields', $fields);

        $this->assertEquals($fieldsExpect, $fields);
    }

    /**
     * @test
     */
    public function addErrorAddErrorToResponseErrors()
    {
        $field = 'test';
        $message = 'Test message';
        $message2 = 'Test message 22';

        $expect = [$field => [$message, $message2]];

        $this->subject->_call('addError', $field, $message);
        $this->subject->_call('addError', $field, $message2);

        $this->assertEquals($expect, $this->subject->_get('responseErrors'));
    }

    /**
     * @test
     */
    public function validateShareSetDefaultEmailIfSenderEmailIsNotSet()
    {
        $settings['shareJob']['defaultSenderEmail'] = 'default@mail.com';
        $shareJob = new ShareJob();

        $this->subject->_set('settings', $settings);

        $this->assertFalse($this->subject->_call('validateShare', $shareJob));
        $this->assertEquals($settings['shareJob']['defaultSenderEmail'], $shareJob->getSenderEmail());
    }

    /**
     * @test
     */
    public function validateShareJobFailValidationIfFieldsAreHasInccorectValues()
    {
        $settings['shareJob']['defaultSenderEmail'] = 'default@mail.com';
        $this->subject->_set('settings', $settings);

        $shareJob = new ShareJob();
        $shareJob->setShareUrl('test');
        $shareJob->setReceiverEmail('bademail');
        $shareJob->setSenderName('Sender');
        $shareJob->setReceiverName('Receiver Name');

        $this->subject->_set('settings', $settings);

        $this->assertFalse($this->subject->_call('validateShare', $shareJob));
    }

    /**
     * @test
     */
    public function validateShareJobPassValidationIfAllFieldsAreSetAndValidEmail()
    {
        $settings['shareJob']['defaultSenderEmail'] = 'default@mail.com';
        $this->subject->_set('settings', $settings);

        $shareJob = new ShareJob();
        $shareJob->setShareUrl('test');
        $shareJob->setReceiverEmail('niceemail@test.com');
        $shareJob->setSenderName('Sender');
        $shareJob->setReceiverName('Receiver Name');

        $this->subject->_set('settings', $settings);

        $this->assertTrue($this->subject->_call('validateShare', $shareJob));
    }

    /**
     * @test
     */
    public function validateApplyJobFilesFailsIfNotAllRequiredFilesUploaded()
    {
        $settings['applyJob']['fields']['requiredFilesFields'] = 'cv,letter';
        $this->subject->_set('settings', $settings);

        $_FILES_BACKUP = $_FILES;

        $_FILES = [
            'tx_pxaintelliplanjobs_pi2' => [
                'error' => [
                    'applyJobFiles' => [
                        'cv' => 4,
                        'letter' => 0
                    ]
                ]
            ]
        ];

        $this->assertFalse($this->subject->_call('validateApplyJobFiles'));

        $_FILES = $_FILES_BACKUP;
    }

    /**
     * @test
     */
    public function validateApplyJobPassIfAllRequiredFilesUploaded()
    {
        $settings['applyJob']['fields']['requiredFilesFields'] = 'cv,letter';
        $this->subject->_set('settings', $settings);

        $_FILES_BACKUP = $_FILES;

        $_FILES = [
            'tx_pxaintelliplanjobs_pi2' => [
                'error' => [
                    'applyJobFiles' => [
                        'cv' => 0,
                        'letter' => 0
                    ]
                ]
            ]
        ];

        $this->assertTrue($this->subject->_call('validateApplyJobFiles'));

        $_FILES = $_FILES_BACKUP;
    }

    /**
     * @test
     */
    public function validateApplyJobFieldsRequiredFieldValidation()
    {
        $validationRules = [
            'name' => 'required',
            'surname' => 'required'
        ];

        $fields = [
            'name' => 'name',
            'surname' => ''
        ];
        $this->assertFalse($this->subject->_call('validateApplyJobFields', $fields, $validationRules));

        $fields = [
            'name' => 'name',
            'surname' => 'valid'
        ];
        $this->assertTrue($this->subject->_call('validateApplyJobFields', $fields, $validationRules));
    }

    /**
     * @test
     */
    public function validateApplyJobFieldsEmailFieldValidation()
    {
        $validationRules = [
            'name' => 'required',
            'email' => 'email,required',
            'email2' => 'email'
        ];

        $fields = [
            'name' => 'name',
            'email' => '',
            'email2' => 'notvalidemail'
        ];
        $this->assertFalse($this->subject->_call('validateApplyJobFields', $fields, $validationRules));

        $fields = [
            'name' => 'name',
            'email' => 'email@site.com',
            'email2' => 'email@site.com'
        ];
        $this->assertTrue($this->subject->_call('validateApplyJobFields', $fields, $validationRules));
    }

    /**
     * @test
     */
    public function validateApplyJobFieldsPhoneFieldValidation()
    {
        $validationRules = [
            'name' => 'required',
            'phone' => 'phone',
            'phone2' => 'phone'
        ];

        $fields = [
            'name' => 'name',
            'phone' => '',
            'phone2' => '123aaa'
        ];
        $this->assertFalse($this->subject->_call('validateApplyJobFields', $fields, $validationRules));

        $fields = [
            'name' => 'name',
            'phone' => '+3111111',
            'phone2' => '8821478-963'
        ];
        $this->assertTrue($this->subject->_call('validateApplyJobFields', $fields, $validationRules));
    }

    /**
     * @test
     */
    public function validateApplyJobFieldsCheckboxFieldValidation()
    {
        $validationRules = [
            'name' => 'required',
            'agree_on_terms' => 'agreeCheckbox'
        ];

        $fields = [
            'name' => 'name',
            'agree_on_terms' => ''
        ];
        $this->assertFalse($this->subject->_call('validateApplyJobFields', $fields, $validationRules));

        $fields = [
            'name' => 'name',
            'agree_on_terms' => '1'
        ];
        $this->assertTrue($this->subject->_call('validateApplyJobFields', $fields, $validationRules));
    }
}
