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

namespace JambageCom\Watchwords\Controller;


use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use Psr\Http\Message\ResponseInterface;

use JambageCom\Watchwords\Api\BibleWebApi;


class WatchwordsController extends ActionController
{
    /**
     * Index Action
     *
     * @return void
     */
    public function indexAction()
    {
        $api = $this->objectManager->get(BibleWebApi::class);
        $watchword = [];
        $api->getWatchwordsFromBiblegateway($watchword, $this->settings);
        $this->view->assign('watchword', $watchword);
   }
}

