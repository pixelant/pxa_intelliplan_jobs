<?php
declare(strict_types=1);

namespace Pixelant\PxaIntelliplanJobs\Api;

use Pixelant\PxaIntelliplanJobs\Domain\Model\Job;
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
     * Login using ticket
     *
     * @param string $ticket
     * @return array
     * @throws ApiCallBadRequestException
     * @throws \HTTP_Request2_Exception
     * @throws \HTTP_Request2_LogicException
     */
    public function logonUsingTicket(string $ticket): array
    {
        $apiUrl = $this->getApiCallUrl('CandidatesUserAccounts/LogonUsingTicket');

        $response = $this->performRequest($apiUrl, HttpRequest::METHOD_POST, ['account_ticket' => $ticket]);

        return json_decode($response, true);
    }

    /**
     * Get candidate information
     *
     * @param string $intelliplanSessionId
     * @return array
     */
    public function getPersonalInformation(string $intelliplanSessionId): array
    {
        $apiUrl = $this->apendSessionIdToUrl(
            $this->getApiCallUrl('Candidates/GetPersonalInformation'),
            $intelliplanSessionId
        );

        $response = $this->performRequest($apiUrl);

        return json_decode($response, true);
    }

    /**
     * Set candidate information
     *
     * @param string $intelliplanSessionId
     * @param array $fields
     * @return array
     */
    public function setPersonalInformation(string $intelliplanSessionId, array $fields): array
    {
        $apiUrl = $this->apendSessionIdToUrl(
            $this->getApiCallUrl('Candidates/SetPersonalInformation'),
            $intelliplanSessionId
        );

        $response = $this->performRequest($apiUrl, HttpRequest::METHOD_POST, $fields);

        return json_decode($response, true);
    }

    /**
     * Apply for a job api call
     *
     * @param Job $job
     * @param array $fields
     * @param array $files
     * @return array
     */
    public function applyForJob(Job $job, array $fields, array $files): array
    {
        $apiUrl = $this->getApiCallUrl('Jobs/ApplyForJob');
        $attachFiles = [];
        $fields['job_ad_id'] = $job->getId();

        foreach ($files as $fileField => $fileInfo) {
            // reserved for CV file
            if ($fileField === 'cv') { // cv is reserved field name in form files
                $attachFiles['cv_document'] = $fileInfo;
            } else {
                $attachFiles['other_document_' . $fileField] = $fileInfo;
            }
        }

        $response = $this->performRequest($apiUrl, HttpRequest::METHOD_POST, $fields, $attachFiles);

        return json_decode($response, true);
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

        return $this->performRequest($apiUrl);
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

        return $this->performRequest($apiUrl);
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
            case 'JobAdsListFeed':
                return 'https://cv-speedgroup-se.app.intelliplan.eu/JobAdGlobePages/Feed.aspx?pid=AA31EA47-FDA6-42F3-BD9F-E42186E5A960&version=2';
            default:
                return sprintf(
                    $this->baseApiUrl,
                    $this->customerName,
                    $callType,
                    $this->partnerId
                );
        }
    }

    /**
     * Perform request
     *
     * @param string $url
     * @param string $method
     * @param array $postFields
     * @param array $attachments
     * @return string
     * @throws ApiCallBadRequestException
     * @throws \HTTP_Request2_Exception
     * @throws \HTTP_Request2_LogicException
     */
    protected function performRequest(
        string $url,
        string $method = HttpRequest::METHOD_GET,
        array $postFields = [],
        array $attachments = []
    ): string {
        $method = strtoupper($method) === HttpRequest::METHOD_POST
            ? HttpRequest::METHOD_POST
            : HttpRequest::METHOD_GET;

        /** @var HttpRequest $httpRequest */
        $httpRequest = GeneralUtility::makeInstance(
            HttpRequest::class,
            $url,
            $method
        );
        $httpRequest->setHeader(['Accept' => 'application/json']);

        if (!empty($postFields) && $method === HttpRequest::METHOD_POST) {
            foreach ($postFields as $postField => $value) {
                $httpRequest->addPostParameter($postField, $value);
            }
        }
        if (!empty($attachments) && $method === HttpRequest::METHOD_POST) {
            foreach ($attachments as $postField => $fileInfo) {
                $httpRequest->addUpload($postField, $fileInfo['path'], $fileInfo['name']);
            }
        }

        /** @var \HTTP_Request2_Response $response */
        $response = $httpRequest->send();

        if ($response->getStatus() === 200) {
            return $response->getBody();
        }

        throw new ApiCallBadRequestException('Failed API call "' . $url . '" with code - ' . $response->getStatus());
    }

    /**
     * Add session id to api url
     *
     * @param string $apiUrl
     * @param string $sessionId
     * @return string
     */
    protected function apendSessionIdToUrl(string $apiUrl, string $sessionId): string
    {
        return $apiUrl . '&intelliplan_session_id=' . $sessionId;
    }
}
