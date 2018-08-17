<?php
declare(strict_types=1);

namespace Pixelant\PxaIntelliplanJobs\Controller;

/***
 *
 * This file is part of the "Intelliplan jobs integration" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2018 Andrii Oprysko <andriy.oprysko@resultify.se>, Resultify
 *
 ***/

use Pixelant\PxaIntelliplanJobs\Api\IntelliplanApi;
use Pixelant\PxaIntelliplanJobs\Domain\Model\ApplyApplication;
use Pixelant\PxaIntelliplanJobs\Domain\Model\DTO\ShareJob;
use Pixelant\PxaIntelliplanJobs\Domain\Model\Job;
use TYPO3\CMS\Core\Mail\MailMessage;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Mvc\View\JsonView;
use TYPO3\CMS\Extbase\Property\PropertyMappingConfiguration;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;
use TYPO3\CMS\Fluid\View\StandaloneView;

/**
 * JobController
 */
class JobAjaxController extends ActionController
{
    /**
     * Reserved upload field name for CV
     */
    const CV_UPLOAD_FIELD_NAME = 'cv';

    /**
     * Default view
     *
     * @var string
     */
    protected $defaultViewObjectName = JsonView::class;

    /**
     * @var \Pixelant\PxaIntelliplanJobs\Domain\Repository\JobRepository
     * @inject
     */
    protected $jobRepository = null;

    /**
     * Hold uploaded in apply form files
     *
     * @var array
     */
    protected $uploadFiles = [];

    /**
     * Errors
     *
     * @var array
     */
    protected $responseErrors = [];

    /**
     * Remove upload temp files
     */
    public function __destruct()
    {
        foreach ($this->uploadFiles as $uploadFile) {
            GeneralUtility::unlink_tempfile($uploadFile['path']);
        }
    }

    /**
     * Initialize configuration
     */
    public function initializeShareAction()
    {
        // allow to create Demand from arguments
        $allowedProperties = GeneralUtility::trimExplode(
            ',',
            $this->settings['shareJob']['allowMappingProperties']
        );

        /** @var PropertyMappingConfiguration $mappingConfiguration */
        $mappingConfiguration = $this->arguments['shareJob']->getPropertyMappingConfiguration();
        $mappingConfiguration->allowProperties(...$allowedProperties);
    }

    /**
     * Share action
     *
     * @param ShareJob $shareJob
     * @param Job $job
     */
    public function shareAction(ShareJob $shareJob, Job $job)
    {
        $isValid = $this->validateShare($shareJob, $job);

        if ($isValid) {
            $mail = GeneralUtility::makeInstance(MailMessage::class);

            $mail
                ->setTo($shareJob->getReceiverEmail(), $shareJob->getReceiverName())
                ->setFrom($shareJob->getSenderEmail(), $shareJob->getSenderName())
                ->setBody($this->getShareJobMessage($shareJob, $job), 'text/html')
                ->setSubject($shareJob->getSubject())
                ->send();
        }

        $this->view->assign(
            'value',
            [
                'success' => $isValid,
                'errors' => $this->responseErrors,
                'successMessage' => $this->translate('fe.success_sent')
            ]
        );
    }

    /**
     * Apply for a job action
     *
     * @param Job $job
     * @param bool $requireCV
     */
    public function applyJobAction(Job $job, bool $requireCV = false)
    {
        $fields = $this->request->getArgument('applyJob');
        // Is this form with CV or not
        $validationType = $requireCV ? 'validationCV' : 'validationNoCV';
        $validationRules = is_array($this->settings['applyJob']['fields'][$validationType])
            ? $this->settings['applyJob']['fields'][$validationType]
            : [];

        $isValidFields = $this->validateApplyJobFields($fields, $validationRules);
        $isValidFiles = !$requireCV || $this->validateApplyJobFiles();
        $apiSuccess = false;

        if ($isValidFields && $isValidFiles) {
            if (!$requireCV) {
                // If CV is not required, create text file with information from radio buttons data
                $this->uploadFiles[self::CV_UPLOAD_FIELD_NAME] = $this->generateTextFileFromNotSupportedFields($fields);
            }

            $intelliplanApi = GeneralUtility::makeInstance(IntelliplanApi::class);
            $response = $intelliplanApi->applyForJob($job, $fields, $this->uploadFiles);

            // Assume we succeed if AplyForJob gone well
            $apiSuccess = $response['success'];

            if ($response['success'] === false) {
                $errorKey = $response['errorCode'] == 7
                    ? 'fe.error_api_apply_job_7'
                    : 'fe.error_api_apply_job_unknown';

                $this->addError('submit', $this->translate($errorKey));
            } else {
                /** @var ApplyApplication $applyJob */
                $applyJob = GeneralUtility::makeInstance(ApplyApplication::class);
                $applyJob->setAccountTicket($response['account_ticket']);
                $applyJob->setEmail($fields['email']);
                $applyJob->setPid($job->getPid());

                $job->addApplyApplication($applyJob);

                $this->jobRepository->update($job);

                // Now check what fields are supported by api Jobs/ApplyForJob
                // and all missing fields we need to set using SetPersonalInfromation api call
                $supportedFields = GeneralUtility::trimExplode(
                    ',',
                    $this->settings['applyJob']['fields']['apiSupportFields'],
                    true
                );
                $missingFields = array_diff(array_keys($fields), $supportedFields);

                // If any fields we need to add using SetPersonalInformation
                if (!empty($missingFields)) {
                    // First login using ticket
                    $response = $intelliplanApi->logonUsingTicket($applyJob->getAccountTicket());
                    if ($response['success'] === true) {
                        $sessionId = $response['data']['intelliplan_session_id'];

                        // If login was success set missing fields
                        // First get all fields, since API required all to be set when setting fields
                        // Then override values for missing fields
                        $response = $intelliplanApi->getPersonalInformation($sessionId);
                        if ($response['success'] === true) {
                            $setFields = $response['personal_information'];

                            foreach ($missingFields as $missingField) {
                                $setFields[$missingField] = $fields[$missingField];
                            }
                            $excludeSetFields = GeneralUtility::trimExplode(
                                ',',
                                $this->settings['applyJob']['fields']['excludeSetFields'],
                                true
                            );
                            foreach ($excludeSetFields as $excludeSetField) {
                                unset($setFields[$excludeSetField]);
                            }

                            $intelliplanApi->setPersonalInformation($sessionId, $setFields);
                        }
                    }
                }
            }
        }

        $this->view->assign(
            'value',
            [
                'success' => $isValidFields && $isValidFiles && $apiSuccess,
                'errors' => $this->responseErrors,
                'successMessage' => $this->translate('fe.success_apply_job')
            ]
        );
    }

