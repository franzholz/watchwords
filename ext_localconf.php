<?php
defined('TYPO3_MODE') || die('Access denied.');

call_user_func(function () {
    define('WATCHWORDS_EXT', 'watchwords');

        // Add the extension as a (cached) plugin to the standard template content (default), UID=43
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPItoST43(
        WATCHWORDS_EXT,
        'class.tx_watchwords_pi1.php',
        '_pi1',
        'list_type',
        true
    );

        // Activate hook to determine if the page cache needs to be reloaded
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tslib/class.tslib_fe.php']['headerNoCache'][] = 'JambageCom\\Watchwords\\Hooks\\PageCacheHook->headerNoCache';
});

