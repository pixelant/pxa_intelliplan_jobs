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
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\MathUtility;
use TYPO3\CMS\Extbase\Mvc\Exception\NoSuchArgumentException;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

/**
 * JobController
 */
class JobController extends AbstractAction
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
        if ($this->request->hasArgument('job')) {
            // If job arguments was set, this means that realurl
            // didn't found alias, so get var was set as string and
            // and Typoscirpt condition didn't match. Need to show 404 page
            // We can not enable 404 page in realrul, because we need to try to find
            // job ad by inteliplan id in show action.
            $this->getTSFE()->pageNotFoundAndExit(
                'Job was not found with alias "' . $this->request->getArgument('job') . '"'
            );
        }
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
    public function showAction(Job $job = null)
    {
        list($lastPathSegment) = array_reverse(
            GeneralUtility::trimExplode(
                '/',
                GeneralUtility::getIndpEnv('TYPO3_SITE_SCRIPT'),
                true
            )
        );
        // If job was not found
        // or last path segment was numeric, that means that external job ad ID was provided,
        // but job with such UID also exist
        // this depends on realurl configuration.
        if ($job === null
            || (MathUtility::canBeInterpretedAsInteger($lastPathSegment) && intval($lastPathSegment) === $job->getUid())
        ) {
            $this->findJobByApiIdAndRedirectOrNotFound($lastPathSegment);
        }


        $this->view->assign(
            self::NO_CV_QUESTION_PRESET,
            $this->getQuestionsPreset(self::NO_CV_QUESTION_PRESET, $job)
        );
        $this->view->assign(
            self::CV_QUESTION_PRESET,
            $this->getQuestionsPreset(self::CV_QUESTION_PRESET, $job)
        );

        $this->view->assign('additionalQuestionsPrefix', self::ADDITIONAL_QUESTIONS_PREFIX);
        $this->view->assign('shareUrl', urlencode($this->getShareUrl($job)));
        $this->view->assign('job', $job);
    }

    /**
     * Try to find object by inteliplan ID and redirect to correct url if found
     */
    protected function findJobByApiIdAndRedirectOrNotFound($jobAdId)
    {
        // Try to find job by external inteliplan uid
        if (MathUtility::canBeInterpretedAsInteger($jobAdId)) {
            $job = $this->jobRepository->findById((int)$jobAdId);

            if ($job !== null) {
                $uriBuilder = $this->controllerContext->getUriBuilder()->reset();
                $url = $uriBuilder
                    ->setTargetPageUid($this->getTSFE()->id)
                    ->uriFor('show', ['job' => $job]);

                if (!empty($url)) {
                    $this->redirectToUri($url, 0, 301);
                }
            }
        }

        $this->getTSFE()->pageNotFoundAndExit('Job ad with uid "' . $jobAdId . '" not found');
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

        $targetPage = $this->settings['singleViewPid'] ?: $this->getTSFE()->id;

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

    /**
     * @return TypoScriptFrontendController
     */
    protected function getTSFE()
    {
        return $GLOBALS['TSFE'];
    }
}
