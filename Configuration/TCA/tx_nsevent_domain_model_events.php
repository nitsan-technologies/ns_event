<?php

$typo3VersionArray = \TYPO3\CMS\Core\Utility\VersionNumberUtility::convertVersionStringToArray(
    \TYPO3\CMS\Core\Utility\VersionNumberUtility::getCurrentTypo3Version()
);

if (version_compare((string)$typo3VersionArray['version_main'], '11', '>=')) {
    $languageConfig = [
        'type' => 'language',
    ];
} else {
    $languageConfig = [
        'type' => 'select',
        'renderType' => 'selectSingle',
        'special' => 'languages',
        'items' => [
            [
                'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.allLanguages',
                -1,
                'flags-multiple'
            ]
        ],
        'default' => 0,
    ];
}


return [
    'ctrl' => [
        'title' => 'LLL:EXT:ns_event/Resources/Private/Language/locallang_db.xlf:tx_nsevent_domain_model_events',
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
        'searchFields' => 'title,teaser,description,link,address',
        'iconfile' => 'EXT:ns_event/Resources/Public/Icons/events/Event.svg',
        'security' => [
            'ignorePageTypeRestriction' => true
        ],
    ],
    'types' => [
        '1' => [
            'showitem' => '
                title, 
                --palette--;;datePalette,
                poster_image, 
                teaser, 
                description, 
                address, 
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:language, 
                    sys_language_uid, 
                    l10n_parent, l10n_diffsource,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access, 
                    hidden, 
                    starttime, 
                    endtime, 
            '],
    ],
    'palettes' => [
        'datePalette' => [
            'showitem' => 'start_date, end_date',
        ],
    ],

    'columns' => [
        'sys_language_uid' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:sLGL.language',
            'config' => $languageConfig
        ],
        'l10n_parent' => [
            'displayCond' => 'FIELD:sys_language_uid:>:0',
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.l18n_parent',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'default' => 0,
                'items' => [
                    ['', 0],
                ],
                'foreign_table' => 'tx_nsevent_domain_model_events',
                'foreign_table_where' => 'AND {#tx_nsevent_domain_model_events}.{#pid}=###CURRENT_PID### AND {#tx_nsevent_domain_model_events}.{#sys_language_uid} IN (-1,0)',
            ],
        ],
        'l10n_diffsource' => [
            'config' => [
                'type' => 'passthrough',
            ],
        ],
        'starttime' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.starttime',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'eval' => 'datetime,int',
                'default' => 0,
                'behaviour' => [
                    'allowLanguageSynchronization' => true
                ]
            ],
        ],
        'endtime' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.endtime',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'eval' => 'datetime,int',
                'default' => 0,
                'range' => [
                    'upper' => mktime(0, 0, 0, 1, 1, 2038)
                ],
                'behaviour' => [
                    'allowLanguageSynchronization' => true
                ]
            ],
        ],

        'title' => [
            'exclude' => true,
            'label' => 'LLL:EXT:ns_event/Resources/Private/Language/locallang_db.xlf:tx_nsevent_domain_model_events.title',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim,required'
            ],
        ],

        'start_date' => [
            'exclude' => true,
            'label' => 'LLL:EXT:ns_event/Resources/Private/Language/locallang_db.xlf:tx_nsevent_domain_model_events.start_date',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'size' => 12,
                'eval' => 'datetime,required',
                'default' => time()
            ],
        ],
        'end_date' => [
            'exclude' => true,
            'label' => 'LLL:EXT:ns_event/Resources/Private/Language/locallang_db.xlf:tx_nsevent_domain_model_events.end_date',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'size' => 12,
                'eval' => 'datetime,required',
                'default' => 0
            ],
        ],

        'teaser' => [
            'exclude' => true,
            'label' => 'LLL:EXT:ns_event/Resources/Private/Language/locallang_db.xlf:tx_nsevent_domain_model_events.teaser',
            'config' => [
                'type' => 'text',
                'cols' => 40,
                'rows' => 5,
                'eval' => 'trim'
            ]
        ],
        'poster_image' => [
            'exclude' => true,
            'label' => 'LLL:EXT:ns_event/Resources/Private/Language/locallang_db.xlf:tx_nsevent_domain_model_events.poster_image',
            // @extensionScannerIgnoreLine
            'config' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig(
                'poster_image',
                [
                    'appearance' => [
                        'createNewRelationLinkTitle' => 'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:images.addFileReference'
                    ],
                    'foreign_types' => [
                        '0' => [
                            'showitem' => '
                            --palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                            --palette--;;filePalette'
                        ],
                        \TYPO3\CMS\Core\Resource\File::FILETYPE_TEXT => [
                            'showitem' => '
                            --palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                            --palette--;;filePalette'
                        ],
                        \TYPO3\CMS\Core\Resource\File::FILETYPE_IMAGE => [
                            'showitem' => '
                            --palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                            --palette--;;filePalette'
                        ],
                        \TYPO3\CMS\Core\Resource\File::FILETYPE_AUDIO => [
                            'showitem' => '
                            --palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                            --palette--;;filePalette'
                        ],
                        \TYPO3\CMS\Core\Resource\File::FILETYPE_VIDEO => [
                            'showitem' => '
                            --palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                            --palette--;;filePalette'
                        ],
                        \TYPO3\CMS\Core\Resource\File::FILETYPE_APPLICATION => [
                            'showitem' => '
                            --palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                            --palette--;;filePalette'
                        ]
                    ],
                    'foreign_match_fields' => [
                        'fieldname' => 'poster_image',
                        'tablenames' => 'tx_nsevent_domain_model_events',
                    ],
                    'maxitems' => 1
                ],
                $GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext']
            ),

        ],
        'description' => [
            'exclude' => true,
            'label' => 'LLL:EXT:ns_event/Resources/Private/Language/locallang_db.xlf:tx_nsevent_domain_model_events.description',
            'config' => [
                'type' => 'text',
                'enableRichtext' => true,
                'richtextConfiguration' => 'default',
                'fieldControl' => [
                    'fullScreenRichtext' => [
                        'disabled' => false,
                    ],
                ],
                'cols' => 40,
                'rows' => 15,
                'eval' => 'trim',
            ],

        ],
        'address' => [
            'exclude' => true,
            'label' => 'LLL:EXT:ns_event/Resources/Private/Language/locallang_db.xlf:tx_nsevent_domain_model_events.address',
            'config' => [
                'type' => 'text',
                'cols' => 40,
                'rows' => 5,
                'eval' => 'trim'
            ]
        ],
    ],
];
