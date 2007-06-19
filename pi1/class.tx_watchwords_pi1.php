<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2004-2006 David Bruehlmeier (typo3@bruehlmeier.com)
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
		
			// Disable caching (only for debugging)
		#$GLOBALS['TSFE']->no_cache = true;

			// Get the TypoScript of the extension and initialize FlexForms
		$this->extConf = $GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_watchwords_pi1.'];
		$this->pi_initPIflexForm();
		
			// Get the watchwords
		$language = $this->getLanguage();
		$fetchDate = $this->getFetchDate();
		$xmlString = $this->getWatchwords($language, $fetchDate);
		
			// If no watchwords were fetched, return the standard output
		if (!$xmlString) return $this->standardOutput();
		
			// Parse the XML file. Support for PHP4 left for backwards compatibility.
		$out = array();
		if (version_compare(PHP_VERSION, '5.0.0') >= 0)		{
			$out = $this->parseXML($xmlString);
		} else {
			$out = $this->parseXMLdeprecated($xmlString);
		}
				
			// If the watchwords could not be properly parsed, return the standard output
		if (!is_array($out)) return $this->standardOutput();
		
			// Get the date
		$out['date'] = $fetchDate;

			// Trim, convert charset to metaCharset (conversion to the output charset will be done by the core)
			// and apply stdWrap to all values
		foreach ($out as $k=>$v)		{
			$v = $GLOBALS['TSFE']->csConv(trim($v), 'utf-8');
			$out[$k] = $this->cObj->stdWrap($v, $this->extConf[$k.'.']);
		}

			// Use templateFile or output without templateFile, according to TypoScript settings
		$content = '';
		if ($this->extConf['templateFile']) {
			$template = $this->cObj->fileResource($this->extConf['templateFile']);
			$globalMarkerArray = array();
			$globalMarkerArray['###DATE###']      = $out['date'];
			$globalMarkerArray['###TITLE###']     = $out['title'];
			$globalMarkerArray['###OT_INT###']    = $out['oldTestamentIntro'];
			$globalMarkerArray['###OT_TXT###']    = $out['oldTestament'];
			$globalMarkerArray['###OT_SRC###']    = $out['oldTestamentSource'];
			$globalMarkerArray['###NT_INT###']    = $out['newTestamentIntro'];
			$globalMarkerArray['###NT_TXT###']    = $out['newTestament'];
			$globalMarkerArray['###NT_SRC###']    = $out['newTestamentSource'];
			$globalMarkerArray['###COPYRIGHT###'] = $out['copyright'];
			$globalMarkerArray['###LICENSE###']   = $out['license'];
			$content = $this->cObj->substituteMarkerArray($template, $globalMarkerArray);
		} else {
				// Concatenate the strings. Cannot use foreach() here because $out is not in the proper order when using PHP4
			if ($this->extConf['date'])					$content.= $out['date'];
			if ($this->extConf['title'])				$content.= $out['title'];
			if ($this->extConf['oldTestamentIntro'])	$content.= $out['oldTestamentIntro'];
			if ($this->extConf['oldTestament'])			$content.= $out['oldTestament'];
			if ($this->extConf['oldTestamentSource'])	$content.= $out['oldTestamentSource'];
			if ($this->extConf['newTestamentIntro'])	$content.= $out['newTestamentIntro'];
			if ($this->extConf['newTestament'])			$content.= $out['newTestament'];
			if ($this->extConf['newTestamentSource'])	$content.= $out['newTestamentSource'];
			if ($this->extConf['copyright'])			$content.= $out['copyright'];
            	// license url MUST always be available
			$content.= $out['license'];
		}

			// Return the content
		return $this->pi_wrapInBaseClass($content);
	}
	
	/**
	 * Determines the language in which to fetch the watchwords in the following order:
	 *  - Prio 1: Language from the current cObj
	 *  - Prio 2: Language from the TypoScript of the extension
	 *  - Prio 3: Language for the current site
	 *  - Prio 4: Default language (English)
	 *
	 * @return	string		Language in which to get the lanuage (2-letter ISO code)
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

			// Mapping TYPO3-language to ISO (as used in www.losung.de)
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
	 * Get the watchwords in the following order
	 *  - Prio 1: Use the testFile
	 *  - Prio 2: Get the XML-File through HTTP
	 *
	 * @return	string		Watchwords as an XML string
	*/
	function getWatchwords($language, $fetchDate)		{
		$xmlString = '';

		if ($this->extConf['testFile']) {
			$xmlString = t3lib_div::getURL($this->extConf['testFile']);
		} elseif ($this->extConf['url']) {
			$url = $this->extConf['url'].
				'&lang='.$language.
				'&year='.date('Y', $fetchDate).
				'&month='.date('n', $fetchDate).
				'&day='.date('j', $fetchDate);
			$xmlString = t3lib_div::getURL($url);
		}
		
		return $xmlString;
	}
	
	/**
	 * Parses the watchwords XML-string. Runs only with PHP5 or later.
	 *
	 * @param	string		$xmlString: String with watchwords XML
	 * @return	array		Array with parsed watchwords XML, encoded in UTF-8, or false if string could not be parsed
	*/
	function parseXML($xmlString)		{
		$out = array();
		
			// Parse the XML. If it cannot be properly parsed, get the standard output and return.
		$xml = simplexml_load_string($xmlString);
		if (!$xml) return false;
		
			// The texts from the old and the new testament can be more than one line and must be iterated
		foreach ($xml->OT->L as $v)		{
			$oldTestament.= $v.' ';
		}
		foreach ($xml->NT->L as $v)		{
			$newTestament.= $v.' ';
		}
		
			// Map the parsed values
		$out['title']				= $xml->TL;
		$out['oldTestamentIntro']	= $xml->OT->IL;
		$out['oldTestament']		= $oldTestament;
		$out['oldTestamentSource']	= $xml->OT->SL;
		$out['newTestamentIntro']	= $xml->NT->IL;
		$out['newTestament']		= $newTestament;
		$out['newTestamentSource']	= $xml->NT->SL;
		$out['copyright']			= $xml->XLAT;
		$out['license']				= $xml->LICENSE;
		
		return $out;
	}
	
	/**
	 * Parses the watchwords XML-string. Deprecated! Used only for backwards compatibility.
	 *
	 * @param	string		$xmlString: String with watchwords XML
	 * @return	array		Array with parsed watchwords XML, encoded in UTF-8, or false if string could not be parsed
	*/
	function parseXMLdeprecated($xmlString)		{
		$out = array();
		
			// Get the encoding of the file
		$arr = explode("'", $xmlString);
		foreach ($arr as $k=>$v)		{
			if (trim($v) == 'encoding=')		{
				$encoding = strtolower(trim($arr[$k+1]));
				break;
			}
		}
			// If it's not already in UTF-8, convert it to UTF-8, so we can parse with the same encoding all the time
		if ($encoding != 'utf-8') $xmlString = $GLOBALS['TSFE']->csConvObj->conv($xmlString, $encoding, 'utf-8');

			// Parse the XML-file as UTF-8. If no file was read, return the standard output defined by TypoScript.
		if ($xmlString) {
			$parser = xml_parser_create('UTF-8');
			xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
			xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
			xml_parse_into_struct($parser, $xmlString, $vals, $index);
			xml_parser_free($parser);
		} else {
			return false;
		}

		// Debugging: Ouput of the parsed XML-content
        /*
		echo "Values-Array:<br>";
		debug ($vals);
		echo "<br>Index-Array:<br>";
		debug ($index);
        */
        
			// 'Mini-Check' if the XML-file is valid (no real XML-validation)
		if (!($vals[$index['LOSUNGONLINE']['0']]['tag']) == 'LOSUNGONLINE') return false;

			// Get the watchword for the old testament
		$arrayOT = array_slice($vals, $index['OT']['0']+1, $index['OT']['1']-$index['OT']['0']-1);
		foreach ($arrayOT as $itemOT) {
			switch ($itemOT['tag']) {
				case 'IL':
					$out['oldTestamentIntro'] = $itemNT['value'];
				break;
				case 'L':
					$out['oldTestament'].= $itemOT['value'].' ';
				break;
				case 'SL':
					$out['oldTestamentSource'] = $itemOT['value'];
				break;
			}
		}

			// Get the watchword for the new testament
		$arrayNT = array_slice($vals, $index['NT']['0']+1, $index['NT']['1']-$index['NT']['0']-1);
		foreach ($arrayNT as $itemNT) {
			switch ($itemNT['tag']) {
				case 'IL':
					$out['newTestamentIntro'] = $itemNT['value'];
				break;
				case 'L':
					$out['newTestament'].= $itemNT['value'].' ';
				break;
				case 'SL':
					$out['newTestamentSource'] = $itemNT['value'];
				break;
			}
		}
		
			// Get the remaining values
		$out['title'] = $vals[$index['TL']['0']]['value'];
		$out['copyright'] = $vals[$index['XLAT']['0']]['value'];
		$out['license'] = $vals[$index['LICENSE']['0']]['value'];
		
		return $out;		
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
