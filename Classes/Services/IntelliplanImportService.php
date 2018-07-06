<?php
declare(strict_types=1);

namespace Pixelant\PxaIntelliplanJobs\Services;

use Pixelant\PxaIntelliplanJobs\Importer\CategoriesImporter;
use Pixelant\PxaIntelliplanJobs\Importer\ImporterInterface;
use Pixelant\PxaIntelliplanJobs\Importer\JobDataImporter;
use Pixelant\PxaIntelliplanJobs\Provider\IntelliplanDataProvider;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class IntelliplanImportService
{
    /**
     * Storage
     *
     * @var int
     */
    protected $pid = 0;

    /**
     * @var IntelliplanDataProvider
     */
    protected $dataProvider = null;

    /**
     * List of importers
     * !!! Order is important
     *
     * @var array
     */
    protected $importers = [
        //CategoriesImporter::class
        JobDataImporter::class
    ];

    /**
     * Initialize
     *
     * @param int $pid
     */
    public function __construct(int $pid)
    {
        $this->pid = $pid;

        $this->dataProvider = GeneralUtility::makeInstance(IntelliplanDataProvider::class);
    }

    /**
     * Run import process
     */
    public function run()
    {
        foreach ($this->importers as $importer) {
            /** @var ImporterInterface $importerInstance */
            $importerInstance = GeneralUtility::makeInstance($importer);

            $data = $this->dataProvider->getDataByImporterType($importerInstance->getImporterType());
            $importerInstance->import($data, $this->pid);
        }
    }
}
