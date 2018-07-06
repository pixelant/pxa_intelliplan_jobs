<?php
declare(strict_types=1);

namespace Pixelant\PxaIntelliplanJobs\Domain\Model;

/**
 * Class Category
 * @package Pixelant\PxaIntelliplanJobs\Domain\Model
 */
class Category extends \TYPO3\CMS\Extbase\Domain\Model\Category
{
    /**
     * @var int
     */
    protected $importId = 0;

    /**
     * @var \Pixelant\PxaIntelliplanJobs\Domain\Model\Category
     */
    protected $parent = null;

    /**
     * @return int
     */
    public function getImportId(): int
    {
        return $this->importId;
    }

    /**
     * @param int $importId
     */
    public function setImportId(int $importId)
    {
        $this->importId = $importId;
    }
}
