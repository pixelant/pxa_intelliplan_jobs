plugin.tx_pxaintelliplanjobs {
    view {
        # cat=plugin.tx_pxaintelliplanjobs/file; type=string; label=Path to template root (FE)
        templateRootPath = EXT:pxa_intelliplan_jobs/Resources/Private/Templates/
        # cat=plugin.tx_pxaintelliplanjobs/file; type=string; label=Path to template partials (FE)
        partialRootPath = EXT:pxa_intelliplan_jobs/Resources/Private/Partials/
        # cat=plugin.tx_pxaintelliplanjobs/file; type=string; label=Path to template layouts (FE)
        layoutRootPath = EXT:pxa_intelliplan_jobs/Resources/Private/Layouts/
    }

    persistence {
        # cat=plugin.tx_pxaintelliplanjobs//a; type=string; label=Default storage PID
        storagePid =
    }
}


# Settings
plugin.tx_pxaintelliplanjobs {
    # customsubcategory=pxaintelliplanjobs=Main settings
    settings {
        # cat=plugin.tx_pxaintelliplanjobs/pxaintelliplanjobs/010; type=string; label=LLL:EXT:pxa_intelliplan_jobs/Resources/Private/Language/locallang_db.xlf:ts.dateFormatHeader
        dateFormatHeader = %d %a

        # cat=plugin.tx_pxaintelliplanjobs/pxaintelliplanjobs/020; type=string; label=LLL:EXT:pxa_intelliplan_jobs/Resources/Private/Language/locallang_db.xlf:ts.dateFormat
        dateFormat = Y-m-d

        # cat=plugin.tx_pxaintelliplanjobs/pxaintelliplanjobs/030; type=options[Yes=1,No=0]; label=LLL:EXT:pxa_intelliplan_jobs/Resources/Private/Language/locallang_db.xlf:ts.showShareForm
        showShareForm = 1

        # cat=plugin.tx_pxaintelliplanjobs/pxaintelliplanjobs/040; type=string; label=LLL:EXT:pxa_intelliplan_jobs/Resources/Private/Language/locallang_db.xlf:ts.defaultSenderEmail
        shareJob.defaultSenderEmail = noreply@site.com

        # cat=plugin.tx_pxaintelliplanjobs/pxaintelliplanjobs/050; type=string; label=LLL:EXT:pxa_intelliplan_jobs/Resources/Private/Language/locallang_db.xlf:ts.termsLink
        applyJob.termsLink =
    }
}