<?php
declare(strict_types=1);

namespace Pixelant\PxaIntelliplanJobs\Provider;

use Pixelant\PxaIntelliplanJobs\Api\IntelliplanApi;
use Pixelant\PxaIntelliplanJobs\Exception\ImporterNotSupportedException;
use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class IntelliplanDataProvider
 * @package Pixelant\PxaIntelliplanJobs\Provider
 */
class IntelliplanDataProvider implements SingletonInterface
{
    /**
     * Importer type
     */
    const CATEGORIES_IMPORTER = 1;

    /**
     * Jobs importer
     */
    const JOBS_IMPORTER = 2;

    /**
     * @var IntelliplanApi
     */
    protected $intelliplanApi = null;

    /**
     * Initialize
     */
    public function __construct()
    {
        $this->intelliplanApi = GeneralUtility::makeInstance(IntelliplanApi::class);
    }

    /**
     * Generate data for importers
     *
     * @param int $type
     * @return array
     */
    public function getDataByImporterType(int $type): array
    {
        switch ($type) {
            case self::CATEGORIES_IMPORTER:
                return $this->getAllCategories();
            case self::JOBS_IMPORTER:
                return $this->getJobsData();
            default:
                throw new ImporterNotSupportedException(
                    'Importer with type "' . $type . '" not supported.',
                    1530868260960
                );
        }
    }

    /**
     * Fetch all public jobs
     *
     * @return array
     */
    protected function getJobsData(): array
    {
        $response = $this->intelliplanApi->getJobAdsListFeed();

        $xml = simplexml_load_string($response);
        $ns = $xml->getNamespaces(true);

        $data = $this->convertJobXmlToArray($xml->channel->item, $ns['intelliplan']);

        return $data;
    }

    /**
     * Get array of all job categories
     *
     * @return array
     */
    protected function getAllCategories(): array
    {
        $response = json_decode($this->intelliplanApi->getAllServiceCategories(), true);

        return is_array($response['service_categories']) ? $response['service_categories'] : [];
    }

    /**
     * Convert jobs feed to array
     *
     * @param \SimpleXMLElement $simpleXMLElement
     * @param string $ns
     * @return array
     */
    protected function convertJobXmlToArray(\SimpleXMLElement $simpleXMLElement, string $ns): array
    {
        $feedItems = [];

        foreach ($simpleXMLElement as $item) {
            $itemRow = [];
            // Go through simple fields
            foreach ($item as $itemFieldName => $itemFieldValue) {
                $itemRow[$itemFieldName] = (string)$itemFieldValue;
            }

            // Go through namespace fields
            foreach ($item->children($ns) as $itemFieldName => $itemFieldValue) {
                if ($itemFieldName === 'descriptions') {
                    $descriptions = [];
                    foreach ($itemFieldValue as $description) {
                        $descriptions[] = [
                            'descriptionheader' => (string)$description->descriptionheader,
                            'descriptiontext' => (string)$description->descriptiontext
                        ];
                    }
                    $itemRow[$itemFieldName] = $descriptions;
                } else {
                    $itemRow[$itemFieldName] = (string)$itemFieldValue;
                }
            }

            $feedItems[] = $itemRow;
        }

        return $feedItems;
    }
}
