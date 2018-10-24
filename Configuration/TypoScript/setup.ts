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
    }

    features {
        #skipDefaultArguments = 1
    }

    mvc {
        #callDefaultActionIfActionCantBeResolved = 1
    }

    settings {
        dateFormat = {$plugin.tx_pxaintelliplanjobs.settings.dateFormat}
        dateFormatHeader = {$plugin.tx_pxaintelliplanjobs.settings.dateFormatHeader}
        showShareForm = {$plugin.tx_pxaintelliplanjobs.settings.showShareForm}

        sharePageType = 7087989

        shareJob {
            allowMappingProperties = receiverName,receiverEmail,senderName,senderEmail,shareUrl
            defaultSenderEmail = {$plugin.tx_pxaintelliplanjobs.settings.mail.senderEmail}
            siteName = {$plugin.tx_pxaintelliplanjobs.settings.shareJob.siteName}
            siteDomain = {$plugin.tx_pxaintelliplanjobs.settings.shareJob.siteDomain}

            template = EXT:pxa_intelliplan_jobs/Resources/Private/Templates/Email/ShareJob.html
        }

        applyJob {
            termsLink = {$plugin.tx_pxaintelliplanjobs.settings.applyJob.termsLink}

            fields {
                formFields = first_name,surname,email,street_address,zip_code,city,social_security_number,mobile_phone

                validationCV {
                    first_name = required
                    email = email
                    zip_code = required
                    surname = required
                    street_address = required
                    city = required
                    mobile_phone = required,phone
                    social_security_number = required,phone
                    agreement_type = agreeCheckbox
                }

                validationNoCV < .validationCV

                apiSupportFields = first_name,surname,mobile_phone,email,job_ad_id,comment,agreement_type,zip_code,city,birthday_year,birthday_month,birthday_day_of_month,social_security_number,linkedin_url
                # Exclude some fields when doing SetPersonalInformation
                excludeSetFields = recruit_interest,success,staffing_interest

                requiredFilesFields {
                    validationCV = cv,letter
                }
                allowedFileTypes = doc,docx,pdf,rtf,txt
                allowedMimeTypes = application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/pdf,application/rtf,text/rtf,text/plain

                # If user doesn't have CV what checkboxes to render
                cvQuestionsPreset {
                    all {
                        0.question = Hur snart kan du börja arbeta?
                    }
                }
                noCvQuestionsPreset {
                    # Job occupation ID
                    8204 {
                        0 {
                            question = Har du svenskt B-körkort?
                            type = radio
                        }

                        1 {
                            question = Har du tillgång till bil?
                            type = radio
                        }

                        2 {
                            question = Har du truckkort?
                            type = radio
                            additional =  Om ja, hur lång erfarenhet har du av truckkörning?
                        }

                        3 {
                            question = Har du tidigare erfarenhet av lagerarbete?
                            type = radio
                            additional = Om ja, hur lång erfarenhet har du?
                        }

                        4 {
                            question = Kan du arbeta skift?
                            type = radio
                        }

                        5.question = Hur snart kan du börja arbeta?
                    }

                    8205 {
                        0 {
                            question = Har du svenskt B-körkort?
                            type = radio
                        }

                        1 {
                            question = Har du tillgång till bil?
                            type = radio
                        }

                        2 {
                            question = Har du erfarenhet av industriarbete?
                            type = radio
                            additional = Om ja, hur lång erfarenhet har du?
                        }

                        3 {
                            question = Har du erfarenhet av verkstadsarbete?
                            type = radio
                            additional = Om ja, hur lång erfarenhet har du?
                        }

                        4 {
                            question = Kan du arbeta skift?
                            type = radio
                        }

                        5.question = Hur snart kan du börja arbeta?
                    }
                }

            }
        }

        singleView {
            image {
                width = {$plugin.tx_pxaintelliplanjobs.settings.singleView.image.width}
                height = {$plugin.tx_pxaintelliplanjobs.settings.singleView.image.height}
                defaultLogoImage = {$plugin.tx_pxaintelliplanjobs.settings.singleView.image.defaultLogoImage}
            }
        }

        # Some values from job type field from API need to have different value than it's
        jobTypes {
            replaceValues {
                needle {
                    0 = bemanning
                    1 = rekrytering
                }

                replacement {
                    0 = Bemanning
                    1 = Rekrytering
                }
            }

            cvOptionalCategoryId = 8195
        }

        mail {
            senderName = {$plugin.tx_pxaintelliplanjobs.settings.mail.senderName}
            senderEmail = {$plugin.tx_pxaintelliplanjobs.settings.mail.senderEmail}
            thankYouMail {
                enable = {$plugin.tx_pxaintelliplanjobs.settings.mail.thankYouMail.enable}
                subject = {$plugin.tx_pxaintelliplanjobs.settings.mail.thankYouMail.subject}
                template = EXT:pxa_intelliplan_jobs/Resources/Private/Templates/Email/ThankYou.html
                apiMailField = email
                logo = {$plugin.tx_pxaintelliplanjobs.settings.mail.thankYouMail.logo}
            }
        }
    }
}

page {
    includeJSFooter {
        pxa_intelliplan_jobs = EXT:pxa_intelliplan_jobs/Resources/Public/Js/pxa_intelliplan_jobs.js
        pxa_jobs_filtering = EXT:pxa_intelliplan_jobs/Resources/Public/Js/pxa_jobs_filtering.js
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

[globalVar = GP:tx_pxaintelliplanjobs_pi1|job > 0]
    config.defaultGetVars {
        tx_pxaintelliplanjobs_pi1.action = show
    }
[end]

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

                stdWrap.append = COA
                stdWrap.append {
                    # Save last page UID, assume this is list page of jobs
                    10 = LOAD_REGISTER
                    10 {
                        lastMenuPid.cObject = TEXT
                        lastMenuPid.cObject.stdWrap.data = field:uid
                        lastMenuPid.cObject.stdWrap.intval = 1
                    }
                }
            }
        }
    }

    20 = COA
    20 {
        5 = TEXT
        5 {
            typolink {
                parameter.data = REGISTER:lastMenuPid
                returnLast = url
            }

            dataWrap = <li class="page-navigation__item"><a href="|#category={field:categoryUid}" class="page-navigation__item-link">{field:categoryTitle}</a></li>
            if.isTrue.data = field:categoryTitle
        }

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

    wrap = <ul class="page-navigation__list">|</ul>
}
