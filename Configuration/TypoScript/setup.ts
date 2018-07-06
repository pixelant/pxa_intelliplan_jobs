plugin.tx_pxaintelliplanjobs {
    view {
        templateRootPaths.0 = EXT:pxa_intelliplan_jobs/Resources/Private/Templates/
        templateRootPaths.1 = {$plugin.tx_pxaintelliplanjobs.view.templateRootPath}
        partialRootPaths.0 = EXT:pxa_intelliplan_jobs/Resources/Private/Partials/
        partialRootPaths.1 = {$plugin.tx_pxaintelliplanjobs.view.partialRootPath}
        layoutRootPaths.0 = EXT:pxa_intelliplan_jobs/Resources/Private/Layouts/
        layoutRootPaths.1 = {$plugin.tx_pxaintelliplanjobs.view.layoutRootPath}
    }

    persistence {
        storagePid = {$plugin.tx_pxaintelliplanjobs.persistence.storagePid}

        classes {
            Pixelant\PxaIntelliplanJobs\Domain\Model\Category {
                mapping {
                    tableName = sys_category

                    columns {
                        tx_pxaintelliplanjobs_import_id.mapOnProperty = importId
                    }
                }
            }
        }
    }

    features {
        #skipDefaultArguments = 1
    }

    mvc {
        #callDefaultActionIfActionCantBeResolved = 1
    }

    settings {
        hideCategoriesWithoutJobs = {$plugin.tx_pxaintelliplanjobs.settings.hideCategoriesWithoutJobs}
        dateFormat = {$plugin.tx_pxaintelliplanjobs.settings.dateFormat}
        showShareForm = {$plugin.tx_pxaintelliplanjobs.settings.showShareForm}

        sharePageType = 7087989

        shareJob {
            allowMappingProperties = receiverName,receiverEmail,senderName,senderEmail,shareUrl
            defaultSenderEmail = {$plugin.tx_pxaintelliplanjobs.settings.shareJob.defaultSenderEmail}

            template = EXT:pxa_intelliplan_jobs/Resources/Private/Templates/Email/ShareJob.html
        }
    }
}

page {
    includeJSFooter {
        pxa_intelliplan_jobs = EXT:pxa_intelliplan_jobs/Resources/Public/Js/pxa_intelliplan_jobs.js
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

        settings =< plugin.tx_pxaintelliplanjobs.settings
        persistence =< plugin.tx_pxaintelliplanjobs.persistence
        view =< plugin.tx_pxaintelliplanjobs.view

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
                    source.data = GP:tx_pxaintelliplanjobs|job
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