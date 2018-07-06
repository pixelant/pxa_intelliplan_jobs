<?php
declare(strict_types=1);

namespace Pixelant\PxaIntelliplanJobs\Importer;

/**
 * Class CategoriesImporter
 * @package Pixelant\PxaIntelliplanJobs\Importer
 */
class CategoriesImporter extends AbstractImporter
{
    /**
     * Import categories data
     *
     * @param array $data
     * @param int $pid
     */
    public function import(array $data, int $pid)
    {
        $data = [];

        \TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($data,'Debug',16);
    }
}
