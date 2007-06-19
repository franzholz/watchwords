<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2005 David Bruehlmeier (typo3@bruehlmeier.com)
*  All rights reserved
*
*  This script is part of the Typo3 project. The Typo3 project is
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
 * Class for migrating watchwords content-elements to flexforms
 *
 * @author  David Bruehlmeier <typo3@bruehlmeier.com>
 * @package TYPO3
 * @subpackage tx_watchwords
 */
class ext_update {

	/**
	 * Main function, returning the HTML content of the module
	 *
	 * @return	string		HTML
	 */
	function main() {
		$out = '';
		$records = $this->getRecords();

		if (!t3lib_div::GPvar('do_update')) {
			$onClick = "document.location='".t3lib_div::linkThisScript(array('do_update' => 1))."'; return false;";
			$out = 'There are '.count($records).' watchword plugins found on tt_content which need to be updated to use FlexFoms.<br />';
			$out.= $GLOBALS['TBE_TEMPLATE']->spacer(10);
			$out.= 'Do you want to perform the action now?<br />';
			$out.= '(This action will <span class="typo3-red">not</span> change your old data in the tt_content table. So even if you perform this action, you will still be able to downgrade to an earlier version of watchwords retaining the old settings.)';
			$out.= $GLOBALS['TBE_TEMPLATE']->spacer(5);
			$out.= '<form action=""><input type="submit" value="UPDATE" onclick="'.htmlspecialchars($onClick).'"></form>';
		} else {
			
			$charset = $GLOBALS['TYPO3_CONF_VARS']['BE']['forceCharset'] ? $GLOBALS['TYPO3_CONF_VARS']['BE']['forceCharset'] : 'iso-8859-1';

			foreach ($records as $theRecord)		{
				$fields_values['pi_flexform'] = trim('
<?xml version="1.0" encoding="'.$charset.'" standalone="yes" ?>
<T3FlexForms>
    <data type="array">
        <sDEF type="array">
            <lDEF type="array">
                <tx_watchwords_language type="array">
                    <vDEF>'.strtolower($theRecord['tx_watchwords_language']).'</vDEF>
                </tx_watchwords_language>
                <tx_watchwords_date_offset type="array">
                    <vDEF>'.$theRecord['tx_watchwords_date_offset'].'</vDEF>
                </tx_watchwords_date_offset>
            </lDEF>
        </sDEF>
    </data>
</T3FlexForms>				
				');
				$GLOBALS['TYPO3_DB']->exec_UPDATEquery('tt_content','uid='.$theRecord['uid'],$fields_values);
				$GLOBALS['TYPO3_DB']->debug('ext_update->main');
				$out.= 'Content Element "'.$theRecord['header'].'" (UID '.$theRecord['uid'].') successfully migrated.<br />';
			}

			$out.= '<br />Done.';
		}

		return $out;
	}

	/**
	 * Checks if the update function needs to be available at all. It will only be available if there are
	 * watchwords content-elements with no flexform entry.
	 *
	 * @return	boolean
	 */
	function access() {
		$out = 0;

		$records = $this->getRecords();
		if (is_array($records)) $out = 1;

		return $out;
	}


	/**
	 * Gets all watchword content-elements without flexform entry.
	 *
	 * @return	array		All records from tt_content with a watchword plugin.
	 */
	function getRecords()		{
		$out = '';
		
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('*', 'tt_content', 'list_type="watchwords_pi1" AND pi_flexform=""');
		if ($res)		{
			while ($rec = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res))		{
				$out[$rec['uid']] = $rec;
			}
		}

		return $out;
	}
}


if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/watchwords/class.ext_update.php']) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/watchwords/class.ext_update.php']);
}

?>
