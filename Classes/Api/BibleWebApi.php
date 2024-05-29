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


use TYPO3\CMS\Core\LinkHandling\LinkService;
use TYPO3\CMS\Core\Utility\GeneralUtility;


class BibleWebApi extends BibleApi
{
    public $biblegatewayCom = 'http://www.biblegateway.com/usage/votd/rss/votd.rdf';	// URL of biblegateway.com

    public function getWatchwordsFromBiblegateway (&$out, array $extConf)
    {
        $cObj = GeneralUtility::makeInstance(\TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer::class);

        /** @var \TYPO3\CMS\Core\Charset\CharsetConverter $charsetConverter */
        $charsetConverter = GeneralUtility::makeInstance(\TYPO3\CMS\Core\Charset\CharsetConverter::class);

            // Get the watchwords
        $result =
            $this->getWatchwords(
                $extConf['bibleVersion'] ?? '',
                $extConf['timeOffset'] ?? '',
                $extConf['testFile'] ?? ''
            );

        $xmlString = $result['xml'];
        $charset = $result['charset'];
            // If no watchwords were fetched, return the standard output
        if (!$xmlString) {
            $out['error'] =
                htmlspecialchars(
                    $this->standardOutput('READ ERROR', $extConf),
                    ENT_HTML5
                );
            return false;
        }

            // Parse the XML
        $xml = simplexml_load_string($xmlString);

        $out['copyright'] = $xml->channel->title;
        $out['license'] = $xml->channel->link;
        $out['bibleLink'] = $out['verseSource'] = '';

        if (isset($xml->channel->item)) {
            $out['bibleLink'] = $xml->channel->item->guid;
            $out['verseSource'] = $xml->channel->item->title;
        }

            // Yes, this is ugly... but it works! :-)
        $split1 = explode('&ldquo;', $xmlString);
        $split2 = explode('&rdquo;', $split1[1]);

        $out['verse'] = $split2[0];

            // Additional fields
        $out['date'] = time();

            // Trim, convert charset to metaCharset (conversion to the output charset will be done by the core)
            // and apply stdWrap to all values
        foreach ($out as $k => $v) {
            $value = trim($v);
            if (!empty($charset) && mb_check_encoding($value, $charset)) {
                $value = $charsetConverter->conv(trim($value), $charset,  'utf-8');
            }
            if (isset($extConf[$k . '.'])) {
                $value = $cObj->stdWrap($value, $extConf[$k . '.']);
            }
            $out[$k] = htmlspecialchars($value, ENT_HTML5);
        }
    }

    /**
    * Determines the time for which to fetch the watchwords in the following order
    *  - Prio 1: Time Offset from the current cObj
    *  - Prio 2: Time Offset from the TypoScript of the extension
    *  - Prio 3: No Time Offset found, take current time
    *
    * @return	integer		Date for which to get the watchwords (UNIX-timestamp)
    */
    public function getFetchDate ($paramTimeOffset)
    {
        $fetchDate = '';

        if ($paramTimeOffset) {
            $fetchDate = mktime(0, 0, 0) + (3600 * $paramTimeOffset);
        } else {
            $fetchDate = mktime(0, 0, 0);
        }

        return $fetchDate;
    }


    /**
    * Get the watchwords in the following order
    *  - Prio 1: First see if there is a watchword for this day and language cached already
    *  - Prio 2: Use the testFile
    *  - Prio 3: Get the XML-File through HTTP
    *
    * @param int $paramTimeOffset offset for the datetime
    * @param string $paramTestFile test file
    *
    * @return	array		Watchwords as array of encoding and XML string
    */
    public function getWatchwords ($bibleVersion, $paramTimeOffset = null, $paramTestFile = null)
    {
        $xmlString = '';
        $encoding = '';

        $fetchDate = $this->getFetchDate($paramTimeOffset);
        $hashKey = md5('tx_watchwords_storeKey:' . serialize([$fetchDate, $bibleVersion]));

        // see if there is a cached XML
        $cachedXmlString = null;
        /** @var \TYPO3\CMS\Core\Cache\Frontend\FrontendInterface $contentHashCache */
        $contentHashCache = GeneralUtility::makeInstance(\TYPO3\CMS\Core\Cache\CacheManager::class)->getCache('hash');
        $cacheEntry = $contentHashCache->get($hashKey);
        if ($cacheEntry) {
            $cachedXmlString = $cacheEntry;
        }

        if ($cachedXmlString) {
            return ['charset' => 'utf-8', 'xml' => $cachedXmlString];
        }

        if ($paramTestFile) {
            $linkService = GeneralUtility::makeInstance(LinkService::class);
            $fileInfo = $linkService->resolve($paramTestFile);

            if (
                is_array($fileInfo) &&
                isset($fileInfo['type']) &&
                $fileInfo['type'] == 'file' &&
                isset($fileInfo['file']) &&
                $fileInfo['file'] instanceof \TYPO3\CMS\Core\Resource\FileInterface
            ) {
                $xmlString = $fileInfo['file']->getContents();
            }
        } else {
            $accept = [
                'type' => ['application/rss+xml', 'application/xml', 'application/rdf+xml', 'text/xml'],
                'charset' => array_diff(mb_list_encodings(), ['pass', 'auto', 'wchar', 'byte2be', 'byte2le', 'byte4be', 'byte4le', 'BASE64', 'UUENCODE', 'HTML-ENTITIES', 'Quoted-Printable', '7bit', '8bit'])
            ];

            $urlParams = $bibleVersion ? '?' . $bibleVersion : '';
            $url = $this->biblegatewayCom . $urlParams;
            $xmlString  = GeneralUtility::getURL($url);

            if ($xmlString === false) {
                trigger_error('Cannot read file "' . $url . '"', E_USER_ERROR);
                return false;
            }

            // if the xml is fetched from remote, store it in the cache
            if ($xmlString != '') {
                $ident = 'tx_watchwords';
                $contentHashCache->set($hashKey, $xmlString, ['ident_' . $ident], (int) 0);
            }
        }

        if (!$encoding) {
            if (preg_match('/^<\?xml\s+version=(?:"[^"]*"|\'[^\']*\')\s+encoding=("[^"]*"|\'[^\']*\')/s', $xmlString, $match)) {
                $encoding = trim($match[1], '"\'');
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
    public function standardOutput ($message, $conf)
    {
        $cObj = GeneralUtility::makeInstance(\TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer::class);

        $typoScriptArray =
            [
                'cObject' => $conf['standard'],
                'cObject.' => $conf['standard.']
            ];

        $out =
            $cObj->stdWrap(
                $message,
                $typoScriptArray
            );

        $out .= ' ' . $message;
        return $out;
    }
}
