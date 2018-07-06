<?php
declare(strict_types=1);

namespace Pixelant\PxaIntelliplanJobs\Provider;

use Pixelant\PxaIntelliplanJobs\Exception\ApiCallBadRequestException;
use Pixelant\PxaIntelliplanJobs\Exception\ImporterNotSupportedException;
use Pixelant\PxaIntelliplanJobs\Utility\ConfigurationUtility;
use TYPO3\CMS\Core\Http\HttpRequest;
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
     * Api url template
     *
     * @var string
     */
    protected static $apiUrl = 'https://%s.app.intelliplan.eu/api/CandidatePortal_v1/%s';

    /**
     * Customer name
     *
     * @var string
     */
    protected $customerName = '';

    /**
     * Partner code
     *
     * @var string
     */
    protected $partnerId = '';

    /**
     * Initialize
     */
    public function __construct()
    {
        $this->customerName = ConfigurationUtility::getCustomerName();
        $this->partnerId = ConfigurationUtility::getPartnerCode();
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
                return$this->getJobsData();
            default:
                throw new ImporterNotSupportedException('Importer with type "' . $type . '" not supported.', 1530868260960);
        }
    }

    /**
     * Fetch all public jobs
     * @return array
     */
    public function getJobsData(): array
    {
        $apiUrl = $this->getApiCallUrl(self::JOBS_IMPORTER);
        $response = $this->performGetRequest($apiUrl);


    }

    /**
     * Get array of all job categories
     *
     * @return array
     */
    public function getAllCategories(): array
    {
        $apiUrl = $this->getApiCallUrl(self::CATEGORIES_IMPORTER);
        $response = $this->performGetRequest($apiUrl);

        return json_decode($response, true) ?: [];
    }

    /**
     * Get url for api call
     *
     * @param int $callType
     * @return string
     */
    protected function getApiCallUrl(int $callType): string
    {
        switch ($callType) {
            case self::CATEGORIES_IMPORTER:
                return sprintf(
                    self::$apiUrl,
                    $this->customerName,
                    'JobCategories/GetAllJobCategories?partner_code=' . $this->partnerId
                );
            case self::JOBS_IMPORTER:
                return'https://cv-speedgroup-se.app.intelliplan.eu/JobAdGlobePages/Feed.aspx?pid=AA31EA47-FDA6-42F3-BD9F-E42186E5A960&version=2';
            default:
                throw new \UnexpectedValueException('Api call with type "' . $callType . '" not supported.', 1530863121487);
        }
    }

    /**
     * Perform request
     *
     * @param string $url
     * @return string
     * @throws ApiCallBadRequestException
     * @throws \HTTP_Request2_Exception
     * @throws \HTTP_Request2_LogicException
     */
    protected function performGetRequest(string $url): string
    {
        /** @var HttpRequest $httpRequest */
        $httpRequest = GeneralUtility::makeInstance(
            HttpRequest::class,
            $url,
            'GET'
        );
        $httpRequest->setHeader(['Accept' => 'application/json']);

        /** @var \HTTP_Request2_Response $response */
        $response = $httpRequest->send();

        if ($response->getStatus() === 200) {
            return $response->getBody();
        }

        throw new ApiCallBadRequestException('Failed API call "' . $url . '" with code - ' . $response->getStatus());
    }
}
