<?php

########################################################################
# Extension Manager/Repository config file for ext: "watchwords"
########################################################################

$EM_CONF[$_EXTKEY] = array(
	'title' => 'Display daily Christian Watchwords',
	'description' => 'This extension adds a new content element which will get a new Christian Watchword (bible verse) every day. For TYPO3 4.5 use version 1.0.1',
	'category' => 'plugin',
	'shy' => 0,
	'version' => '1.1.0',
	'dependencies' => '',
	'conflicts' => '',
	'priority' => '',
	'loadOrder' => '',
	'module' => '',
	'state' => 'stable',
	'uploadfolder' => 0,
	'createDirs' => '',
	'modify_tables' => 'tt_content',
	'clearcacheonload' => 0,
	'lockType' => '',
	'author' => 'David Bruehlmeier',
	'author_email' => 'typo3@bruehlmeier.com',
	'author_company' => '',
	'CGLcompliance' => '',
	'CGLcompliance_note' => '',
	'constraints' => array(
		'depends' => array(
			'php' => '5.2.0-7.99.99',
			'typo3' => '6.2.0-7.99.99',
		),
		'conflicts' => array(
		),
		'suggests' => array(
		),
	),
	'_md5_values_when_last_written' => 'a:17:{s:12:"ext_icon.gif";s:4:"5d27";s:17:"ext_localconf.php";s:4:"78bf";s:15:"ext_php_api.dat";s:4:"faca";s:14:"ext_tables.php";s:4:"838d";s:28:"ext_typoscript_constants.txt";s:4:"f6b5";s:24:"ext_typoscript_setup.txt";s:4:"67fb";s:28:"flexform_ds_biblegateway.xml";s:4:"e4cf";s:13:"locallang.php";s:4:"415b";s:14:"doc/manual.sxw";s:4:"ae50";s:35:"inc/class.tx_watchwords_tslibfe.php";s:4:"4d45";s:14:"pi1/ce_wiz.gif";s:4:"b1fc";s:31:"pi1/class.tx_watchwords_pi1.php";s:4:"a910";s:39:"pi1/class.tx_watchwords_pi1_wizicon.php";s:4:"9a7f";s:13:"pi1/clear.gif";s:4:"cc11";s:17:"pi1/locallang.php";s:4:"e8fd";s:29:"pi1/template_biblegateway.inc";s:4:"b829";s:23:"xml/biblegateway_en.xml";s:4:"be75";}',
);

