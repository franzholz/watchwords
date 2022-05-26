<?php

call_user_func(
    function ()
    {
        $table = 'tt_content';
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
            'FILE:EXT:watchwords/Configuration/FlexForms/Watch.xml'
        );
        $GLOBALS['TCA'][$table]['types']['list']['subtypes_excludelist'][$listType] =
            'recursive,pages';
    }
);

