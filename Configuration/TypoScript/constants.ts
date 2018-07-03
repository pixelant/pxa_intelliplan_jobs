plugin.tx_pxaintelliplanjobs_pi1 {
    view {
        # cat=plugin.tx_pxaintelliplanjobs_pi1/file; type=string; label=Path to template root (FE)
        templateRootPath = EXT:pxa_intelliplan_jobs/Resources/Private/Templates/
        # cat=plugin.tx_pxaintelliplanjobs_pi1/file; type=string; label=Path to template partials (FE)
        partialRootPath = EXT:pxa_intelliplan_jobs/Resources/Private/Partials/
        # cat=plugin.tx_pxaintelliplanjobs_pi1/file; type=string; label=Path to template layouts (FE)
        layoutRootPath = EXT:pxa_intelliplan_jobs/Resources/Private/Layouts/
    }

    persistence {
        # cat=plugin.tx_pxaintelliplanjobs_pi1//a; type=string; label=Default storage PID
        storagePid =
    }
}


# Settings
plugin.tx_pxaintelliplanjobs_pi1 {
    # customsubcategory=pxaintelliplanjobs=Main settings
    settings {
        # cat=plugin.tx_pxaintelliplanjobs_pi1/pxaintelliplanjobs/010; type=options[Yes=1,No=0]; label=LLL:EXT:pxa_intelliplan_jobs/Resources/Private/Language/locallang_db.xlf:ts.hideCategoriesWithoutJobs
        hideCategoriesWithoutJobs = 1
    }
}