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


use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\TypoScript\TypoScriptService;
use TYPO3\CMS\Core\Utility\GeneralUtility;

use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Mvc\Controller\Arguments;

use Psr\Http\Message\ResponseInterface;

use JambageCom\Watchwords\Api\BibleWebApi;



class WatchwordsController extends ActionController implements SingletonInterface
{
    /**
     * @var \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface
     */
    protected $configurationManager;


    /**
     * Index Action
     *
     * @return void
     */
    public function indexAction(): ResponseInterface
    {
        $api = GeneralUtility::makeInstance(BibleWebApi::class);
        $watchword = [];
        $api->getWatchwordsFromBiblegateway($watchword, $this->settings);
        $this->view->assign('watchword', $watchword);

        return $this->htmlResponse();
   }

    /**
     * Injects the Configuration Manager and is initializing the framework settings
     *
     * @param \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface $configurationManager Instance of the Configuration Manager
     */
    public function injectConfigurationManager(
        \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface $configurationManager
    ) {
        $typoScriptService = GeneralUtility::makeInstance(TypoScriptService::class);

        $this->configurationManager = $configurationManager;

        $tsSettings = $this->configurationManager->getConfiguration(
            \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK,
            'Watchwords',
            'Watchwords_Watch'
        );

        $provedSettings =
            $this->configurationManager->getConfiguration(
                \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface::CONFIGURATION_TYPE_SETTINGS
            );

        $propertiesViaFlexForms = ['bibleVersion', 'timeOffset', 'testFile'];
        foreach ($propertiesViaFlexForms as $property) {
            if (isset($tsSettings['settings'][$property])) {
                $provedSettings[$property] = $tsSettings['settings'][$property];
            }
        }

        // Use stdWrap for given defined settings
        if (isset($tsSettings['instructions']) && !empty($tsSettings['instructions'])) {
            $instructionsArray = $typoScriptService->convertPlainArrayToTypoScriptArray($tsSettings['instructions']);
            $provedSettings = array_merge($provedSettings, $instructionsArray);
        }

        $this->settings = $provedSettings;
        $this->arguments = GeneralUtility::makeInstance(Arguments::class);
    }
}

