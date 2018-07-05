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
     * Validation fields errors
     *
     * @var array
     */
    protected $errorFields = [];

    /**
     * Initialize configuration
     */
    public function initializeAjaxLazyListAction()
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
                'errors' => array_values($this->responseErrors),
                'errorFields' => $this->errorFields,
                'successMessage' => $this->translate('fe.success_sent')
            ]
        );
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
            $this->errorFields[] = 'receiverName';
            $this->responseErrors['allRequired'] = $this->translate('fe.error_all_fields_required');
        }
        if (empty($shareJob->getSenderName())) {
            $valid = false;
            $this->errorFields[] = 'senderName';
            $this->responseErrors['allRequired'] = $this->translate('fe.error_all_fields_required');
        }

        if (!GeneralUtility::validEmail($shareJob->getReceiverEmail())) {
            $valid = false;
            $this->errorFields[] = 'receiverEmail';
            $this->responseErrors['validEmail'] = $this->translate('fe.error_valid_email');
        }

        return $valid;
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
