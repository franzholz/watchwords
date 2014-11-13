<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2005 David Bruehlmeier (typo3@bruehlmeier.com)
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
* Class with method called as hook in tslib_fe
*
* @author David Bruehlmeier <typo3@bruehlmeier.com>
*/

class tx_watchwords_tslibfe {

	/**
	 * Checks if the current page contains at least one active watchwords plugin. If so, the cached page must be from today, otherwise
	 * old watchwords might be displayed from the cache. So if the cached page is NOT from today, the cached is forced
	 * to be reloaded.
	 *
	 * @param	array		$params: The current parameters, passed by reference
	 * @param	object		$reference: The current cObj, passed by reference
	 * @return	void		Nothing returned. $params['disableAcquireCacheData'] is directly changed, as it is passed by reference
	 */
	function headerNoCache(&$params, &$reference) {

			// Check if the current page contains a watchwords plugin
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
			'uid,pid,list_type',
			'tt_content',
			'pid=' . $reference->id . ' AND list_type=\'watchwords_pi1\'' . $reference->sys_page->enableFields('tt_content'),
			'',
			'',
			'1'
		);

		if ($res) {
				// Get the cache of the current page
				$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('id,page_id,tstamp', 'cache_pages', 'page_id=' . $reference->id);
				if ($res) {
						$rec = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);

						// If the cached page is not from today, force to reload the cache
						$today = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
						if ($rec['tstamp'] < $today) $params['disableAcquireCacheData'] = TRUE;
				}
		}
	}
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/watchwords/inc/class.tx_watchwords_tslibfe.php']) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/watchwords/inc/class.tx_watchwords_tslibfe.php']);
}

?>