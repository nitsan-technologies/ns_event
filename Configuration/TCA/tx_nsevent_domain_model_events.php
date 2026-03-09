<?php

use TYPO3\CMS\Core\Resource\File;
use TYPO3\CMS\Core\Resource\FileType;

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

if ($typo3VersionArray['version_main'] >= 14) {
    $imageSettingsFalMedia = [
        'exclude' => true,
        'label' => 'LLL:EXT:ns_event/Resources/Private/Language/locallang_db.xlf:tx_nsevent_domain_model_events.poster_image',
       'config' => [
                'maxitems' => 1,
                'type' => 'file',
                'allowed' => 'common-image-types',
       ],
        'behaviour' => [
            'allowLanguageSynchronization' => true,
        ],
        'appearance' => [
            'createNewRelationLinkTitle' => 'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:images.addFileReference',
            'enabledControls' => [
                'hide' => false,
            ]
        ],
        'overrideChildTca' => [
            'types' => [
                FileType::TEXT->value => [
                    'showitem' => '
                    --palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                    --palette--;;filePalette'
                ],
                FileType::IMAGE->value => [
                    'showitem' => '
                    --palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                    --palette--;;filePalette'
                ],
                FileType::AUDIO->value => [
                    'showitem' => '
                    --palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                    --palette--;;filePalette'
                ],
                FileType::VIDEO->value => [
                    'showitem' => '
                    --palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                    --palette--;;filePalette'
                ],
                FileType::APPLICATION->value => [
                    'showitem' => '
                    --palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                    --palette--;;filePalette'
                ],
            ],
        ],
    ];
} else {
    $imageSettingsFalMedia = [
        'exclude' => true,
        'label' => 'LLL:EXT:ns_event/Resources/Private/Language/locallang_db.xlf:tx_nsevent_domain_model_events.poster_image',
       'config' => [
                'maxitems' => 1,
                'type' => 'file',
                'allowed' => 'common-image-types',
       ],
        'behaviour' => [
            'allowLanguageSynchronization' => true,
        ],
        'appearance' => [
            'createNewRelationLinkTitle' => 'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:images.addFileReference',
            'enabledControls' => [
                'hide' => false,
            ]
        ],
        'overrideChildTca' => [
            'types' => [
                File::FILETYPE_TEXT => [
                    'showitem' => '
                    --palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                    --palette--;;filePalette'
                ],
                File::FILETYPE_IMAGE => [
                    'showitem' => '
                    --palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                    --palette--;;filePalette'
                ],
                File::FILETYPE_AUDIO => [
                    'showitem' => '
                    --palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                    --palette--;;filePalette'
                ],
                File::FILETYPE_VIDEO => [
                    'showitem' => '
                    --palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                    --palette--;;filePalette'
                ],
                File::FILETYPE_APPLICATION => [
                    'showitem' => '
                    --palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                    --palette--;;filePalette'
                ],
            ],
        ],
    ];
}

if (version_compare((string)$typo3VersionArray['version_main'], '11', '<=')) {
    $startDate =[
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
    ];
}
else{
    $startDate =[
        'exclude' => true,
        'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.starttime',
        'config' => [
            'type' => 'datetime',
            'default' => 0,
            'behaviour' => [
                'allowLanguageSynchronization' => true
            ]
        ],
    ];
}

if (version_compare((string)$typo3VersionArray['version_main'], '11', '<=')) {
    $endDate =[
        'exclude' => true,
        'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.endtime',
        'config' => [
            'type' => 'input',
            'renderType' => 'inputDateTime',
            'eval' => 'datetime,int',
            'default' => 0,
            'behaviour' => [
                'allowLanguageSynchronization' => true
            ]
        ],
    ];
}
else{
    $endDate =[
        'exclude' => true,
        'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.endtime',
        'config' => [
            'type' => 'datetime',
            'default' => 0,
            'behaviour' => [
                'allowLanguageSynchronization' => true
            ]
        ],
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
       'starttime' => $startDate,
       'endtime' => $endDate,

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
       'poster_image' => $imageSettingsFalMedia,

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
