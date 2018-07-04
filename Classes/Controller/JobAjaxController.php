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
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Mvc\View\JsonView;
use TYPO3\CMS\Extbase\Property\PropertyMappingConfiguration;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;

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
     */
    public function shareAction(ShareJob $shareJob)
    {
        $response = [];


    }

    protected function validateShare(ShareJob $shareJob): bool
    {
        $senderEmail = $shareJob->getSenderEmail();

        if (empty($senderEmail)) {
            $senderEmail = $this->settings['shareJob']['defaultSenderEmail'];
            $shareJob->setSenderEmail($senderEmail);
        }

        if (empty())
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
