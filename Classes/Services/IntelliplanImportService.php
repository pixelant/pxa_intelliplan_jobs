<?php
declare(strict_types=1);

namespace Pixelant\PxaIntelliplanJobs\Services;

use Pixelant\PxaIntelliplanJobs\Importer\CategoriesImporter;
use Pixelant\PxaIntelliplanJobs\Importer\ImporterInterface;
use Pixelant\PxaIntelliplanJobs\Importer\JobDataImporter;
use Pixelant\PxaIntelliplanJobs\Provider\IntelliplanDataProvider;
use TYPO3\CMS\Core\DataHandling\DataHandler;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\MathUtility;

class IntelliplanImportService
{
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
        CategoriesImporter::class,
        JobDataImporter::class
    ];

    /**
     * Initialize
     */
    public function __construct()
    {
        $this->dataProvider = GeneralUtility::makeInstance(IntelliplanDataProvider::class);
    }

    /**
     * Run import process
     *
     * @param int $pid
     * @param string $clearCache
     * @throws \Pixelant\PxaIntelliplanJobs\Exception\ImporterNotSupportedException
     */
    public function run(int $pid, string $clearCache)
    {
        $requireClearCache = false;

        foreach ($this->importers as $importer) {
            /** @var ImporterInterface $importerInstance */
            $importerInstance = GeneralUtility::makeInstance($importer);

            $data = $this->dataProvider->getDataByImporterType($importerInstance->getImporterType());
            $importerInstance->import($data, $pid);

            // If one of the importers require a clear cache
            if (!$requireClearCache && $importerInstance->isRequireClearCache()) {
                $requireClearCache = true;
            }
        }

        if ($requireClearCache && !empty($clearCache)) {
            $clearCachesCmd = $this->getClearCacheCommands($clearCache);
            $tce = $this->getDataHandler();

            foreach ($clearCachesCmd as $clearCacheCmd) {
                $tce->clear_cacheCmd($clearCacheCmd);
            }
        }
    }

    /**
     * Clear cache for array of clear cache commands
     *
     * @param string $clearCache
     * @return array
     */
    protected function getClearCacheCommands(string $clearCache): array
    {
        $clearCacheValues = GeneralUtility::trimExplode(',', $clearCache, true);
        $clearCachesCmd = [];

        foreach ($clearCacheValues as $clearCacheValue) {
            $clearCachesCmd[] = MathUtility::canBeInterpretedAsInteger($clearCacheValue)
                ? $clearCacheValue
                : 'cacheTag:' . $clearCacheValue;
        }

        return $clearCachesCmd;
    }

    /**
     * Wrapper
     *
     * @return DataHandler
     */
    protected function getDataHandler(): DataHandler
    {
        return GeneralUtility::makeInstance(DataHandler::class);
    }
}
