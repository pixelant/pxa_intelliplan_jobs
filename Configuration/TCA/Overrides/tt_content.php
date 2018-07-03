<?php

call_user_func(function () {
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
        'Pixelant.PxaIntelliplanJobs',
        'Pi1',
        'Intelliplan jobs'
    );

    $extKey = 'pxa_intelliplan_jobs';
    $pluginSignature = 'pxaintelliplanjobs_pi1';

    $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist'][$pluginSignature] = 'pi_flexform';
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
        $pluginSignature,
        'FILE:EXT:' . $extKey . '/Configuration/FlexForms/flexform_pi1.xml'
    );
});
