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
    'version' => '2.0.1',
    'constraints' => [
        'depends' => [
            'typo3' => '10.4.0-11.5.99',
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
];
