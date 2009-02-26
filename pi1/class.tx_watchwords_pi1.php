<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2004-2009 David Bruehlmeier (typo3@bruehlmeier.com)
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
* Plugin 'Display Watchword' for the 'watchwords' extension.
*
* @author David Bruehlmeier <typo3@bruehlmeier.com>
*/


require_once(PATH_tslib.'class.tslib_pibase.php');

/**
 * Class for the functions of the watchwords-extension.
 * This extension adds a new content element which will get a new christian watchword every day.
 *
 * @author David Bruehlmeier <typo3@bruehlmeier.com>
 * @package TYPO3
 * @subpackage tx_watchwords
 */
class tx_watchwords_pi1 extends tslib_pibase {
	var $prefixId = 'tx_watchwords_pi1';						// Same as class name
	var $scriptRelPath = 'pi1/class.tx_watchwords_pi1.php';		// Path to this script relative to the extension dir.
	var $extKey = 'watchwords';									// The extension key.
	var $extConf = '';											// TS-configuration
	
	var $biblegateway_com = 'http://www.biblegateway.com/usage/votd/rss/votd.rdf';	// URL of biblegateway.com
	

	/**
	 * This is the main function
	 *
	 * @param	string		$content: The normal content-variable. Not used.
	 * @param	array		$conf: TS-Configuration array
	 * @return	string		Returns the watchword(s)
	*/
	function main($content, $conf) {
		$this->conf = $conf;
		$this->pi_setPiVarDefaults();
		$this->pi_loadLL();

			// Get the TypoScript of the extension and initialize FlexForms
		$this->extConf = $GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_watchwords_pi1.'];
		$this->pi_initPIflexForm();
		
			// Get the extension configuration
		$this->confVars = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['watchwords']);
		
			// Get the watchwords
		$content = $this->getWatchwordsFromBiblegateway();

