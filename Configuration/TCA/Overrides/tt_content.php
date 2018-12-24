<?php
defined('TYPO3_MODE') or die('Access denied.');

$table = 'tt_content';
$listType = WATCHWORDS_EXT . '_pi1';

// *************************************
// *** Add FE Plugin
// *************************************
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPlugin(
    array(
        'LLL:EXT:' . WATCHWORDS_EXT . '/locallang.xlf:tt_content.list_type_pi1',
        $listType,
        'LLL:EXT:' . WATCHWORDS_EXT . '/Resources/Public/Icons/watchwords.gif',
    ),
    'list_type',
    WATCHWORDS_EXT
);

    // Add FlexForm field to tt_content
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
    $listType,  'FILE:EXT:' . WATCHWORDS_EXT . '/flexform_ds_biblegateway.xml'
);

$GLOBALS['TCA'][$table]['types']['list']['subtypes_addlist'][$listType] = 'pi_flexform';
$GLOBALS['TCA'][$table]['types']['list']['subtypes_excludelist'][$listType] = 'layout,select_key,pages,recursive';


