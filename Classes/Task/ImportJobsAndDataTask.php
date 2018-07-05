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
     * Importer
     *
     * @return bool|void
     */
    public function execute()
    {
        $importService = GeneralUtility::makeInstance(IntelliplanJobsImportService::class);
        $importService->import();
    }
}
