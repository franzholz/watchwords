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


use TYPO3\CMS\Core\Api\AbstractApi;


 /**
 * Parent class for "Api" classes
 */
abstract class BibleApi
{
    /**
     * @var array service description array
     */
    public $info =
        [
            'apiKey' => 'biblegateway',
            'title' => 'Bible Gateway',
            'url' => 'https://www.biblegateway.com/usage/votd/rss/votd.rdf'
        ];
    protected $cObj;

    public function __construct() {
        $this->cObj = $GLOBALS['TSFE']->cObj;
    }


    public function getCObj () {
        return $this->cObj;
    }
}

