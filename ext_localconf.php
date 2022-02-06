<?php
defined('TYPO3_MODE') || die('Access denied.');

call_user_func(
    function()
    {
        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
            'JambageCom.Watchwords',
            'Watch',
            [
                \JambageCom\Watchwords\Controller\WatchwordsController::class => 'index'
            ],
            // non-cacheable actions
            [
                \JambageCom\Watchwords\Controller\WatchwordsController::class => ''
            ]
        );

        // wizards
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig(
            'mod {
                wizards.newContentElement.wizardItems.plugins {
                    elements {
                        watch {
                            iconIdentifier = watchwords-plugin-watch
                            title = LLL:EXT:watchwords/Resources/Private/Language/locallang_db.xlf:watchwords_watch.name
                            description = LLL:EXT:watchwords/Resources/Private/Language/locallang_db.xlf:watchwords_watch.description
                            tt_content_defValues {
                                CType = list
                                list_type = watchwords_watch
                            }
                        }
                    }
                    show = *
                }
           }'
        );
		$iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Imaging\IconRegistry::class);
        $iconRegistry->registerIcon(
            'watchwords-plugin-watch',
            \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
            ['source' => 'EXT:watchwords/Resources/Public/Icons/user_plugin_watch.svg']
        );
    }
);
