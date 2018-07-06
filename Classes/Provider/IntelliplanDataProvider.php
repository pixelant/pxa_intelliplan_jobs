<?php
declare(strict_types=1);

namespace Pixelant\PxaIntelliplanJobs\Provider;

use Pixelant\PxaIntelliplanJobs\Exception\ApiCallBadRequestException;
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

    const API_CALL_GET_CATEGORIES = 1;

    /**
     * Api url template
     *
     * @var string
     */
    protected static $apiUrl = 'https://%s.app.intelliplan.eu/CandidatePortal_v1/%s';

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
     * Get array of all job categories
     *
     * @return array
     */
    public function getAllCategories(): array
    {
        $apiUrl = $this->getApiCallUrl(self::API_CALL_GET_CATEGORIES);
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
            case self::API_CALL_GET_CATEGORIES:
                return sprintf(
                    self::$apiUrl,
                    $this->customerName,
                    'JobCategories/GetAllJobCategories?partner_code=' . $this->partnerId
                );
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
    protected function performGetRequest(string $url)
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
