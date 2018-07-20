<?php
namespace Pixelant\PxaIntelliplanJobs\Tests\Unit;

use Nimut\TestingFramework\MockObject\AccessibleMockObjectInterface;
use Nimut\TestingFramework\TestCase\UnitTestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Pixelant\PxaIntelliplanJobs\Controller\JobAjaxController;
use Pixelant\PxaIntelliplanJobs\Domain\Model\DTO\ShareJob;
use Pixelant\PxaIntelliplanJobs\Domain\Model\Job;

/**
 * Class JobAjaxControllerTest
 * @package Pixelant\PxaIntelliplanJobs\Tests\Unit
 */
class JobAjaxControllerTest extends UnitTestCase
{
    /**
     * @var JobAjaxController|AccessibleMockObjectInterface|MockObject
     */
    protected $subject = null;

    protected function setUp()
    {
        parent::setUp();
        $this->subject = $this->getAccessibleMock(
            JobAjaxController::class,
            ['translate', 'uploadToTempFile', 'writeToTempFile', 'getMimeType'],
            [],
            '',
            false
        );
    }

    protected function tearDown()
    {
        parent::tearDown();
        // Reset files, because those doesn't exist
        $this->subject->_set('uploadFiles', []);
        unset($this->subject);
    }

    /**
     * @test
     */
    public function radioFieldsWillBeExcludedAfterCvTextFieldGeneration()
    {
        $settings['applyJob']['fields']['noCvRadios'] = 'radio1,radio2';
        $fields = [
            'radio1' => 'test 123',
            'radio2' => '123 342',
            'remain' => 'Remain value'
        ];
        $fieldsExpect = ['remain' => 'Remain value'];

        $this->subject->_set('settings', $settings);

        $this->subject->_callRef('generateTextFileFromNotSupportedFields', $fields);

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
        $job = new Job();

        $this->subject->_set('settings', $settings);

        $this->assertFalse($this->subject->_call('validateShare', $shareJob, $job));
        $this->assertEquals($settings['shareJob']['defaultSenderEmail'], $shareJob->getSenderEmail());
    }

    /**
     * @test
     */
    public function validateShareJobFailValidationIfFieldsAreHasInccorectValues()
    {
        $settings['shareJob']['defaultSenderEmail'] = 'default@mail.com';
        $this->subject->_set('settings', $settings);
        $job = new Job();

        $shareJob = new ShareJob();
        $shareJob->setShareUrl('test');
        $shareJob->setReceiverEmail('bademail');
        $shareJob->setSenderName('Sender');
        $shareJob->setReceiverName('Receiver Name');

        $this->subject->_set('settings', $settings);

        $this->assertFalse($this->subject->_call('validateShare', $shareJob, $job));
    }

    /**
     * @test
     */
    public function validateShareJobPassValidationIfAllFieldsAreSetAndValidEmail()
    {
        $settings['shareJob']['defaultSenderEmail'] = 'default@mail.com';
        $this->subject->_set('settings', $settings);
        $job = new Job();

        $shareJob = new ShareJob();
        $shareJob->setShareUrl('test');
        $shareJob->setReceiverEmail('niceemail@test.com');
        $shareJob->setSenderName('Sender');
        $shareJob->setReceiverName('Receiver Name');

        $this->subject->_set('settings', $settings);

        $this->assertTrue($this->subject->_call('validateShare', $shareJob, $job));
    }

    /**
     * @test
     * @dataProvider failUploadsDataProvide
     */
    public function validateApplyJobFilesFailsIfNotAllRequiredFilesUploadedOrSomeAreNotAllowed($filesUpload, $mimeType)
    {
        $settings['applyJob']['fields']['requiredFilesFields'] = 'cv,letter';
        $settings['applyJob']['fields']['allowedFileTypes'] = 'doc,docx';
        $settings['applyJob']['fields']['allowedMimeTypes'] = 'text/plain';

        $this->subject->_set('settings', $settings);

        $_FILES_BACKUP = $_FILES;

        $_FILES = $filesUpload;

        $this->subject
            ->expects($this->atLeastOnce())
            ->method('getMimeType')
            ->willReturn($mimeType);

        $this->assertFalse($this->subject->_call('validateApplyJobFiles'));

        $_FILES = $_FILES_BACKUP;
    }