    /**
     * Check if all required files are provided
     *
     * @return bool
     */
    protected function validateApplyJobFiles(): bool
    {
        $isValid = true;
        $requiredFiles = $this->settings['applyJob']['fields']['requiredFilesFields'] ?? '';

        $uploadedFilesErrors = $_FILES['tx_pxaintelliplanjobs_pi2']['error']['applyJobFiles'] ?? [];
        $uploadedFilesNames = $_FILES['tx_pxaintelliplanjobs_pi2']['name']['applyJobFiles'] ?? [];

        foreach ($uploadedFilesErrors as $file => $error) {
            if ($uploadedFilesErrors[$file] === UPLOAD_ERR_OK) {
                $tmpName = $_FILES['tx_pxaintelliplanjobs_pi2']['tmp_name']['applyJobFiles'][$file];

                $this->uploadFiles[$file] = [
                    'name' => basename($uploadedFilesNames[$file]),
                    'path' => $this->uploadToTempFile($tmpName)
                ];
            }
        }

        foreach (GeneralUtility::trimExplode(',', $requiredFiles, true) as $file) {
            if (!isset($uploadedFilesErrors[$file])
                || ($uploadedFilesErrors[$file]) !== UPLOAD_ERR_OK) {
                $isValid = false;
                $this->addError($file, $this->translate('fe.error_file_required'));
            }
        }

        foreach ($this->uploadFiles as $file => $uploadFile) {
            $mimeType = $this->getMimeType($uploadFile['path']);

            if (false === $this->isFileTypeAllowed($uploadFile['name'], $mimeType)) {
                $isValid = false;
                $this->addError(
                    $file,
                    $this->translate(
                        'fe.error_file_not_allowed',
                        [$this->settings['applyJob']['fields']['allowedFileTypes']]
                    )
                );
            }
        }

        return $isValid;
    }

    /**
     * Move upload file to temp file
     * Wrapper for tests
     *
     * @param string $uploadFilePath
     * @return string
     */
    protected function uploadToTempFile(string $uploadFilePath): string
    {
        return GeneralUtility::upload_to_tempfile($uploadFilePath);
    }

    /**
     * Return mime type
     * Wrapper for tests
     *
     * @param string $filePath
     * @return string
     */
    protected function getMimeType(string $filePath): string
    {
        return mime_content_type($filePath);
    }

    /**
     * Check if file extension is in list of allowed
     *
     * @param string $fileName
     * @param string $mimeType
     * @return bool
     */
    protected function isFileTypeAllowed(string $fileName, string $mimeType): bool
    {
        $allowedFileTypes = $this->settings['applyJob']['fields']['allowedFileTypes'];
        $allowedMimeTypes = $this->settings['applyJob']['fields']['allowedMimeTypes'];

        if (empty($allowedFileTypes) || empty($allowedMimeTypes)) {
            return false;
        }

        $extension = pathinfo($fileName, PATHINFO_EXTENSION);

        return ($allowedFileTypes === '*' || GeneralUtility::inList($allowedFileTypes, $extension))
            && ($allowedMimeTypes === '*' || GeneralUtility::inList($allowedMimeTypes, $mimeType));
    }

