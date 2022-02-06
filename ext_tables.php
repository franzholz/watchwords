<?php
defined('TYPO3_MODE') || die('Access denied.');

call_user_func(
    function()
    {
        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
            'JambageCom.Watchwords',
            'Watch',
            'Watchword'
        );

        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
            'watchwords',
            'Configuration/TypoScript',
            'Display daily Christian Watchwords'
        );
    }
);