    /**
     * @test
     */
    public function validateApplyJobPassIfAllRequiredFilesUploadedAndAllAllowed()
    {
        $settings['applyJob']['fields']['requiredFilesFields'] = 'cv,letter';
        $settings['applyJob']['fields']['allowedFileTypes'] = 'doc,docx';
        $settings['applyJob']['fields']['allowedMimeTypes'] = 'text/plain';

        $this->subject->_set('settings', $settings);

        $_FILES_BACKUP = $_FILES;

        $_FILES = [
            'tx_pxaintelliplanjobs_pi2' => [
                'error' => [
                    'applyJobFiles' => [
                        'cv' => 0,
                        'letter' => 0
                    ]
                ],
                'name' => [
                    'applyJobFiles' => [
                        'cv' => 'cv.doc',
                        'letter' => 'cv.docx'
                    ]
                ],
                'tmp_name' => [
                    'applyJobFiles' => [
                        'cv' => '/tmp/php/blabla',
                        'letter' => '/tmp/php/blabla'
                    ]
                ]
            ]
        ];

        $this->subject
            ->expects($this->atLeastOnce())
            ->method('getMimeType')
            ->willReturn('text/plain');

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

    /**
     * Simulate different cases where validation of files will fail
     *
     * @return array
     */
    public function failUploadsDataProvide()
    {
        return [
            'notAllRequiredFilesAreUploaded' => [
                'files' => [
                    'tx_pxaintelliplanjobs_pi2' => [
                        'error' => [
                            'applyJobFiles' => [
                                'cv' => 4,
                                'letter' => 0
                            ]
                        ],
                        'name' => [
                            'applyJobFiles' => [
                                'cv' => 'cv.doc',
                                'letter' => 'cv.docx'
                            ]
                        ],
                        'tmp_name' => [
                            'applyJobFiles' => [
                                'cv' => '/tmp/php/blabla123321',
                                'letter' => '/tmp/php/blabla'
                            ]
                        ]
                    ],
                ],
                'mimeType' => 'text/plain'
            ],
            'allRequiredUploadedButExtensionNotAllowed' => [
                'files' => [
                    'tx_pxaintelliplanjobs_pi2' => [
                        'error' => [
                            'applyJobFiles' => [
                                'cv' => 0,
                                'letter' => 0
                            ]
                        ],
                        'name' => [
                            'applyJobFiles' => [
                                'cv' => 'cv.php', // Forbidden extension
                                'letter' => 'cv.docx'
                            ]
                        ],
                        'tmp_name' => [
                            'applyJobFiles' => [
                                'cv' => '/tmp/php/blabla123321',
                                'letter' => '/tmp/php/blabla'
                            ]
                        ]
                    ]
                ],
                'mimeType' => 'text/plain'
            ],
            'allRequiredUploadedButMimeTypeIsNotAllowed' => [
                'files' => [
                    'tx_pxaintelliplanjobs_pi2' => [
                        'error' => [
                            'applyJobFiles' => [
                                'cv' => 0,
                                'letter' => 0
                            ]
                        ],
                        'name' => [
                            'applyJobFiles' => [
                                'cv' => 'cv.doc',
                                'letter' => 'cv.docx'
                            ]
                        ],
                        'tmp_name' => [
                            'applyJobFiles' => [
                                'cv' => '/tmp/php/blabla123321',
                                'letter' => '/tmp/php/blabla'
                            ]
                        ]
                    ]
                ],
                'mimeType' => 'image/jpeg'
            ]
        ];
    }
}
