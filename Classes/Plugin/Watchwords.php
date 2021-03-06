<?php

namespace JambageCom\Watchwords\Plugin;

/***************************************************************
*  Copyright notice
*
*  (c) 2018 David Bruehlmeier (typo3@bruehlmeier.com)
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


use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class for the functions of the watchwords-extension.
 * This extension adds a new content element which will get a new christian watchword every day.
 *
 * @author David Bruehlmeier <typo3@bruehlmeier.com>
 * @package TYPO3
 * @subpackage tx_watchwords
 */
class Watchwords extends \TYPO3\CMS\Frontend\Plugin\AbstractPlugin
{
    public $prefixId = 'tx_watchwords_pi1';
    public $scriptRelPath = 'Classes/Plugin/Watchwords.php';	// Path to this script relative to the extension dir.
    public $extKey = WATCHWORDS_EXT;// The extension key.
    public $extConf = '';       // TS-configuration
    public $biblegatewayCom = 'http://www.biblegateway.com/usage/votd/rss/votd.rdf';	// URL of biblegateway.com


    /**
    * This is the main function
    *
    * @param	string		$content: The normal content-variable. Not used.
    * @param	array		$conf: TS-Configuration array
    * @return	string		Returns the watchword(s)
    */
    public function main ($content, $conf)
    {
        $this->conf = $conf;
        $this->pi_setPiVarDefaults();
        $this->pi_loadLL();

            // Get the TypoScript of the extension and initialize FlexForms
        $this->extConf = $conf;
        $this->pi_initPIflexForm();

            // Get the extension configuration
        $this->confVars = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['watchwords']);

