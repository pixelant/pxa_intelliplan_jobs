<?php
declare(strict_types=1);

namespace Pixelant\PxaIntelliplanJobs\Provider;

use Pixelant\PxaIntelliplanJobs\Utility\ConfigurationUtility;

/**
 * Class IntelliplanDataProvider
 * @package Pixelant\PxaIntelliplanJobs\Provider
 */
class IntelliplanDataProvider
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
}
