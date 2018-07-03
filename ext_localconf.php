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
                'Job' => ''
            ]
        );

        // wizards
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig(
            '<INCLUDE_TYPOSCRIPT: source="FILE:EXT:' . $extKey . '/Configuration/TypoScript/PageTS/mod.wizards.ts">'
        );
    },
    $_EXTKEY
);
