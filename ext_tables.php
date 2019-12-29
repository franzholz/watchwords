<?php
defined('TYPO3_MODE') || die('Access denied.');

if (
    TYPO3_MODE == 'BE'
) {
     $GLOBALS['TBE_MODULES_EXT']['xMOD_db_new_content_el']['addElClasses']['JambageCom\\Watchwords\\Hooks\\WizardIcon'] = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath(WATCHWORDS_EXT) . 'Classes/Hooks/WizardIcon.php';
}

