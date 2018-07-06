<?php
declare(strict_types=1);

namespace Pixelant\PxaIntelliplanJobs\Importer;
use Pixelant\PxaIntelliplanJobs\Provider\IntelliplanDataProvider;

/**
 * Class JobDataImporter
 * @package Pixelant\PxaIntelliplanJobs\Importer
 */
class JobDataImporter extends AbstractImporter
{
    /**
     * Import jobs data
     *
     * @param array $data
     * @param int $pid
     */
    public function import(array $data, int $pid)
    {
        // TODO: Implement import() method.
    }

    /**
     * Type of job importer
     *
     * @return int
     */
    public function getImporterType(): int
    {
        return IntelliplanDataProvider::JOBS_IMPORTER;
    }
}
