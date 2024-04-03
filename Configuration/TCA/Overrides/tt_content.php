<?php

defined('TYPO3_MODE') || defined('TYPO3') || die();

$extName = 'NsEvent';

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    $extName,
    'Pi1',
    'NS Event: List View'
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    $extName,
    'Pi2',
    'NS Event: Detail View'
);

$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist']['nsevent_pi1'] = 'pi_flexform';
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist']['nsevent_pi1'] = 'recursive,select_key';
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist']['nsevent_pi2'] = 'recursive,select_key,pages,xmlTitle';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue('nsevent_pi1', 'FILE:EXT:ns_event/Configuration/FlexForms/flexform_event.xml');
