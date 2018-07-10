<?php
declare(strict_types=1);

namespace Pixelant\PxaIntelliplanJobs\Api;

use Pixelant\PxaIntelliplanJobs\Exception\ApiCallBadRequestException;
use Pixelant\PxaIntelliplanJobs\Utility\ConfigurationUtility;
use TYPO3\CMS\Core\Http\HttpRequest;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class IntelliplanApi
 * @package Pixelant\PxaIntelliplanJobs\Api
 */
class IntelliplanApi
{
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
     * Template for API calls
     *
     * @var string
     */
    protected $baseApiUrl = 'https://%s.app.intelliplan.eu/api/CandidatePortal_v1/%s?partner_code=%s';

    /**
     * Initialize
     */
    public function __construct()
    {
        $this->customerName = ConfigurationUtility::getCustomerName();
        $this->partnerId = ConfigurationUtility::getPartnerCode();
    }

    /**
     * Load XML string
     *
     * @return string
     * @throws \HTTP_Request2_Exception
     * @throws \HTTP_Request2_LogicException
     */
    public function getJobAdsListFeed(): string
    {
        $apiUrl = $this->getApiCallUrl('JobAdsListFeed');

        return $this->performGetRequest($apiUrl);
    }

    /**
     * All categories list as json
     *
     * @return string
     * @throws ApiCallBadRequestException
     * @throws \HTTP_Request2_Exception
     * @throws \HTTP_Request2_LogicException
     */
    public function getAllServiceCategories(): string
    {
        $apiUrl = $this->getApiCallUrl('Jobs/GetAllServiceCategories');

        return $this->performGetRequest($apiUrl);
    }

    /**
     * Get url for api call
     *
     * @param int $callType
     * @return string
     */
    protected function getApiCallUrl(string $callType): string
    {
        switch ($callType) {
            case 'Jobs/GetAllServiceCategories':
                return sprintf(
                    $this->baseApiUrl,
                    $this->customerName,
                    $callType,
                    $this->partnerId
                );
            case 'JobAdsListFeed':
                return 'https://cv-speedgroup-se.app.intelliplan.eu/JobAdGlobePages/Feed.aspx?pid=AA31EA47-FDA6-42F3-BD9F-E42186E5A960&version=2';
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
