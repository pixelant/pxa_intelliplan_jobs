<?php
defined('TYPO3_MODE') || die('Access denied.');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr(
    'tx_pxaintelliplanjobs_domain_model_job',
    'EXT:pxa_intelliplan_jobs/Resources/Private/Language/locallang_csh_tx_pxaintelliplanjobs_domain_model_job.xlf'
);
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_pxaintelliplanjobs_domain_model_job');

call_user_func(function () {
    $icons = [
        'ext-pxa-intelliplan-jobs-wizard-icon' => 'job_wizard.svg',
    ];

    /** @var \TYPO3\CMS\Core\Imaging\IconRegistry $iconRegistry */
    $iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(
        \TYPO3\CMS\Core\Imaging\IconRegistry::class
    );
    foreach ($icons as $identifier => $path) {
        $iconRegistry->registerIcon(
            $identifier,
            \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
            ['source' => 'EXT:pxa_intelliplan_jobs/Resources/Public/Icons/' . $path]
        );
    }
});