    /**
     * Generate email message
     *
     * @param ShareJob $shareJob
     * @param Job $job
     * @return string
     */
    protected function getShareJobMessage(ShareJob $shareJob, Job $job): string
    {
        $templatePathAndFilename = GeneralUtility::getFileAbsFileName($this->settings['shareJob']['template']);

        /** @var StandaloneView $standaloneView */
        $standaloneView = $this->objectManager->get(StandaloneView::class);
        $standaloneView->setTemplatePathAndFilename($templatePathAndFilename);
        $standaloneView->setFormat('html');

        $standaloneView
            ->assign('settings', $this->settings)
            ->assign('shareJob', $shareJob)
            ->assign('job', $job);

        return $standaloneView->render();
    }

    /**
     * Validate apply job fields
     *
     * @param array $fields
     * @param array $validationRules
     * @return bool
     */
    protected function validateApplyJobFields(array $fields, array $validationRules): bool
    {
        $isValid = true;
        $missingFields = array_diff(array_keys($validationRules), array_keys($fields));

        // Force empty values for missing fields
        foreach ($missingFields as $missingField) {
            $fields[$missingField] = '';
        }

        foreach ($fields as $field => $value) {
            if (isset($validationRules[$field])) {
                foreach (GeneralUtility::trimExplode(',', $validationRules[$field], true) as $rule) {
                    switch ($rule) {
                        case 'required':
                            if (empty($value)) {
                                $isValid = false;
                                $this->addError($field, $this->translate('fe.error_field_required'));
                            }
                            break;
                        case 'email':
                            if (!GeneralUtility::validEmail($value)) {
                                $isValid = false;
                                $this->addError($field, $this->translate('fe.error_valid_email'));
                            }
                            break;
                        case 'phone':
                            $pattern = '/^[0-9\-\(\)\/\+\s]*$/';
                            if (!preg_match($pattern, $value)) {
                                $isValid = false;
                                $this->addError($field, $this->translate('fe.error_valid_phone'));
                            }
                            break;
                        case 'agreeCheckbox':
                            if ((int)$value === 0) {
                                $isValid = false;
                                $this->addError($field, $this->translate('fe.error_acceptTerms'));
                            }
                            break;
                    }
                }
            }
        }

        return $isValid;
    }

    /**
     * Validate share job
     *
     * @param ShareJob $shareJob
     * @param Job $job
     * @return bool
     */
    protected function validateShare(ShareJob $shareJob, Job $job): bool
    {
        $valid = true;
        $senderEmail = $shareJob->getSenderEmail();

        if (empty($senderEmail)) {
            $senderEmail = $this->settings['shareJob']['defaultSenderEmail'];
            $shareJob->setSenderEmail($senderEmail);
        }
        if (empty($shareJob->getSubject())) {
            $shareJob->setSubject(
                $this->translate('mail.subject', [$shareJob->getSenderName(), $job->getTitle()])
            );
        }

        if (empty($shareJob->getReceiverName())) {
            $valid = false;
            $this->addError('receiverName', $this->translate('fe.error_field_required'));
        }
        if (empty($shareJob->getSenderName())) {
            $valid = false;
            $this->addError('senderName', $this->translate('fe.error_field_required'));
        }

        if (!GeneralUtility::validEmail($shareJob->getReceiverEmail())) {
            $valid = false;
            $this->addError('receiverEmail', $this->translate('fe.error_valid_email'));
        }

        return $valid;
    }

    /**
     * Add error
     *
     * @param string $field
     * @param string $message
     */
    protected function addError(string $field, string $message)
    {
        if (!isset($this->responseErrors[$field])) {
            $this->responseErrors[$field] = [];
        }

        $this->responseErrors[$field][] = $message;
    }

    /**
     * Generate text file from radio buttons and exclude it from all fields
     *
     * @param array &$fields
     * @return array File path and name
     */
    protected function generateTextFileFromNotSupportedFields(array &$fields): array
    {
        $radios = GeneralUtility::trimExplode(',', $this->settings['applyJob']['fields']['noCvRadios'] ?? '', true);
        $text = '';

        for ($i = 1; $i <= count($radios); $i++) {
            $radio = $radios[$i - 1];
            if (!isset($fields[$radio])) {
                continue;
            }
            $text .= sprintf(
                '%d. %s: "%s"' . "\r\n" . '%s: "%s"' . "\r\n\r\n",
                $i,
                $this->translate('fe.question'),
                $this->translate('fe.checkbox_' . $radio),
                $this->translate('fe.answer'),
                $fields[$radio]
            );

            unset($fields[$radio]);
        }

        return [
            'name' => 'cv_text.txt',
            'path' => $this->writeToTempFile($text)
        ];
    }

    /**
     * Create temp file and write to it text
     *
     * @param string $text
     * @return string
     */
    protected function writeToTempFile(string $text): string
    {
        $tempFile = GeneralUtility::tempnam('cv_');

        $fp = fopen($tempFile, 'w');
        fwrite($fp, $text);
        fclose($fp);

        return $tempFile;
    }

    /**
     * Translate label
     *
     * @param string $key
     * @param array $arguments
     * @return string
     */
    protected function translate(string $key, array $arguments = null): string
    {
        return LocalizationUtility::translate($key, 'PxaIntelliplanJobs', $arguments) ?? '';
    }
}
