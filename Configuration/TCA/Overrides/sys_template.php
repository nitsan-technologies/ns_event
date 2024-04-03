<?php

defined('TYPO3_MODE') || defined('TYPO3') || die();

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
    'ns_event',
    'Configuration/TypoScript',
    'NsEvent'
);
