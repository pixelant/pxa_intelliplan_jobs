<?php
declare(strict_types=1);

namespace Pixelant\PxaIntelliplanJobs\Domain\Repository;

use TYPO3\CMS\Extbase\Domain\Model\Category;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\Repository;

/**
 * Class JobRepository
 * @package Pixelant\PxaIntelliplanJobs\Domain\Repository
 */
class JobRepository extends Repository
{
    /**
     * @var array
     */
    protected $defaultOrderings = [
        'crdate' => QueryInterface::ORDER_DESCENDING
    ];

    /**
     * Count job offers by category
     *
     * @param Category $category
     * @return int
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\InvalidQueryException
     */
    public function countByCategory(Category $category): int
    {
        $query = $this->createQuery();

        $query->matching($query->contains('categories', $category));

        return $query->count();
    }
}