            // Get the watchwords
        $content = $this->getWatchwordsFromBiblegateway();
            // Return the content
        return $this->pi_wrapInBaseClass($content);
    }

    public function getWatchwordsFromBiblegateway ()
    {
        /** @var \TYPO3\CMS\Core\Charset\CharsetConverter $charsetConverter */
        $charsetConverter = GeneralUtility::makeInstance(\TYPO3\CMS\Core\Charset\CharsetConverter::class);

        $report = '';
            // Get the watchwords
        $result = $this->getWatchwords($report);
        $xmlString = $result['xml'];
        $charset = $result['charset'];
            // If no watchwords were fetched, return the standard output
        if (!$xmlString) return $this->standardOutput($report['message']);

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
        foreach ($out as $k => $v) {
            $v = $charsetConverter->conv(trim($v), $charsetConverter->parse_charset($charset), 'utf-8');
            $out[$k] = $this->cObj->stdWrap($v, $this->extConf[$k . '.']);
        }

            // Use templateFile or output without templateFile, according to TypoScript settings
        $content = '';
        if ($this->extConf['templateFileBiblegateway']) {
            $templateService = GeneralUtility::makeInstance(\TYPO3\CMS\Core\Service\MarkerBasedTemplateService::class);

            $incFile = $GLOBALS['TSFE']->tmpl->getFileName($this->extConf['templateFileBiblegateway']);
            $template = file_get_contents($incFile);
            $globalMarkerArray = array();
            $globalMarkerArray['###DATE###'] = $out['date'];
            $globalMarkerArray['###VERSE###'] = $out['verse'];
            $globalMarkerArray['###BIBLE_LINK###'] = $out['bibleLink'];
            $globalMarkerArray['###VERSE_SOURCE###'] = $out['verseSource'];
            $globalMarkerArray['###COPYRIGHT###'] = $out['copyright'];
            $globalMarkerArray['###LICENSE###'] = $out['license'];
            $content =
                $templateService->substituteMarkerArray(
                    $template,
                    $globalMarkerArray
                );
        } else {
                // Concatenate the strings. Cannot use foreach() here because $out is not in the proper order when using PHP4
            if ($this->extConf['date'])
                $content .= $out['date'];
            if ($this->extConf['verse'])
                $content .= $out['verse'];
            if ($this->extConf['bibleLink'])
                $content .= $out['bibleLink'];
            if ($this->extConf['verseSource'])
                $content .= $out['verseSource'];
            if ($this->extConf['copyright'])
                $content .= $out['copyright'];
                // license url MUST always be available
            $content .= $out['license'];
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
    public function getLanguage ()
    {
        $language = '';

        $piLang =
            $this->pi_getFFvalue(
                $this->cObj->data['pi_flexform'],
                'tx_watchwords_language',
                'sDEF'
            );
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
    public function getFetchDate ()
    {
        $fetchDate = '';

        $dateOffset =
            $this->pi_getFFvalue(
                $this->cObj->data['pi_flexform'],
                'tx_watchwords_date_offset',
                'sDEF'
            );
        if ($dateOffset) {
            $fetchDate = mktime(0, 0, 0) + (86400 * $dateOffset);
        } elseif ($this->extConf['dateOffset']) {
            $fetchDate = mktime(0, 0, 0) + (86400 * $this->extConf['dateOffset']);
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
    public function getBibleVersion ()
    {
        $bibleVersion = 31;	// New International version

        $piBibleVersion =
            $this->pi_getFFvalue(
                $this->cObj->data['pi_flexform'],
                'tx_watchwords_bible_version',
                'sDEF'
            );

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
    * @param array $report Error code/message
    *
    * @return	array		Watchwords as array of encoding and XML string
    */
    public function getWatchwords (&$report = null)
    {
        $xmlString = '';

        $language = $this->getLanguage();
        $fetchDate = $this->getFetchDate();
        $bibleVersion = $this->getBibleVersion();
        $hashKey = md5('tx_watchwords_storeKey:' . serialize(array($language, $fetchDate, $bibleVersion)));
        
        // see if there is a cached XML
        $cachedXmlString = null;
        /** @var \TYPO3\CMS\Core\Cache\Frontend\FrontendInterface $contentHashCache */
        $contentHashCache = GeneralUtility::makeInstance(\TYPO3\CMS\Core\Cache\CacheManager::class)->getCache('cache_hash');
        $cacheEntry = $contentHashCache->get($hashKey);
        if ($cacheEntry) {
            $cachedXmlString = $cacheEntry;
        }

        if ($cachedXmlString) {
            return ['charset' => 'utf-8', 'xml' => $cachedXmlString];
        }

        if ($this->extConf['testFile']) {
            $xmlString = GeneralUtility::getURL($this->extConf['testFile']);
        } else {
            $accept = array(
                'type' => array('application/rss+xml', 'application/xml', 'application/rdf+xml', 'text/xml'),
                'charset' => array_diff(mb_list_encodings(), array('pass', 'auto', 'wchar', 'byte2be', 'byte2le', 'byte4be', 'byte4le', 'BASE64', 'UUENCODE', 'HTML-ENTITIES', 'Quoted-Printable', '7bit', '8bit'))
            );

            $urlParams = $bibleVersion ? '?' . $bibleVersion : '';
            $url = $this->biblegatewayCom . $urlParams;
            $report = [];
            $xmlString = GeneralUtility::getURL($url, 1, null, $report);
            $offset = strpos($xmlString, "\r\n\r\n");
            $header = substr($xmlString, 0, $offset);
            $match = null;

            if (!$header || !preg_match('/^Content-Type:\s+([^;]+)(?:;\s*charset=(.*))?/im', $header, $match)) {
                // error parsing the response
            } else {
                if (!in_array(strtolower($match[1]), array_map('strtolower', $accept['type']))) {
                    // type not accepted
                }
                $encoding = trim($match[2], '"\'');
            }

            $xmlString = substr($xmlString, $offset + 4);

            if (!$encoding) {
                if (preg_match('/^<\?xml\s+version=(?:"[^"]*"|\'[^\']*\')\s+encoding=("[^"]*"|\'[^\']*\')/s', $xmlString, $match)) {
                    $encoding = trim($match[1], '"\'');
                }
            }

            // if the xml is fetched from remote, store it in the cache
            if ($xmlString != '') {
                $ident = 'tx_watchwords';
                $contentHashCache->set($hashKey, $xmlString, ['ident_' . $ident], (int) 0);
            }
        }

        return ['charset' => $encoding, 'xml' => $xmlString];
    }

    /**
    * Gets the standard output as defined by TypoScript.
    * @param string $message Error message
    *
    * @return	string		Standard output
    */
    public function standardOutput ($message)
    {
        $out =
            $this->cObj->stdWrap(
                $this->extConf['standard'] . ' ' . $message,
                $this->extConf['standard.']
            );
        return $this->pi_wrapInBaseClass($out);
    }
}

