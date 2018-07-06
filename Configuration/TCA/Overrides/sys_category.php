<?php
defined('TYPO3_MODE') || die('Access denied.');

call_user_func(function () {
    $ll = 'LLL:EXT:pxa_intelliplan_jobs/Resources/Private/Language/locallang_db.xlf:';

    $columns = [
        'tx_pxaintelliplanjobs_import_id' => [
            'exclude' => true,
            'label' => $ll . 'tca.tx_pxaintelliplanjobs_import_id',
            'config' => [
                'type' => 'input',
                'size' => 5,
                'eval' => 'int'
            ]
        ]
    ];

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns(
        'sys_category',
        $columns
    );

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
        'sys_category',
        '--div--;' . $ll .'tca.category_tab, tx_pxaintelliplanjobs_import_id'
    );
});
