<?php
declare(strict_types=1);

namespace Pixelant\PxaIntelliplanJobs\Importer;

use Pixelant\PxaIntelliplanJobs\Domain\Repository\CategoryRepository;
use Pixelant\PxaIntelliplanJobs\Domain\Repository\JobRepository;
use TYPO3\CMS\Core\DataHandling\DataHandler;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;

/**
 * Class AbstractImporter
 * @package Pixelant\PxaIntelliplanJobs\Importer
 */
abstract class AbstractImporter implements ImporterInterface
{
    /**
     * @var ObjectManager
     */
    protected $objectManager = null;

    /**
     * @var JobRepository
     */
    protected $jobRepository = null;

    /**
     * @var CategoryRepository
     */
    protected $categoryRepository = null;

    /**
     * Initialize
     */
    public function __construct()
    {
        $this->objectManager = GeneralUtility::makeInstance(ObjectManager::class);

        $this->jobRepository = $this->objectManager->get(JobRepository::class);
        $this->categoryRepository = $this->objectManager->get(CategoryRepository::class);
    }

    /**
     * @return DataHandler
     */
    protected function getDataHandler(): DataHandler
    {
        return GeneralUtility::makeInstance(DataHandler::class);
    }
}
