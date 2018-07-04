<?php
defined('TYPO3_MODE') || die('Access denied.');

// Categorize jobs
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::makeCategorizable(
    'pxa_intelliplan_jobs',
    'tx_pxaintelliplanjobs_domain_model_job',
    'categories',
    [
        // @codingStandardsIgnoreStart
        'label' => 'LLL:EXT:pxa_intelliplan_jobs/Resources/Private/Language/locallang_db.xlf:tx_pxaintelliplanjobs_domain_model_job.categories',
        // @codingStandardsIgnoreEnd
        'exclude' => false,
        'fieldConfiguration' => [
            'foreign_table_where' => ' AND sys_category.sys_language_uid IN (-1, 0)',
            'minitems' => 1
        ],
        'l10n_mode' => 'exclude',
        'l10n_display' => 'hideDiff'
    ]
);
