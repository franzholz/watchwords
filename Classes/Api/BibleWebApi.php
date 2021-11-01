<?php

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

namespace JambageCom\Watchwords\Api;

use TYPO3\CMS\Core\Utility\GeneralUtility;


class BibleWebApi extends BibleApi
{
    public $biblegatewayCom = 'http://www.biblegateway.com/usage/votd/rss/votd.rdf';	// URL of biblegateway.com

    public function getWatchwordsFromBiblegateway (&$out, array $extConf)
    {
        /** @var \TYPO3\CMS\Core\Charset\CharsetConverter $charsetConverter */
        $charsetConverter = GeneralUtility::makeInstance(\TYPO3\CMS\Core\Charset\CharsetConverter::class);
        $report = '';
            // Get the watchwords
        $result =
            $this->getWatchwords(
                $report,
                $extConf['bible_version'],
                $extConf['dateOffset'],
                $extConf['testFile']
            );

        $xmlString = $result['xml'];
        $charset = $result['charset'];
            // If no watchwords were fetched, return the standard output
        if (!$xmlString) {
            return $this->standardOutput($report['message']);
        }

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
            $out[$k] = $this->getCObj()->stdWrap($v, $extConf[$k . '.']);
        }
    }

    /**
    * Determines the date for which to fetch the watchwords in the following order
    *  - Prio 1: Date Offset from the current cObj
    *  - Prio 2: Date Offset from the TypoScript of the extension
    *  - Prio 3: No Date Offset found, take current date
    *
    * @return	integer		Date for which to get the watchwords (UNIX-timestamp)
    */
    public function getFetchDate ($paramDateOffset)
    {
        $fetchDate = '';

        if ($paramDateOffset) {
            $fetchDate = mktime(0, 0, 0) + (86400 * $paramDateOffset);
        } else {
            $fetchDate = mktime(0, 0, 0);
        }

        return $fetchDate;
    }


    /**
    * Get the watchwords in the following order
    *  - Prio 1: First see if there is watchwords for this day and language cached already
    *  - Prio 2: Use the testFile
    *  - Prio 3: Get the XML-File through HTTP
    *
    * @param array output $report Error code/message
    * @param int $paramDateOffset offset for the datetime
    * @param string $paramTestFile test file
    *
    * @return	array		Watchwords as array of encoding and XML string
    */
    public function getWatchwords (&$report = null, $bibleVersion, $paramDateOffset = null, $paramTestFile = null)
    {
        $xmlString = '';

        $fetchDate = $this->getFetchDate($paramDateOffset);
        $hashKey = md5('tx_watchwords_storeKey:' . serialize([$fetchDate, $bibleVersion]));

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

        if ($paramTestFile) {
            $xmlString = GeneralUtility::getURL($paramTestFile);
        } else {
            $accept = [
                'type' => ['application/rss+xml', 'application/xml', 'application/rdf+xml', 'text/xml'],
                'charset' => array_diff(mb_list_encodings(), ['pass', 'auto', 'wchar', 'byte2be', 'byte2le', 'byte4be', 'byte4le', 'BASE64', 'UUENCODE', 'HTML-ENTITIES', 'Quoted-Printable', '7bit', '8bit'])
            ];

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
        $cObj = GeneralUtility::makeInstance(\TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer::class);
        $out =
            $cObj->stdWrap(
                $this->extConf['standard'] . ' ' . $message,
                $this->extConf['standard.']
            );
        return $out;
    }
}
