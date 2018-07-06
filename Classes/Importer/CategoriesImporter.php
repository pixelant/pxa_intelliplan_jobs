<?php
declare(strict_types=1);

namespace Pixelant\PxaIntelliplanJobs\Importer;

use Pixelant\PxaIntelliplanJobs\Provider\IntelliplanDataProvider;
use TYPO3\CMS\Core\Utility\StringUtility;

/**
 * Class CategoriesImporter
 * @package Pixelant\PxaIntelliplanJobs\Importer
 */
class CategoriesImporter extends AbstractImporter
{
    /**
     * Import categories data
     *
     * @param array $importData
     * @param int $pid
     */
    public function import(array $importData, int $pid)
    {
        $data = [];
        $categories = is_array($importData['job_categories']) ? $importData['job_categories'] : [];

        foreach ($categories as $category) {
            $existingCategory = $this->categoryRepository->findByExternalImportId((int)$category['id']);

            if ($existingCategory !== null) {
                // Update title
                if ($existingCategory->getTitle() !== $category['name']) {
                    $data[$existingCategory->getUid()] = [
                        'title' => $category['name'],
                    ];
                }
            } else {
                // Create missing category
                $data[StringUtility::getUniqueId('NEW')] = [
                    'title' => $category['name'],
                    'pid' => $pid,
                    'tx_pxaintelliplanjobs_import_id' => $category['id']
                ];
            }
        }

        if (!empty($data)) {
            $data = [
                'sys_category' => $data
            ];

            $dataHandler = $this->getDataHandler();
            $dataHandler->start($data, []);
            $dataHandler->process_datamap();
        }
    }

    /**
     * Identify importer
     *
     * @return int
     */
    public function getImporterType(): int
    {
        return IntelliplanDataProvider::CATEGORIES_IMPORTER;
    }
}
