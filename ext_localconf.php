<?php

defined('TYPO3_MODE') || defined('TYPO3') || die();

$typo3VersionArray = \TYPO3\CMS\Core\Utility\VersionNumberUtility::convertVersionStringToArray(
    \TYPO3\CMS\Core\Utility\VersionNumberUtility::getCurrentTypo3Version()
);
if (version_compare((string)$typo3VersionArray['version_main'], '9', '==')) {
    $extName = 'NITSAN.NsEvent';
    $eventsClass = 'Events';
} else {
    $extName = 'NsEvent';
    $eventsClass = \NITSAN\NsEvent\Controller\EventsController::class;
}



\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    $extName,
    'Pi1',
    [
        $eventsClass => 'list',
    ],
    // non-cacheable actions
    [
        $eventsClass => 'list',
    ]
);
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    $extName,
    'Pi2',
    [
        $eventsClass => 'show',
    ],
    // non-cacheable actions
    [
        $eventsClass => 'show',
    ]
);
$iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Imaging\IconRegistry::class);
$icons = [
    'ns_event-plugin-pi1'
    , 'ns_event-plugin-pi2'
];

foreach ($icons as $icon) {
    $iconRegistry->registerIcon(
        $icon,
        \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
        ['source' => 'EXT:ns_event/Resources/Public/Icons/PluginIcon.svg']
    );
}
