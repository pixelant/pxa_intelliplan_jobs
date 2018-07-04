plugin.tx_pxaintelliplanjobs_pi1 {
    view {
        templateRootPaths.0 = EXT:pxa_intelliplan_jobs/Resources/Private/Templates/
        templateRootPaths.1 = {$plugin.tx_pxaintelliplanjobs_pi1.view.templateRootPath}
        partialRootPaths.0 = EXT:pxa_intelliplan_jobs/Resources/Private/Partials/
        partialRootPaths.1 = {$plugin.tx_pxaintelliplanjobs_pi1.view.partialRootPath}
        layoutRootPaths.0 = EXT:pxa_intelliplan_jobs/Resources/Private/Layouts/
        layoutRootPaths.1 = {$plugin.tx_pxaintelliplanjobs_pi1.view.layoutRootPath}
    }

    persistence {
        storagePid = {$plugin.tx_pxaintelliplanjobs_pi1.persistence.storagePid}
        #recursive = 1
    }

    features {
        #skipDefaultArguments = 1
    }

    mvc {
        #callDefaultActionIfActionCantBeResolved = 1
    }

    settings {
        hideCategoriesWithoutJobs = {$plugin.tx_pxaintelliplanjobs_pi1.settings.hideCategoriesWithoutJobs}
        dateFormat = {$plugin.tx_pxaintelliplanjobs_pi1.settings.dateFormat}
        showShareForm = {$plugin.tx_pxaintelliplanjobs_pi1.settings.showShareForm}

        sharePageType = 7087989

        shareJob {
            allowMappingProperties = receiverName,receiverEmail,senderName,senderEmail
            defaultSenderEmail = {$plugin.tx_pxaintelliplanjobs_pi1.settings.shareJob.defaultSenderEmail}
        }
    }
}

PXA_INTELLIPLAN_JOBS_AJAX = PAGE
PXA_INTELLIPLAN_JOBS_AJAX {
    typeNum = 7087989

    config {
        disableAllHeaderCode = 1
        admPanel = 0
        debug = 0
    }

    10 = USER_INT
    10 {
        userFunc = TYPO3\CMS\Extbase\Core\Bootstrap->run
        extensionName = PxaIntelliplanJobs
        pluginName = Pi2
        vendorName = Pixelant

        settings =< plugin.tx_pxaintelliplanjobs_pi1.settings
        persistence =< plugin.tx_pxaintelliplanjobs_pi1.persistence
        view =< plugin.tx_pxaintelliplanjobs_pi1.view

        switchableControllerActions {
            JobAjax {
                1 = share
            }
        }
    }
}

lib.pxaIntelliplanJobsContentElementsRenderer = COA
lib.pxaIntelliplanJobsContentElementsRenderer {
    10 = RECORDS
    10 {
        source.data = field:uids
        dontCheckPid = 1
        tables = tt_content
    }
}

lib.pxaIntelliplanJobsBreadCrumbs = COA
lib.pxaIntelliplanJobsBreadCrumbs {
    10 = HMENU
    10 {
        special = rootline
        special.range = 0|-1

        1 = TMENU
        1 {
            NO = 1
            NO {
                allWrap = <li class="page-navigation__item">|</li>
                ATagParams = class="page-navigation__item-link"
            }

            stdWrap.append = COA
            stdWrap.append {
                10 = RECORDS
                10 {
                    tables = tx_pxaintelliplanjobs_domain_model_job
                    source.data = GP:tx_pxaintelliplanjobs_pi1|job
                    source.intval = 1
                    conf {
                        tx_pxaintelliplanjobs_domain_model_job = TEXT
                        tx_pxaintelliplanjobs_domain_model_job {
                            field = title
                            htmlSpecialChars = 1
                        }
                    }

                    wrap = <li class="page-navigation__item"><span>|</span></li>
                }
            }
        }

        wrap = <ul class="page-navigation__list">|</ul>
    }
}