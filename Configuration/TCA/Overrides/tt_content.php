<?php

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;


call_user_func(function($extensionKey, $table)
{
    $contentTypeName = 'watchwords_list';

    $pluginSignature = ExtensionUtility::registerPlugin(
        'Watchwords',
        'Watch',
        'Watchword List',
        'EXT:watchwords/Resources/Public/Icons/Extension.svg'
    );
    $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist'][$pluginSignature]
    = 'pi_flexform';
    ExtensionManagementUtility::addPiFlexFormValue(
        $pluginSignature,
        'FILE:EXT:' . $extensionKey . '/Configuration/FlexForms/Watch.xml',
    );

    // Configure element type
    $GLOBALS['TCA']['tt_content']['types'][$contentTypeName] =
        [
            'showitem' => '
            --div--;General,
            --palette--;General;general,
            --palette--;Headers;headers,
            tx_watchwords_list,
            --div--;Options,
            pi_flexform'
        ];
}, 'watchwords', basename(__FILE__, '.php'));

