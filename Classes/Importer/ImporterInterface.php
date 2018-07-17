<?php
namespace Pixelant\PxaIntelliplanJobs\Importer;

/**
 * Interface ImporterInterface
 * @package Pixelant\PxaIntelliplanJobs\Importer
 */
interface ImporterInterface
{
    /**
     * Import data to TYPO3 from Itelliplan
     *
     * @param array $data Data from itelliplan
     * @param int $pid Storage
     * @return void
     */
    public function import(array $data, int $pid);

    /**
     * Identify importer type
     *
     * @return int
     */
    public function getImporterType(): int;

    /**
     * Check if importer require a clear cache
     *
     * @return bool
     */
    public function isRequireClearCache(): bool;
}
