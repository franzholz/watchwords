<?php

if (!defined ('TYPO3_MODE')) {
    die ('Access denied.');
}

$table = 'tt_content';


// *************************************
// *** Add FE Plugin
// *************************************
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPlugin(
    array(
        'LLL:EXT:' . WATCHWORDS_EXT . '/locallang.xlf:tt_content.list_type_pi1', WATCHWORDS_EXT . '_pi1',
        'LLL:EXT:' . WATCHWORDS_EXT . '/Resources/Public/Icons/watchwords.gif',
    ),
    'list_type',
    WATCHWORDS_EXT
);


$GLOBALS['TCA'][$table]['types']['list']['subtypes_addlist'][WATCHWORDS_EXT . '_pi1'] = 'pi_flexform';
$GLOBALS['TCA'][$table]['types']['list']['subtypes_excludelist'][WATCHWORDS_EXT . '_pi1'] = 'layout,select_key,pages,recursive';

