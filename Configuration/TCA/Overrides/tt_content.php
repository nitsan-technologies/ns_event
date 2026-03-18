<?php
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
defined('TYPO3') || die('Access denied');

$plugins = [
    ['Pi1', 'NS Event: List View',    'flexform_event.xml', true],
    ['Pi2', 'NS Event: Detail View',  null,                 false],
];

$signatures = [];
foreach ($plugins as $plugin) {
    $signatures[] = ExtensionUtility::registerPlugin(
        'NsEvent',
        $plugin[0],
        $plugin[1],
        'ns_event-plugin-' . strtolower($plugin[0]),
        'plugins'
    );
}


foreach ($plugins as $index => $plugin) {
    $value = $signatures[$index];
    $hasFlexForm = $plugin[3];

    $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist'][$value] = 'recursive,select_key,pages';

    if ($hasFlexForm) {
        $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist'][$value] = 'pi_flexform';
        // @extensionScannerIgnoreLine
        ExtensionManagementUtility::addPiFlexFormValue(
            '*',
            'FILE:EXT:ns_event/Configuration/FlexForms/' . $plugin[2],
            $value
        );
        ExtensionManagementUtility::addToAllTCAtypes(
            'tt_content',
            '--div--;plugin,pi_flexform,pages',
            $value,
            'after:subheader'
        );
    } else {
        ExtensionManagementUtility::addToAllTCAtypes(
            'tt_content',
            '--div--;plugin,',
            $value,
            'after:subheader'
        );
    }
}