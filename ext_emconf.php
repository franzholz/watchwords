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
    'version' => '2.1.1',
    'constraints' => [
        'depends' => [
            'typo3' => '11.5.0-12.4.99'
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
];
