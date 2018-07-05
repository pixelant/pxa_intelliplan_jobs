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
use TYPO3\CMS\Extbase\Domain\Model\Category;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

/**
 * JobController
 */
class JobController extends ActionController
{
    /**
     * @var \Pixelant\PxaIntelliplanJobs\Domain\Repository\JobRepository
     * @inject
     */
    protected $jobRepository = null;

    /**
     * @var \TYPO3\CMS\Extbase\Domain\Repository\CategoryRepository
     * @inject
     */
    protected $categoryRepository = null;

    /**
     * @var TypoScriptFrontendController
     */
    protected $tsfe = null;

    /**
     * Initialize on every action
     */
    public function initializeAction()
    {
        $this->tsfe = $GLOBALS['TSFE'];
    }

    /**
     * action list
     *
     * @return void
     */
    public function listAction()
    {
        $jobs = $this->jobRepository->findAll();
        $rootCategory = $this->categoryRepository->findByUid(
            (int)$this->settings['root_category']
        );

        if ($rootCategory !== null) {
            /** @var QueryResultInterface $subCategories */
            $subCategories = $this->categoryRepository->findByParent($rootCategory);
            $this->view->assign('subCategories', $this->filterOutHiddenCategories($subCategories->toArray()));
        }

        $this->view
            ->assign('jobs', $jobs)
            ->assign('cities', $this->collectJobCities($jobs))
            ->assign('rootCategory', $rootCategory);
    }

    /**
     * action show
     *
     * @param \Pixelant\PxaIntelliplanJobs\Domain\Model\Job $job
     * @return void
     */
    public function showAction(\Pixelant\PxaIntelliplanJobs\Domain\Model\Job $job)
    {
        $this->view->assign('shareUrl', urlencode($this->getShareUrl($job)));
        $this->view->assign('job', $job);
    }

    /**
     * Remove categories that are not visible
     *
     * @param array $categories
     * @return array
     */
    protected function filterOutHiddenCategories(array $categories): array
    {
        if ($this->isOptionEnabled('hideCategoriesWithoutJobs')) {
            $resultCategories = [];

            /** @var Category $category */
            foreach ($categories as $category) {
                if ($this->jobRepository->countByCategory($category) > 0) {
                    $resultCategories[] = $category;
                }
            }

            return $resultCategories;
        }

        return $categories;
    }


    protected function collectJobCities(QueryResultInterface $jobs): array
    {
        $cities = [];

        /** @var Job $job */
        foreach ($jobs as $job) {
            if (!empty($job->getCity())) {
                $cityProcessed = $job->getCityProcessed();

                if (!array_key_exists($cityProcessed, $cities)) {
                    $cities[$cityProcessed] = $job->getCity();
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

        return $uriBuilder
            ->setTargetPageUid($this->settings['singleViewPid'])
            ->setCreateAbsoluteUri(true)
            ->uriFor('show', ['job' => $job]);
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
