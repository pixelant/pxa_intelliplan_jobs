<?php
declare(strict_types=1);

namespace Pixelant\PxaIntelliplanJobs\Task;

use TYPO3\CMS\Core\Utility\GeneralUtility;
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
     * @return bool|void
     */
    public function execute()
    {
        $importService = GeneralUtility::makeInstance(IntelliplanImportService::class, $this->pid);
        $importService->import();
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
}
