<?php
declare(strict_types=1);

namespace Pixelant\PxaIntelliplanJobs\Controller;

use Pixelant\PxaIntelliplanJobs\Domain\Model\Job;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

/**
 * Class AbstractAction
 * @package Pixelant\PxaIntelliplanJobs\Controller
 */
abstract class AbstractAction extends ActionController
{
    /**
     * Questions presset keys
     */
    const NO_CV_QUESTION_PRESET = 'noCvQuestionsPreset';
    const CV_QUESTION_PRESET = 'cvQuestionsPreset';

    /**
     * Get questions preset
     *
     * @param string $key
     * @param Job $job
     * @return array
     */
    protected function getQuestionsPreset(string $key, Job $job): array
    {
        return $this->settings['applyJob']['fields'][$key][$job->getJobOccupationId()]
            ?? $this->settings['applyJob']['fields'][$key]['all']
            ?? [];
    }

    /**
     * Check if recaptcha credentials were given
     *
     * @return bool
     */
    protected function isRecaptchaCredentialsSet(): bool
    {
        return !empty($this->settings['recaptcha']['siteKey']) && !empty($this->settings['recaptcha']['secretKey']);
    }
}
