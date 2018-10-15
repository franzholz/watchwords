<?php
if (!defined ('TYPO3_MODE')) {
    die ('Access denied.');
}

define('WATCHWORDS_EXT', 'watchwords');

    // Add the extension as a (cached) plugin to the standard template content (default), UID=43
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPItoST43(
    WATCHWORDS_EXT,
    'Classes/Plugin/Watchwords.php',
    '_pi1',
    'list_type',
    1
);

    // Activate hook to determine if the page cache needs to be reloaded
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tslib/class.tslib_fe.php']['headerNoCache'][] = 'JambageCom\\Watchwords\\Hooks\\PageCacheHook->headerNoCache';

