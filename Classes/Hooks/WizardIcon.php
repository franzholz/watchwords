<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2004-2005 David Bruehlmeier (typo3@bruehlmeier.com)
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
* Class that adds the wizard icon.
*
* @author David Bruehlmeier <typo3@bruehlmeier.com>
*/



class tx_watchwords_pi1_wizicon {
	public function proc($wizardItems) {
		$LL = $this->includeLocalLang();

		$wizardItems['plugins_tx_watchwords_pi1'] = array(
		'icon' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath('watchwords') . 'pi1/ce_wiz.gif',
			'title' => $GLOBALS['LANG']->getLLL('pi1_title', $LL),
			'description' => $GLOBALS['LANG']->getLLL('pi1_plus_wiz_description', $LL),
			'params' => '&defVals[tt_content][CType]=list&defVals[tt_content][list_type]=watchwords_pi1' );

		return $wizardItems;
	}

	public function includeLocalLang () {
		include(\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('watchwords') . 'locallang.xlf');
		return $LOCAL_LANG;
	}
}


if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/watchwords/pi1/class.tx_watchwords_pi1_wizicon.php']) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/watchwords/pi1/class.tx_watchwords_pi1_wizicon.php']);
}

