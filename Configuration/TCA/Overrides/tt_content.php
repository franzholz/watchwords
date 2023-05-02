<?php

call_user_func(function($extensionKey, $table)
{
    $listType = 'watchwords_watch';

    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
        'Watchwords',
        'Watch',
        'The Watchword List',
        'EXT:watchwords/Resources/Public/Icons/Extension.svg'
    );

    $GLOBALS['TCA'][$table]['types']['list']['subtypes_addlist'][$listType] = 'pi_flexform';
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
        $listType,
        'FILE:EXT:' . $extensionKey . '/Configuration/FlexForms/Watch.xml'
    );
    $GLOBALS['TCA'][$table]['types']['list']['subtypes_excludelist'][$listType] =
        'recursive,pages';
}, 'watchwords', basename(__FILE__, '.php'));


