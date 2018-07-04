<?php
defined('TYPO3_MODE') || die('Access denied.');

call_user_func(
    function ($extKey) {
        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
            'Pixelant.PxaIntelliplanJobs',
            'Pi1',
            [
                'Job' => 'list, show'
            ],
            // non-cacheable actions
            [
                'Job' => 'list'
            ]
        );

        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
            'Pixelant.PxaIntelliplanJobs',
            'Pi2',
            [
                'JobAjax' => 'share'
            ],
            // non-cacheable actions
            [
                'JobAjax' => 'share'
            ]
        );

        // wizards
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig(
            '<INCLUDE_TYPOSCRIPT: source="FILE:EXT:' . $extKey . '/Configuration/TypoScript/PageTS/mod.wizards.ts">'
        );
    },
    $_EXTKEY
);

if (\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('realurl')) {
    $configuration = \Pixelant\PxaIntelliplanJobs\Utility\RealurlUtility::getRealurlConfiguration();

    if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['realurl']['_DEFAULT']['fixedPostVars'])) {
        $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['realurl']['_DEFAULT']['fixedPostVars'] +=
            $configuration;
    } else {
        $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['realurl']['_DEFAULT']['fixedPostVars'] =
            $configuration;
    }
}