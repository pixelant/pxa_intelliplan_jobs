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

            Pixelant\PxaIntelliplanJobs\Domain\Model\ContentElement {
                mapping {
                    tableName = tt_content
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
        dateFormat = {$plugin.tx_pxaintelliplanjobs.settings.dateFormat}
        showShareForm = {$plugin.tx_pxaintelliplanjobs.settings.showShareForm}

        sharePageType = 7087989

        shareJob {
            allowMappingProperties = receiverName,receiverEmail,senderName,senderEmail,shareUrl
            defaultSenderEmail = {$plugin.tx_pxaintelliplanjobs.settings.shareJob.defaultSenderEmail}

            template = EXT:pxa_intelliplan_jobs/Resources/Private/Templates/Email/ShareJob.html
        }

        applyJob {
            termsLink = {$plugin.tx_pxaintelliplanjobs.settings.applyJob.termsLink}

            fields {
                firstColumns = first_name,email,zip_code,mobile_phone
                secondColumn = surname,street_address,city,phone_number_home

                validation {
                    first_name = required
                    email = email
                    zip_code = required
                    mobile_phone = required,phone
                    surname = required
                    street_address = required
                    city = required
                    phone_number_home = required,phone
                    agreement_type = agreeCheckbox
                }

                apiSupportFields = first_name,surname,mobile_phone,email,job_ad_id,comment,agreement_type,zip_code,city,birthday_year,birthday_month,birthday_day_of_month,social_number,linkedin_url
                # Exclude some fields when doing SetPersonalInformation
                excludeSetFields = recruit_interest,success,staffing_interest

                requiredFilesFields = cv,letter
            }
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