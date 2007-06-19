<?php
if (!defined ('TYPO3_MODE')) 	die ('Access denied.');

	// Add the extension as a (cached) plugin to the standard template content (default), UID=43
t3lib_extMgm::addPItoST43($_EXTKEY,'pi1/class.tx_watchwords_pi1.php','_pi1','list_type',1);

	// Activate hook to determine if the page cache needs to be reloaded
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tslib/class.tslib_fe.php']['headerNoCache'][] = 'EXT:watchwords/inc/class.tx_watchwords_tslibfe.php:tx_watchwords_tslibfe->headerNoCache';

?>
