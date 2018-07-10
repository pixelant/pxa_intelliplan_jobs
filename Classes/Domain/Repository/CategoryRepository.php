<?php
declare(strict_types=1);

namespace Pixelant\PxaIntelliplanJobs\Domain\Repository;
use Pixelant\PxaIntelliplanJobs\Domain\Model\Category;

/**
 * Class CategoryRepository
 * @package Pixelant\PxaIntelliplanJobs\Domain\Repository
 */
class CategoryRepository extends \TYPO3\CMS\Extbase\Domain\Repository\CategoryRepository
{
    /**
     * Find intelliplan category
     *
     * @param $id
     * @return Category|null
     */
    public function findByExternalImportId($id)
    {
        $query = $this->createQuery();

        $query->getQuerySettings()
            ->setRespectSysLanguage(false)
            ->setRespectStoragePage(false);

        $query->matching(
            $query->equals('importId', $id)
        );

        return $query->execute()->getFirst();
    }
}
