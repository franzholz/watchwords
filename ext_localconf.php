<?php
defined('TYPO3') || die('Access denied.');

use TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider;
use TYPO3\CMS\Core\Imaging\IconRegistry;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

use JambageCom\Watchwords\Controller\WatchwordsController;


call_user_func(
    function()
    {
        ExtensionUtility::configurePlugin(
            'Watchwords',
            'Watch',
            // controller-action combinations: The first is the default one.
            [
                WatchwordsController::class => 'index'
            ],
            // non-cacheable actions
            [
                WatchwordsController::class => 'index'
            ]
        );

        // wizards
        ExtensionManagementUtility::addPageTSConfig(
            'mod {
                wizards.newContentElement.wizardItems.plugins {
                    elements {
                        watch {
                            iconIdentifier = watchwords-plugin-watch
                            title = LLL:EXT:watchwords/Resources/Private/Language/locallang_db.xlf:tx_watchwords.name
                            description = LLL:EXT:watchwords/Resources/Private/Language/locallang_db.xlf:tx_watchwords.description
                            tt_content_defValues {
                                CType = list
                                list_type = tx_watchwords
                            }
                        }
                    }
                    show = *
                }
           }'
        );
		$iconRegistry = GeneralUtility::makeInstance(IconRegistry::class);
        $iconRegistry->registerIcon(
            'watchwords-plugin-watch',
             SvgIconProvider::class,
            ['source' => 'EXT:watchwords/Resources/Public/Icons/user_plugin_watch.svg']
        );
    }
);
