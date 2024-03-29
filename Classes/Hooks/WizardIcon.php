<?php

namespace JambageCom\Watchwords\Hooks;

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with TYPO3 source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

/**
 * Class that adds the wizard icon.
 *
 * @category    Plugin
 * @package     TYPO3
 * @subpackage  watchwords
 * @author      Franz Holzinger <franz@ttproducts.de>
 * @copyright   Causal Sàrl
 * @license     http://www.gnu.org/copyleft/gpl.html
 */
class WizardIcon
{
    /**
     * Processes the wizard items array.
     *
     * @param array $wizardItems The wizard items
     * @return array Modified array with wizard items
     */
    public function proc (array $wizardItems)
    {
        $extensionKey = 'watchwords';

        /** @var \TYPO3\CMS\Core\Imaging\IconRegistry $iconRegistry */
        $iconRegistry = GeneralUtility::makeInstance(\TYPO3\CMS\Core\Imaging\IconRegistry::class);
        $iconPath = 'Resources/Public/Icons/';
        $languagePath = '/Resources/Private/Language/';

        $type = 'watchwords_watch';
        $params = '&defVals[tt_content][CType]=list&defVals[tt_content][list_type]=' . $type;
        $wizardItem = [
            'title' => $GLOBALS['LANG']->sL('LLL:EXT:' . $extensionKey . $languagePath . 'locallang.xlf:tt_content.list_type'),
            'description' => $GLOBALS['LANG']->sL('LLL:EXT:' . $extensionKey . $languagePath . 'locallang.xlf:tt_content.wizard_description'),
            'params' => $params
        ];

        $iconIdentifier = 'extensions-watchwords-' . $type . '-wizard';
        $iconRegistry->registerIcon(
            $iconIdentifier,
            \TYPO3\CMS\Core\Imaging\IconProvider\BitmapIconProvider::class,
            [
                'source' => 'EXT:' . $extensionKey . '/' . $iconPath . 'user_plugin_watch.svg',
            ]
        );
        $wizardItem['iconIdentifier'] = $iconIdentifier;
        $wizardItems['plugins_' . $type] = $wizardItem;

        return $wizardItems;
    }
}
