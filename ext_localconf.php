<?php
defined('TYPO3') || die('Access denied.');

call_user_func(
    function()
    {
        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
            'Watchwords',
            'Watch',
            // controller-action combinations: The first is the default one.
            [
                \JambageCom\Watchwords\Controller\WatchwordsController::class => 'index'
            ],
            // non-cacheable actions
            [
                \JambageCom\Watchwords\Controller\WatchwordsController::class => 'index'
            ]
        );

        // wizards
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig(
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
		$iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Imaging\IconRegistry::class);
        $iconRegistry->registerIcon(
            'watchwords-plugin-watch',
            \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
            ['source' => 'EXT:watchwords/Resources/Public/Icons/user_plugin_watch.svg']
        );
    }
);
