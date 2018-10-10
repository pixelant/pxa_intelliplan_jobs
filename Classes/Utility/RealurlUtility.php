<?php
declare(strict_types=1);

namespace Pixelant\PxaIntelliplanJobs\Utility;

use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class RealurlUtility
 * @package Pixelant\PxaIntelliplanJobs\Utility
 */
class RealurlUtility
{
    /**
     * Get real url configuration
     *
     * @return array
     */
    public static function getRealurlConfiguration(): array
    {
        $singleViewPids = self::getSingleViewPids();
        if (!empty($singleViewPids)) {
            $pagesConfiguration = array_fill_keys($singleViewPids, 'pxa_intelliplan_jobs');
            $pagesConfiguration['pxa_intelliplan_jobs'] = self::getConfiguration();
        }

        return $pagesConfiguration ?? [];
    }

    /**
     * Get pids from extension configuration
     *
     * @return array
     */
    public static function getSingleViewPids(): array
    {
        $extConf = ConfigurationUtility::getExtensionConfiguration();
        return GeneralUtility::intExplode(',', $extConf['singleViewPids'] ?? '');
    }

    /**
     * @return array
     */
    public static function getConfiguration(): array
    {
        return [
            [
                'GETvar' => 'tx_pxaintelliplanjobs_pi1[action]',
                'noMatch' => 'bypass',
            ],
            [
                'GETvar' => 'tx_pxaintelliplanjobs_pi1[controller]',
                'noMatch' => 'bypass',
            ],
            [
                'GETvar' => 'tx_pxaintelliplanjobs_pi1[job]',
                'lookUpTable' => [
                    'table' => 'tx_pxaintelliplanjobs_domain_model_job',
                    'id_field' => 'uid',
                    'alias_field' => 'title',
                    'useUniqueCache' => 1,
                    'useUniqueCache_conf' => [
                        'strtolower' => 1,
                        'spaceCharacter' => '-',
                    ],
                    'languageGetVar' => 'L',
                    'languageExceptionUids' => '',
                    'languageField' => 'sys_language_uid',
                    'transOrigPointerField' => 'l10n_parent',
                    'noMatch' => 'bypass',
                    'enable404forInvalidAlias' => false
                ]
            ]
        ];
    }
}
