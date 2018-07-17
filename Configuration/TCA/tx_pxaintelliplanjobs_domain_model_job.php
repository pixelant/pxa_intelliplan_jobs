<?php
return [
    'ctrl' => [
        'title' => 'LLL:EXT:pxa_intelliplan_jobs/Resources/Private/Language/locallang_db.xlf:tx_pxaintelliplanjobs_domain_model_job',
        'label' => 'title',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'versioningWS' => true,
        'languageField' => 'sys_language_uid',
        'transOrigPointerField' => 'l10n_parent',
        'transOrigDiffSourceField' => 'l10n_diffsource',
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden',
            'starttime' => 'starttime',
            'endtime' => 'endtime',
        ],
        'searchFields' => 'title,company,description,pub_date,category,id,number_of_positions_to_fill,type,job_position_title,job_position_title_id,job_position_category_id,job_location,job_location_id,job_occupation,job_occupation_id,job_category,job_category_id,service_category,service,country,country_id,state,state_id,municipality,municipality_id,company_logo_url,employment_extent,employment_extent_id,employment_type,employment_type_id,job_level,job_level_id,contact1name,contact1email,pub_date_to,last_updated,content_elements',
        'iconfile' => 'EXT:pxa_intelliplan_jobs/Resources/Public/Icons/job.svg'
    ],
    'interface' => [
        'showRecordFieldList' => 'sys_language_uid, l10n_parent, l10n_diffsource, hidden, title, company, description, pub_date, category, id, number_of_positions_to_fill, type, job_position_title, job_position_title_id, job_position_category_id, job_location, job_location_id, job_occupation, job_occupation_id, job_category, job_category_id, service_category, service, country, country_id, state, state_id, municipality, municipality_id, company_logo_url, employment_extent, employment_extent_id, employment_type, employment_type_id, job_level, job_level_id, contact1name, contact1email, pub_date_to, last_updated, content_elements, apply_applications, top_images, bottom_images',
    ],
    'types' => [
        '1' => ['showitem' => 'sys_language_uid, l10n_parent, l10n_diffsource, hidden, title, company, description, pub_date, category, id, number_of_positions_to_fill, type, job_position_title, job_position_title_id, job_position_category_id, job_location, job_location_id, job_occupation, job_occupation_id, job_category, job_category_id, service_category, service, country, country_id, state, state_id, municipality, municipality_id, company_logo_url, employment_extent, employment_extent_id, employment_type, employment_type_id, job_level, job_level_id, contact1name, contact1email, pub_date_to, last_updated, --div--;LLL:EXT:pxa_intelliplan_jobs/Resources/Private/Language/locallang_db.xlf:tab.additional_fields, top_images, bottom_images, content_elements, --div--;LLL:EXT:pxa_intelliplan_jobs/Resources/Private/Language/locallang_db.xlf:tab.apply_for_job, apply_applications, --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.access, starttime, endtime'],
    ],
    'columns' => [
        'sys_language_uid' => [
            'exclude' => true,
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.language',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'special' => 'languages',
                'items' => [
                    [
                        'LLL:EXT:lang/locallang_general.xlf:LGL.allLanguages',
                        -1,
                        'flags-multiple'
                    ]
                ],
                'default' => 0,
            ],
        ],
        'l10n_parent' => [
            'displayCond' => 'FIELD:sys_language_uid:>:0',
            'exclude' => true,
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.l18n_parent',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    ['', 0],
                ],
                'foreign_table' => 'tx_pxaintelliplanjobs_domain_model_job',
                'foreign_table_where' => 'AND tx_pxaintelliplanjobs_domain_model_job.pid=###CURRENT_PID### AND tx_pxaintelliplanjobs_domain_model_job.sys_language_uid IN (-1,0)',
            ],
        ],
        'l10n_diffsource' => [
            'config' => [
                'type' => 'passthrough',
            ],
        ],
        't3ver_label' => [
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.versionLabel',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'max' => 255,
            ],
        ],
        'hidden' => [
            'exclude' => true,
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.hidden',
            'config' => [
                'type' => 'check',
                'items' => [
                    '1' => [
                        '0' => 'LLL:EXT:lang/locallang_core.xlf:labels.enabled'
                    ]
                ],
            ],
        ],
        'starttime' => [
            'exclude' => true,
            'l10n_mode' => 'mergeIfNotBlank',
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.starttime',
            'config' => [
                'type' => 'input',
                'size' => 13,
                'eval' => 'datetime',
                'default' => 0,
            ]
        ],
        'endtime' => [
            'exclude' => true,
            'l10n_mode' => 'mergeIfNotBlank',
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.endtime',
            'config' => [
                'type' => 'input',
                'size' => 13,
                'eval' => 'datetime',
                'default' => 0,
                'range' => [
                    'upper' => mktime(0, 0, 0, 1, 1, 2038)
                ]
            ],
        ],
        'title' => [
            'label' => 'LLL:EXT:pxa_intelliplan_jobs/Resources/Private/Language/locallang_db.xlf:tx_pxaintelliplanjobs_domain_model_job.title',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim, required'
            ],
        ],
        'company' => [
            'label' => 'LLL:EXT:pxa_intelliplan_jobs/Resources/Private/Language/locallang_db.xlf:tx_pxaintelliplanjobs_domain_model_job.company',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'description' => [
            'label' => 'LLL:EXT:pxa_intelliplan_jobs/Resources/Private/Language/locallang_db.xlf:tx_pxaintelliplanjobs_domain_model_job.description',
            'config' => [
                'type' => 'text',
                'cols' => 40,
                'rows' => 15,
                'eval' => 'trim',
            ],
            'defaultExtras' => 'richtext:rte_transform[mode=ts_css]'
        ],
        'pub_date' => [
            'label' => 'LLL:EXT:pxa_intelliplan_jobs/Resources/Private/Language/locallang_db.xlf:tx_pxaintelliplanjobs_domain_model_job.pub_date',
            'config' => [
                'type' => 'input',
                'size' => 10,
                'eval' => 'datetime',
                'default' => time()
            ],
        ],
        'category' => [
            'label' => 'LLL:EXT:pxa_intelliplan_jobs/Resources/Private/Language/locallang_db.xlf:tx_pxaintelliplanjobs_domain_model_job.category',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'id' => [
            'label' => 'LLL:EXT:pxa_intelliplan_jobs/Resources/Private/Language/locallang_db.xlf:tx_pxaintelliplanjobs_domain_model_job.id',
            'config' => [
                'type' => 'input',
                'size' => 4,
                'eval' => 'int'
            ]
        ],
        'number_of_positions_to_fill' => [
            'label' => 'LLL:EXT:pxa_intelliplan_jobs/Resources/Private/Language/locallang_db.xlf:tx_pxaintelliplanjobs_domain_model_job.number_of_positions_to_fill',
            'config' => [
                'type' => 'input',
                'size' => 4,
                'eval' => 'int'
            ]
        ],
        'type' => [
            'label' => 'LLL:EXT:pxa_intelliplan_jobs/Resources/Private/Language/locallang_db.xlf:tx_pxaintelliplanjobs_domain_model_job.type',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'job_position_title' => [
            'label' => 'LLL:EXT:pxa_intelliplan_jobs/Resources/Private/Language/locallang_db.xlf:tx_pxaintelliplanjobs_domain_model_job.job_position_title',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'job_position_title_id' => [
            'label' => 'LLL:EXT:pxa_intelliplan_jobs/Resources/Private/Language/locallang_db.xlf:tx_pxaintelliplanjobs_domain_model_job.job_position_title_id',
            'config' => [
                'type' => 'input',
                'size' => 4,
                'eval' => 'int'
            ]
        ],
        'job_position_category_id' => [
            'label' => 'LLL:EXT:pxa_intelliplan_jobs/Resources/Private/Language/locallang_db.xlf:tx_pxaintelliplanjobs_domain_model_job.job_position_category_id',
            'config' => [
                'type' => 'input',
                'size' => 4,
                'eval' => 'int'
            ]
        ],
        'job_location' => [
            'label' => 'LLL:EXT:pxa_intelliplan_jobs/Resources/Private/Language/locallang_db.xlf:tx_pxaintelliplanjobs_domain_model_job.job_location',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'job_location_id' => [
            'label' => 'LLL:EXT:pxa_intelliplan_jobs/Resources/Private/Language/locallang_db.xlf:tx_pxaintelliplanjobs_domain_model_job.job_location_id',
            'config' => [
                'type' => 'input',
                'size' => 4,
                'eval' => 'int'
            ]
        ],
        'job_occupation' => [
            'label' => 'LLL:EXT:pxa_intelliplan_jobs/Resources/Private/Language/locallang_db.xlf:tx_pxaintelliplanjobs_domain_model_job.job_occupation',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'job_occupation_id' => [
            'label' => 'LLL:EXT:pxa_intelliplan_jobs/Resources/Private/Language/locallang_db.xlf:tx_pxaintelliplanjobs_domain_model_job.job_occupation_id',
            'config' => [
                'type' => 'input',
                'size' => 4,
                'eval' => 'int'
            ]
        ],
        'job_category' => [
            'label' => 'LLL:EXT:pxa_intelliplan_jobs/Resources/Private/Language/locallang_db.xlf:tx_pxaintelliplanjobs_domain_model_job.job_category',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'job_category_id' => [
            'label' => 'LLL:EXT:pxa_intelliplan_jobs/Resources/Private/Language/locallang_db.xlf:tx_pxaintelliplanjobs_domain_model_job.job_category_id',
            'config' => [
                'type' => 'input',
                'size' => 4,
                'eval' => 'int'
            ]
        ],
        'service_category' => [
            'label' => 'LLL:EXT:pxa_intelliplan_jobs/Resources/Private/Language/locallang_db.xlf:tx_pxaintelliplanjobs_domain_model_job.service_category',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'service' => [
            'label' => 'LLL:EXT:pxa_intelliplan_jobs/Resources/Private/Language/locallang_db.xlf:tx_pxaintelliplanjobs_domain_model_job.service',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'country' => [
            'label' => 'LLL:EXT:pxa_intelliplan_jobs/Resources/Private/Language/locallang_db.xlf:tx_pxaintelliplanjobs_domain_model_job.country',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'country_id' => [
            'label' => 'LLL:EXT:pxa_intelliplan_jobs/Resources/Private/Language/locallang_db.xlf:tx_pxaintelliplanjobs_domain_model_job.country_id',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'state' => [
            'label' => 'LLL:EXT:pxa_intelliplan_jobs/Resources/Private/Language/locallang_db.xlf:tx_pxaintelliplanjobs_domain_model_job.state',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'state_id' => [
            'label' => 'LLL:EXT:pxa_intelliplan_jobs/Resources/Private/Language/locallang_db.xlf:tx_pxaintelliplanjobs_domain_model_job.state_id',
            'config' => [
                'type' => 'input',
                'size' => 4,
                'eval' => 'int'
            ]
        ],
        'municipality' => [
            'label' => 'LLL:EXT:pxa_intelliplan_jobs/Resources/Private/Language/locallang_db.xlf:tx_pxaintelliplanjobs_domain_model_job.municipality',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'municipality_id' => [
            'label' => 'LLL:EXT:pxa_intelliplan_jobs/Resources/Private/Language/locallang_db.xlf:tx_pxaintelliplanjobs_domain_model_job.municipality_id',
            'config' => [
                'type' => 'input',
                'size' => 4,
                'eval' => 'int'
            ]
        ],
        'company_logo_url' => [
            'label' => 'LLL:EXT:pxa_intelliplan_jobs/Resources/Private/Language/locallang_db.xlf:tx_pxaintelliplanjobs_domain_model_job.company_logo_url',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'employment_extent' => [
            'label' => 'LLL:EXT:pxa_intelliplan_jobs/Resources/Private/Language/locallang_db.xlf:tx_pxaintelliplanjobs_domain_model_job.employment_extent',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'employment_extent_id' => [
            'label' => 'LLL:EXT:pxa_intelliplan_jobs/Resources/Private/Language/locallang_db.xlf:tx_pxaintelliplanjobs_domain_model_job.employment_extent_id',
            'config' => [
                'type' => 'input',
                'size' => 4,
                'eval' => 'int'
            ]
        ],
        'employment_type' => [
            'label' => 'LLL:EXT:pxa_intelliplan_jobs/Resources/Private/Language/locallang_db.xlf:tx_pxaintelliplanjobs_domain_model_job.employment_type',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'employment_type_id' => [
            'label' => 'LLL:EXT:pxa_intelliplan_jobs/Resources/Private/Language/locallang_db.xlf:tx_pxaintelliplanjobs_domain_model_job.employment_type_id',
            'config' => [
                'type' => 'input',
                'size' => 4,
                'eval' => 'int'
            ]
        ],
        'job_level' => [
            'label' => 'LLL:EXT:pxa_intelliplan_jobs/Resources/Private/Language/locallang_db.xlf:tx_pxaintelliplanjobs_domain_model_job.job_level',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'job_level_id' => [
            'label' => 'LLL:EXT:pxa_intelliplan_jobs/Resources/Private/Language/locallang_db.xlf:tx_pxaintelliplanjobs_domain_model_job.job_level_id',
            'config' => [
                'type' => 'input',
                'size' => 4,
                'eval' => 'int'
            ]
        ],
        'contact1name' => [
            'label' => 'LLL:EXT:pxa_intelliplan_jobs/Resources/Private/Language/locallang_db.xlf:tx_pxaintelliplanjobs_domain_model_job.contact1name',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'contact1email' => [
            'label' => 'LLL:EXT:pxa_intelliplan_jobs/Resources/Private/Language/locallang_db.xlf:tx_pxaintelliplanjobs_domain_model_job.contact1email',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'pub_date_to' => [
            'label' => 'LLL:EXT:pxa_intelliplan_jobs/Resources/Private/Language/locallang_db.xlf:tx_pxaintelliplanjobs_domain_model_job.pub_date_to',
            'config' => [
                'type' => 'input',
                'size' => 10,
                'eval' => 'datetime',
                'default' => time()
            ],
        ],
        'last_updated' => [
            'label' => 'LLL:EXT:pxa_intelliplan_jobs/Resources/Private/Language/locallang_db.xlf:tx_pxaintelliplanjobs_domain_model_job.last_updated',
            'config' => [
                'type' => 'input',
                'size' => 10,
                'eval' => 'datetime',
                'default' => time()
            ],
        ],
        'content_elements' => [
            'label' => 'LLL:EXT:pxa_intelliplan_jobs/Resources/Private/Language/locallang_db.xlf:tx_pxaintelliplanjobs_domain_model_job.content_elements',
            'config' => [
                'type' => 'group',
                'internal_type' => 'db',
                'allowed' => 'tt_content',
                'MM' => 'tx_pxaintelliplanjobs_domain_model_job_content_mm',
                'foreign_table' => 'tt_content'
            ],
        ],
        'apply_applications' => [
            'label' => 'LLL:EXT:pxa_intelliplan_jobs/Resources/Private/Language/locallang_db.xlf:tx_pxaintelliplanjobs_domain_model_job.apply_applications',
            'config' => [
                'type' => 'inline',
                'foreign_table' => 'tx_pxaintelliplanjobs_domain_model_applyapplication',
                'foreign_field' => 'job',
                'maxitems' => 999,
                'appearance' => [
                    'collapseAll' => 1,
                    'expandSingle' => 1,
                ],
            ],
        ],
        'top_images' => [
            'label' => 'LLL:EXT:pxa_intelliplan_jobs/Resources/Private/Language/locallang_db.xlf:tx_pxaintelliplanjobs_domain_model_job.top_images',
            'config' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig(
                'top_images',
                [
                    'appearance' => [
                        'createNewRelationLinkTitle' =>
                            'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:images.addFileReference',
                        'showPossibleLocalizationRecords' => false,
                        'showRemovedLocalizationRecords' => true,
                        'showAllLocalizationLink' => false,
                        'showSynchronizationLink' => false
                    ],
                    'foreign_match_fields' => [
                        'fieldname' => 'top_images',
                        'tablenames' => 'tx_pxaintelliplanjobs_domain_model_job',
                        'table_local' => 'sys_file',
                    ],
                    'foreign_types' => [
                        '0' => [
                            'showitem' => '
                            --palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                            --palette--;;filePalette'
                        ],
                        \TYPO3\CMS\Core\Resource\File::FILETYPE_IMAGE => [
                            'showitem' => '
                            --palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                            --palette--;;filePalette'
                        ]
                    ],
                    'behaviour' => [
                        'allowLanguageSynchronization' => true,
                        'localizeChildrenAtParentLocalization' => true,
                    ],
                ],
                $GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext']
            ),
        ],
        'bottom_images' => [
            'label' => 'LLL:EXT:pxa_intelliplan_jobs/Resources/Private/Language/locallang_db.xlf:tx_pxaintelliplanjobs_domain_model_job.bottom_images',
            'config' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig(
                'bottom_images',
                [
                    'appearance' => [
                        'createNewRelationLinkTitle' =>
                            'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:images.addFileReference',
                        'showPossibleLocalizationRecords' => false,
                        'showRemovedLocalizationRecords' => true,
                        'showAllLocalizationLink' => false,
                        'showSynchronizationLink' => false
                    ],
                    'foreign_match_fields' => [
                        'fieldname' => 'bottom_images',
                        'tablenames' => 'tx_pxaintelliplanjobs_domain_model_job',
                        'table_local' => 'sys_file',
                    ],
                    'foreign_types' => [
                        '0' => [
                            'showitem' => '
                            --palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                            --palette--;;filePalette'
                        ],
                        \TYPO3\CMS\Core\Resource\File::FILETYPE_IMAGE => [
                            'showitem' => '
                            --palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                            --palette--;;filePalette'
                        ]
                    ],
                    'behaviour' => [
                        'allowLanguageSynchronization' => true,
                        'localizeChildrenAtParentLocalization' => true,
                    ],
                ],
                $GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext']
            ),
        ],
        'crdate' => [
            'config' => [
                'type' => 'passthrough',
            ],
        ],
    ],
];
