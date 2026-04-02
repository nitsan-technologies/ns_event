<?php
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;
defined('TYPO3') || die('Access denied');


    $extName = 'NsEvent';
    $eventsClass = \NITSAN\NsEvent\Controller\EventsController::class;
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    $extName,
    'Pi1',
    [
        $eventsClass => 'list',
    ],
    // non-cacheable actions
    [
        $eventsClass => 'list',
    ],
    ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
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
    ],
    ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
);
