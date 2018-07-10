<?php
declare(strict_types=1);

namespace Pixelant\PxaIntelliplanJobs\Domain\Repository;

use TYPO3\CMS\Extbase\Domain\Model\Category;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
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
        'pubDate' => QueryInterface::ORDER_DESCENDING
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

    /**
     * Find job by ID
     *
     * @param int $id
     * @return object
     */
    public function findById(int $id)
    {
        $query = $this->createQuery();

        $query->getQuerySettings()
            ->setRespectSysLanguage(false)
            ->setRespectStoragePage(false);

        $query->matching(
            $query->equals('id', $id)
        );

        return $query->execute()->getFirst();
    }

    /**
     * Find all with custom order
     *
     * @param string $direction
     * @return QueryResultInterface
     */
    public function findAllWithOrder(string $direction): QueryResultInterface
    {
        $query = $this->createQuery();

        $orderDirection = $direction === 'asc' ? QueryInterface::ORDER_ASCENDING : QueryInterface::ORDER_DESCENDING;

        $query->setOrderings(['pubDate' => $orderDirection]);

        return $query->execute();
    }
}
