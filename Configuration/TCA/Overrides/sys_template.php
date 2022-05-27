<?php
defined('TYPO3_MODE') || die('Access denied.');

if (!defined ('WATCHWORDS_EXT')) {
    define('WATCHWORDS_EXT', 'watchwords');
}

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
    WATCHWORDS_EXT,
    'Configuration/TypoScript',
    'Display daily Christian Watchwords'
);
