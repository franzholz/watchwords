<?php
defined('TYPO3') || die('Access denied.');

$extensionKey = 'watchwords';

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
    $extensionKey,
    'Configuration/TypoScript',
    'Display daily Christian Watchwords'
);
