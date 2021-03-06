<?php

########################################################################
# Extension Manager/Repository config file for ext: "watchwords"
########################################################################

$EM_CONF[$_EXTKEY] = array(
    'title' => 'Display daily Christian Watchwords',
    'description' => 'This extension adds a new content element which will get a new Christian Watchword (bible verse) every day.',
    'category' => 'plugin',
    'version' => '1.8.0',
    'state' => 'stable',
    'uploadfolder' => 0,
    'createDirs' => '',
    'clearcacheonload' => 0,
    'author' => 'David Bruehlmeier / Franz Holzinger',
    'author_email' => 'franz@ttproducts.de',
    'author_company' => '',
    'constraints' => array(
        'depends' => array(
            'php' => '7.0.0-7.3.99',
            'typo3' => '8.7.0-9.5.99',
        ),
        'conflicts' => array(
        ),
        'suggests' => array(
        ),
    )
);

