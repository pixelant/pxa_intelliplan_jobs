<?php
declare(strict_types=1);

namespace Pixelant\PxaIntelliplanJobs\Utility;

/**
 * Class ConfigurationUtility
 *
 * @package Pixelant\PxaIntelliplanJobs\Utility
 */
class ConfigurationUtility
{
    /**
     * Extension configuration
     *
     * @var array
     */
    protected static $extensionConfiguration;

    /**
     * Get extension configuration
     *
     * @return array
     */
    public static function getExtensionConfiguration(): array
    {
        if (self::$extensionConfiguration === null) {
            self::$extensionConfiguration = unserialize(
                $GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['pxa_intelliplan_jobs'] ?? ''
            );
        }

        return self::$extensionConfiguration ?: [];
    }

    /**
     * Return partner code
     *
     * @return string
     */
    public static function getPartnerCode(): string
    {
        return self::getExtensionConfiguration()['partnerCode'] ?? '';
    }
}
