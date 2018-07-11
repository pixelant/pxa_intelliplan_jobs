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
     * Default view
     *
     * @var string
     */
    protected $defaultViewObjectName = JsonView::class;

    /**
     * Errors
     *
     * @var array
     */
    protected $responseErrors = [];

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
        $isValid = $this->validateShare($shareJob);

        if ($isValid) {
            $mail = GeneralUtility::makeInstance(MailMessage::class);

            $mail
                ->setFormat('html')
                ->setTo($shareJob->getReceiverEmail(), $shareJob->getReceiverName())
                ->setFrom($shareJob->getSenderEmail(), $shareJob->getSenderName())
                ->setBody($this->getShareJobMessage($shareJob, $job))
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
        $isValidFields = $this->validateApplyJobFields($fields);
        $isValidFiles = !$requireCV || $this->validateApplyJobFiles();

        if ($isValidFields && $isValidFiles) {

        }

        $this->view->assign(
            'value',
            [
                'success' => $isValidFields && $isValidFiles,
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
        $requiredFiles = $validationRules = is_array($this->settings['applyJob']['fields']['requiredFilesFields'])
            ? $this->settings['applyJob']['fields']['requiredFilesFields']
            : '';

        foreach (GeneralUtility::trimExplode(',', $requiredFiles, true) as $file) {
            if (!isset($_FILES['tx_pxaintelliplanjobs_pi2']['error']['files'][$file])
                || ($_FILES['tx_pxaintelliplanjobs_pi2']['error']['files'][$file]) !== 0) {
                $isValid = false;
                $this->addError($file, $this->translate('fe.error_file_required'));
            }
        }

        return $isValid;
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
     * @return bool
     */
    protected function validateApplyJobFields(array $fields): bool
    {
        $isValid = true;
        $validationRules = is_array($this->settings['applyJob']['fields']['validation'])
            ? $this->settings['applyJob']['fields']['validation']
            : [];
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
                        case 'agreeCheckbox':
                            if ((int)$value !== 1) {
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
     * @return bool
     */
    protected function validateShare(ShareJob $shareJob): bool
    {
        $valid = true;
        $senderEmail = $shareJob->getSenderEmail();

        if (empty($senderEmail)) {
            $senderEmail = $this->settings['shareJob']['defaultSenderEmail'];
            $shareJob->setSenderEmail($senderEmail);
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
        if (!is_array($this->responseErrors[$field])) {
            $this->responseErrors[$field] = [];
        }

        $this->responseErrors[$field][] = $message;
    }

    /**
     * Translate label
     *
     * @param string $key
     * @return string
     */
    protected function translate(string $key): string
    {
        return LocalizationUtility::translate($key, 'PxaIntelliplanJobs') ?? '';
    }
}