			// Return the content
		return $this->pi_wrapInBaseClass($content);
	}
	
	function getWatchwordsFromBiblegateway() {
		
			// Get the watchwords
		$xmlString = $this->getWatchwords();
		
			// If no watchwords were fetched, return the standard output
		if (!$xmlString) return $this->standardOutput();

			// Parse the XML
		$xml = simplexml_load_string($xmlString);
		$out['copyright'] = $xml->channel->title;
		$out['license'] = $xml->channel->link;
		$out['bibleLink'] = $xml->channel->item->guid;
		$out['verseSource'] = $xml->channel->item->title;
		
			// Yes, this is ugly... but it works! :-)
		$split1 = explode('&ldquo;', $xmlString);
		$split2 = explode('&rdquo;', $split1[1]);
		$out['verse'] = $split2[0];
		
			// Additional fields
		$out['date'] = time();
		
			// Trim, convert charset to metaCharset (conversion to the output charset will be done by the core)
			// and apply stdWrap to all values
		foreach ($out as $k=>$v)		{
			$v = $GLOBALS['TSFE']->csConv(trim($v), 'utf-8');
			$out[$k] = $this->cObj->stdWrap($v, $this->extConf[$k.'.']);
		}
	
			// Use templateFile or output without templateFile, according to TypoScript settings
		$content = '';
		if ($this->extConf['templateFileBiblegateway']) {
			$template = $this->cObj->fileResource($this->extConf['templateFileBiblegateway']);
			$globalMarkerArray = array();
			$globalMarkerArray['###DATE###']   			= $out['date'];
			$globalMarkerArray['###VERSE###']    		= $out['verse'];
			$globalMarkerArray['###BIBLE_LINK###']   	= $out['bibleLink'];
			$globalMarkerArray['###VERSE_SOURCE###']    = $out['verseSource'];
			$globalMarkerArray['###COPYRIGHT###'] 		= $out['copyright'];
			$globalMarkerArray['###LICENSE###']   		= $out['license'];
			$content = $this->cObj->substituteMarkerArray($template, $globalMarkerArray);
		} else {
				// Concatenate the strings. Cannot use foreach() here because $out is not in the proper order when using PHP4
			if ($this->extConf['date'])					$content.= $out['date'];
			if ($this->extConf['verse'])				$content.= $out['verse'];
			if ($this->extConf['bibleLink'])			$content.= $out['bibleLink'];
			if ($this->extConf['verseSource'])			$content.= $out['verseSource'];
			if ($this->extConf['copyright'])			$content.= $out['copyright'];
            	// license url MUST always be available
			$content.= $out['license'];
		}
						
		return $content;
	}
	
	
	/**
	 * Determines the language in which to fetch the watchwords in the following order:
	 *  - Prio 1: Language from the current cObj
	 *  - Prio 2: Language from the TypoScript of the extension
	 *  - Prio 3: Language for the current site
	 *  - Prio 4: Default language (English)
	 *
	 * @return	string		Language in which to get the language (2-letter ISO code)
	*/
	function getLanguage()		{
		$language = '';

		$piLang = $this->pi_getFFvalue($this->cObj->data['pi_flexform'], 'tx_watchwords_language', 'sDEF');
		if ($piLang) {
			$language = $piLang;
		} elseif ($this->extConf['language']) {
			$language = $this->extConf['language'];
		} elseif ($GLOBALS['TSFE']->tmpl->setup['config.']['language']) {
			$language = $GLOBALS['TSFE']->tmpl->setup['config.']['language'];
		} else {
			$language = 'en';
		}

			// Mapping TYPO3-language to ISO
		$language = $GLOBALS['TSFE']->csConvObj->isoArray[$language] ? $GLOBALS['TSFE']->csConvObj->isoArray[$language] : $language;
		
		return $language;
	}
	
	
	/**
	 * Determines the date for which to fetch the watchwords in the following order
	 *  - Prio 1: Date Offset from the current cObj
	 *  - Prio 2: Date Offset from the TypoScript of the extension
	 *  - Prio 3: No Date Offset found, take current date
	 *
	 * @return	integer		Date for which to get the watchwords (UNIX-timestamp)
	*/
	function getFetchDate()		{
		$fetchDate = '';

		$dateOffset = $this->pi_getFFvalue($this->cObj->data['pi_flexform'], 'tx_watchwords_date_offset', 'sDEF');
		if ($dateOffset) {
			$fetchDate = mktime(0, 0, 0)+(86400 * $dateOffset);
		} elseif ($this->extConf['dateOffset']) {
			$fetchDate = mktime(0, 0, 0)+(86400 * $this->extConf['dateOffset']);
		} else {
			$fetchDate = mktime(0, 0, 0);
		}
		
		return $fetchDate;
	}
	
	/**
	 * Determines the bible version (only for biblegateway.com) for which to fetch the watchwords in the following order
	 *  - Prio 1: Bible version from the current cObj
	 *  - Prio 2: Bible version from the TypoScript of the extension
	 *  - Prio 3: No Bible version found, take New International version (31)
	 *
	 * @return	integer		Bible version for which to get the watchwords
	*/
	function getBibleVersion()		{
		$bibleVersion = 31;	// New International version

		$piBibleVersion = $this->pi_getFFvalue($this->cObj->data['pi_flexform'], 'tx_watchwords_bible_version', 'sDEF');
		if ($piBibleVersion) {
			$bibleVersion = $piBibleVersion;
		} elseif ($this->extConf['bibleVersion']) {
			$bibleVersion = $this->extConf['bibleVersion'];
		}

		return $bibleVersion;
	}
	
	/**
	 * Get the watchwords in the following order
	 *  - Prio 1: First see if there is watchwords for this day and language cached already
	 *  - Prio 2: Use the testFile
	 *  - Prio 3: Get the XML-File through HTTP
	 *
	 * @return	string		Watchwords as an XML string
	*/
	function getWatchwords()		{
		$xmlString = '';
		
		$language = $this->getLanguage();
		$fetchDate = $this->getFetchDate();
		$bibleVersion = $this->getBibleVersion();

		$hashKey = md5('tx_watchwords_storeKey:'.serialize(array($language, $fetchDate, $bibleVersion)));
		// see if there is a cached XML 
		$cachedXmlString = $GLOBALS['TSFE']->sys_page->getHash($hashKey, 0);
		if ($cachedXmlString) {
			return $cachedXmlString;
		}

		if ($this->extConf['testFile']) {
			$xmlString = t3lib_div::getURL($this->extConf['testFile']);
		} else {
			
			$urlParams = $bibleVersion ? '?'.$bibleVersion : '';
			$url = $this->biblegateway_com.$urlParams;
			$xmlString = t3lib_div::getURL($url);

			// if the xml is fetched from remote, store it in the cache
			if ($xmlString)	{
				$GLOBALS['TSFE']->sys_page->storeHash($hashKey, $xmlString, 'tx_watchwords');
			}
		}

		return $xmlString;
	}
	
	/**
	 * Gets the standard output as defined by TypoScript.
	 *
	 * @return	string		Standard output
	*/
	function standardOutput()		{
		$out = $this->cObj->stdWrap($this->extConf['standard'], $this->extConf['standard.']);
		return $this->pi_wrapInBaseClass($out);
	}
}


if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/watchwords/pi1/class.tx_watchwords_pi1.php']) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/watchwords/pi1/class.tx_watchwords_pi1.php']);
}

?>
