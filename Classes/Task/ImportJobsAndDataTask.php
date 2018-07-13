<?php
declare(strict_types=1);

namespace Pixelant\PxaIntelliplanJobs\Task;

use Pixelant\PxaIntelliplanJobs\Services\IntelliplanImportService;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Lang\LanguageService;
use TYPO3\CMS\Scheduler\Task\AbstractTask;

/**
 * Class ImportJobsAndDataTask
 * @package Pixelant\PxaIntelliplanJobs\Task
 */
class ImportJobsAndDataTask extends AbstractTask
{
    /**
     * Storage for records
     *
     * @var int
     */
    protected $pid = 0;

    /**
     * Importer
     *
     * @return bool
     */
    public function execute(): bool
    {
        $importService = GeneralUtility::makeInstance(IntelliplanImportService::class, $this->pid);
        $importService->run();

        return true;
    }

    /**
     * @return int
     */
    public function getPid(): int
    {
        return $this->pid;
    }

    /**
     * @param int $pid
     */
    public function setPid(int $pid)
    {
        $this->pid = $pid;
    }

    public function getAdditionalInformation()
    {
        /** @var LanguageService $lang */
        $lang = $GLOBALS['LANG'];

        $page = BackendUtility::getRecord('pages', $this->getPid(), 'title');

        return sprintf(
            '%s: %s[%d]',
            $lang->sL(
                'LLL:EXT:pxa_intelliplan_jobs/Resources/Private/Language/locallang_db.xlf:importJobsAndDataTask.pid'
            ),
            $page['title'],
            $this->getPid()
        );
    }
}
