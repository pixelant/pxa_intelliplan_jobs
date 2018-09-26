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

use Pixelant\PxaIntelliplanJobs\Domain\Model\Job;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

/**
 * JobController
 */
class JobController extends ActionController
{
    /**
     * Prefix for form name where additonal questions are rendered
     */
    const ADDITIONAL_QUESTIONS_PREFIX = 'additional_question_';

    /**
     * @var \Pixelant\PxaIntelliplanJobs\Domain\Repository\JobRepository
     * @inject
     */
    protected $jobRepository = null;

    /**
     * action list
     *
     * @return void
     */
    public function listAction()
    {
        $jobs = $this->jobRepository->findAllWithOrder($this->settings['sortOrder']);

        if ($jobs->count() > 0) {
            $subCategories = [];

            /** @var Job $job */
            foreach ($jobs as $job) {
                $category = $job->getCategoryTypo3();
                if ($category !== null && !isset($subCategories[$category->getUid()])) {
                    $subCategories[$category->getUid()] = $category;
                }
            }

            $this->view->assign('subCategories', $this->sortArrayAlphabetically($subCategories));
        }

        $this->view
            ->assign('jobs', $jobs)
            ->assign('cities', $this->sortArrayAlphabetically($this->collectJobCities($jobs)));
    }

    /**
     * action show
     *
     * @param Job $job
     * @return void
     */
    public function showAction(Job $job)
    {
        if (!empty($job->getJobOccupationId())
            && isset($this->settings['applyJob']['fields']['noCvQuestionsPreset'][$job->getJobOccupationId()])
        ) {
            $this->view->assign(
                'noCvQuestionsPreset',
                $this->settings['applyJob']['fields']['noCvQuestionsPreset'][$job->getJobOccupationId()]
            );
            $this->view->assign('additionalQuestionsPrefix', self::ADDITIONAL_QUESTIONS_PREFIX);
        }
        $this->view->assign('shareUrl', urlencode($this->getShareUrl($job)));
        $this->view->assign('job', $job);
    }

    /**
     * Create array of locations
     *
     * @param QueryResultInterface $jobs
     * @return array
     */
    protected function collectJobCities(QueryResultInterface $jobs): array
    {
        $cities = [];

        /** @var Job $job */
        foreach ($jobs as $job) {
            if (!empty($job->getMunicipality()) && !empty($job->getMunicipalityId())) {
                if (!array_key_exists($job->getMunicipalityId(), $cities)) {
                    $cities[$job->getMunicipalityId()] = $job->getMunicipality();
                }
            }
        }

        return $cities;
    }

    /**
     * Generate url for share
     *
     * @return string
     */
    protected function getShareUrl(Job $job): string
    {
        $uriBuilder = $this->getControllerContext()->getUriBuilder()->reset();
        /** @var TypoScriptFrontendController $tsfe */
        $tsfe = $GLOBALS['TSFE'];

        $targetPage = $this->settings['singleViewPid'] ?: $tsfe->id;

        return $uriBuilder
            ->setTargetPageUid($targetPage)
            ->setCreateAbsoluteUri(true)
            ->uriFor('show', ['job' => $job]);
    }

    /**
     * Sort array in alphabetical order
     *
     * @param array $array
     * @return array
     */
    protected function sortArrayAlphabetically(array $array): array
    {
        $replacements = [
            'ä' => 'a',
            'å' => 'a',
            'ö' => 'o',
            'Å' => 'a',
            'Ä' => 'a',
            'Ö' => 'o',
            'ø' => 'o',
            'Ø' => 'o'
        ];
        $search = array_keys($replacements);

        $arrayNoSpecialChars = [];
        foreach ($array as $key => $item) {
            $arrayNoSpecialChars[$key] = str_replace($search, $replacements, $item);
        }

        asort($arrayNoSpecialChars);

        return array_replace($arrayNoSpecialChars, $array);
    }

    /**
     * Read boolean option from settings
     *
     * @param string $option
     * @return bool
     */
    protected function isOptionEnabled(string $option): bool
    {
        return (int)$this->settings[$option] === 1;
    }
}
