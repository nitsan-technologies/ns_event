<?php

defined('TYPO3_MODE') || defined('TYPO3') || die();

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig(
    '<INCLUDE_TYPOSCRIPT: source="FILE:EXT:ns_event/Configuration/TSconfig/ContentElementWizard.tsconfig">'
);
