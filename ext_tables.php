<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2004-2014 David Bruehlmeier (typo3@bruehlmeier.com)
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/
/**
 * Tables definition for the 'watchwords'-extension
 *
 * @author David Bruehlmeier <typo3@bruehlmeier.com>
 */

if (!defined ('TYPO3_MODE')) {
	die ('Access denied.');
}

$typoVersion = '';

if (t3lib_extMgm::isLoaded('div2007')) {
	$typoVersion = tx_div2007_core::getTypoVersion();
}

// *************************************
// *** Add FE Plugin
// *************************************

if (TYPO3_MODE == 'BE') {
		// Add the frontend content-element
	$TBE_MODULES_EXT['xMOD_db_new_content_el']['addElClasses']['tx_watchwords_pi1_wizicon'] = t3lib_extMgm::extPath($_EXTKEY) . 'pi1/class.tx_watchwords_pi1_wizicon.php';
}


// *************************************
// *** Frontend-Related
// *************************************

	// Including the FE-Plugin
t3lib_extMgm::addPlugin(Array('LLL:EXT:watchwords/locallang.php:tt_content.list_type_pi1', $_EXTKEY . '_pi1'), 'list_type');


// *************************************
// *** Addition to tt_content
// *************************************

if (
	$typoVersion < '6001000'
) {
		// Add FlexForm field to tt_content
	t3lib_div::loadTCA('tt_content');
}

t3lib_extMgm::addPiFlexFormValue($_EXTKEY . '_pi1', 'FILE:EXT:watchwords/flexform_ds_biblegateway.xml');


$TCA['tt_content']['types']['list']['subtypes_addlist'][$_EXTKEY . '_pi1'] = 'pi_flexform';
$TCA['tt_content']['types']['list']['subtypes_excludelist'][$_EXTKEY . '_pi1'] = 'layout,select_key,pages,recursive';

?>