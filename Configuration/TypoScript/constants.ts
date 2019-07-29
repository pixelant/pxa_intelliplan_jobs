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

        # cat=plugin.tx_pxaintelliplanjobs/pxaintelliplanjobs/070; type=string; label=LLL:EXT:pxa_intelliplan_jobs/Resources/Private/Language/locallang_db.xlf:ts.termsLink
        applyJob.termsLink =

        # cat=plugin.tx_pxaintelliplanjobs/pxaintelliplanjobs/080; type=string; label=LLL:EXT:pxa_intelliplan_jobs/Resources/Private/Language/locallang_db.xlf:ts.singleView.image.width
        singleView.image.width = 750

        # cat=plugin.tx_pxaintelliplanjobs/pxaintelliplanjobs/090; type=string; label=LLL:EXT:pxa_intelliplan_jobs/Resources/Private/Language/locallang_db.xlf:ts.singleView.image.height
        singleView.image.height =

        # cat=plugin.tx_pxaintelliplanjobs/pxaintelliplanjobs/100; type=string; label=LLL:EXT:pxa_intelliplan_jobs/Resources/Private/Language/locallang_db.xlf:ts.singleView.image.defaultLogoImage
        singleView.image.defaultLogoImage =
    }

    # customsubcategory=mailsettings=Mail settings
    settings {
        mail {
            # cat=plugin.tx_pxaintelliplanjobs/mailsettings/010; type=string; label=LLL:EXT:pxa_intelliplan_jobs/Resources/Private/Language/locallang_db.xlf:ts.senderName
            senderName = Sender

            # cat=plugin.tx_pxaintelliplanjobs/mailsettings/020; type=string; label=LLL:EXT:pxa_intelliplan_jobs/Resources/Private/Language/locallang_db.xlf:ts.senderEmail
            senderEmail = noreply@site.com

            # cat=plugin.tx_pxaintelliplanjobs/mailsettings/030; type=options[Yes=1,No=0]; label=LLL:EXT:pxa_intelliplan_jobs/Resources/Private/Language/locallang_db.xlf:ts.thankYouMail.enable
            thankYouMail.enable = 1

            # cat=plugin.tx_pxaintelliplanjobs/mailsettings/040; type=string; label=LLL:EXT:pxa_intelliplan_jobs/Resources/Private/Language/locallang_db.xlf:ts.thankYouMail.subject
            thankYouMail.subject =

            # cat=plugin.tx_pxaintelliplanjobs/mailsettings/050; type=string; label=LLL:EXT:pxa_intelliplan_jobs/Resources/Private/Language/locallang_db.xlf:ts.thankYouMail.logo
            thankYouMail.logo =
        }

        # cat=plugin.tx_pxaintelliplanjobs/mailsettings/050; type=string; label=LLL:EXT:pxa_intelliplan_jobs/Resources/Private/Language/locallang_db.xlf:ts.siteName
        shareJob.siteName = Site name

        # cat=plugin.tx_pxaintelliplanjobs/mailsettings/060; type=string; label=LLL:EXT:pxa_intelliplan_jobs/Resources/Private/Language/locallang_db.xlf:ts.siteDomain
        shareJob.siteDomain = www.site.com
    }

    # customsubcategory=recaptcha=Recaptcha settings
    settings {
        recaptcha {
            # cat=plugin.tx_pxaintelliplanjobs/recaptcha/010; type=string; label=LLL:EXT:pxa_intelliplan_jobs/Resources/Private/Language/locallang_db.xlf:ts.siteKey
            siteKey =

            # cat=plugin.tx_pxaintelliplanjobs/recaptcha/020; type=string; label=LLL:EXT:pxa_intelliplan_jobs/Resources/Private/Language/locallang_db.xlf:ts.secretKey
            secretKey =
        }
    }
}
