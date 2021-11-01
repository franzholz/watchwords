<?php

call_user_func(
    function ()
    {
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
        'JambageCom.Watchwords',
        'Watch',
        'The Watchword List',
        'EXT:watchwords/Resources/Public/Icons/Extension.svg'
    );

    $listType = 'watchwords_watch';
    $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist'][$listType] = 'pi_flexform';
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
            $listType,
            'FILE:EXT:watchwords/Configuration/FlexForms/Watch.xml'
        );
    }
);

