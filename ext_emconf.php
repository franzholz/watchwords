<?php

/***************************************************************
 * Extension Manager/Repository config file for ext: "watchwords"
 ***************************************************************/

$EM_CONF['watchwords'] = [
    'title' => 'Display daily Christian Watchwords',
    'description' => 'This extension adds a new content element which will get a new Christian Watchword (bible verse) every day.',
    'category' => 'plugin',
    'author' => 'Franz Holzinger',
    'author_email' => 'franz@ttproducts.de',
    'state' => 'stable',
    'clearCacheOnLoad' => 0,
    'version' => '2.3.0',
    'constraints' => [
        'depends' => [
            'typo3' => '13.4.0-13.4.99'
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
];
