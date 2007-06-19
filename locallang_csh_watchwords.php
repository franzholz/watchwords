<?php

// Field description for the additional fields of the extension 'watchwords''

$LOCAL_LANG = Array (
	'default' => Array (
		'tx_watchwords_date_offset.description' => 'Number of days that the watchword will be offset from the current date.',
		'tx_watchwords_date_offset.details' => 'If you set this field to 1, then the extension will fetch the watchword from tomorrow (today plus 1 day). If you use a negative number, for example -2, then the extension will get the watchword from the day before yesterday (today minus two days).',
		'tx_watchwords_language.description' => 'Language, in which the watchword will be displayed.',
		'tx_watchwords_language.details' => 'The languages available are depending on the source of the watchword (www.losung.de). If you chose (Default), the extension will determine the language in the following order:

1. Language defined in the TS-Template for this extension
2. Default language of the site
3. If none of these could be found, the extension choses English as the default language',
	),
	'dk' => Array (
	),
	'de' => Array (
		'tx_watchwords_date_offset.description' => 'Anzahl Tage Unterschied von heute, zur Bestimmung des Tages der Losung.',
		'tx_watchwords_date_offset.details' => 'Wenn Sie hier einen Wert von 1 eintragen, wird die Extension die Losung von morgen anzeigen (heute plus 1 Tag). Sie können auch negative Zahlen benutzen: Ein Wert von -2 wird beispielsweise die Losung von vorgestern anzeigen (heute minus 2 Tage).',
		'tx_watchwords_language.description' => 'Sprache, in welcher die Losung angezeigt wird.',
		'tx_watchwords_language.details' => 'Die verfügbaren Sprachen hängen vom Anbieter der Losungen ab (www.losung.de). Wenn Sie (Voreinstellung) wählen, wird die Extension die Sprache nach der folgenden Prioritätenregelung bestimmen:

1. Sprache, die im TS-Template für diese Extension festgelegt wurde
2. Standard-Sprache der Website
3. Falls keine dieser Werte ermittelt werden konnte, wird die Losung in English angezeigt',
	),
	'no' => Array (
		'tx_watchwords_date_offset.description' => 'Antall dager bibelordet vil være forskjøvet I forhold til dagens dato.',
		'tx_watchwords_date_offset.details' => 'Hvis du setter dette feltet til 1 vil Bibelordet for i morgen bli vist (i dag + 1 dag). Dersom du benytter et negativt tall, for eksempel –2, vil Bibelordet fra i forgårs vises.',
		'tx_watchwords_language.description' => 'Språk som bibelordene vises i.',
		'tx_watchwords_language.details' => 'Hvilke språk som er tilgjengelig er avhengig av kilden til bibelordene (www.losung.de). Hvis du velger (standard), vil språket bli valgt etter følgende rekkefølge:

1. Språket som er satt i TS-Mal for denne "extension".
2. Standardspråket for siden.
3. Hvis ingen av disse ble funnet vil engelsk bli valgt som standard.',
	),
	'it' => Array (
	),
	'fr' => Array (
	),
	'es' => Array (
	),
	'nl' => Array (
	),
	'cz' => Array (
	),
	'pl' => Array (
	),
	'si' => Array (
	),
	'fi' => Array (
		'tx_watchwords_date_offset.description' => 'Päivien lukumäärän siirtymä Päivän Sanalle nykyisestä päiväyksestä.',
		'tx_watchwords_date_offset.details' => 'Jos asetat tiedon arvoksi 1, apuohjelma hakee huomisen Päivän Sanan (tämä päivä + 1). Jos käytät negatiivista lukua, esimerkiksi -2, hakee apuohjelma Päivän Sanan toissapäivältä (tämä päivä - 2).',
		'tx_watchwords_language.description' => 'Kieli, jolla Päivän Sana näytetään.',
		'tx_watchwords_language.details' => 'Saatavilla olevat kielet ovat riippuvaisia Päivän Sanan lähteestä (www.losung.de). Jos valitset (Oletus), päättelee apuohjelma kielen seuraavassa järjestyksessä:

1. Kieli joka on määritelty tämän apuohjelman TS-mallinteessa
2. Sivuston oletuskieli
3. Jos kumpaakaan edelläolevaa ei löydy, asetetaan oletuskieleksi englanti.',
	),
	'tr' => Array (
	),
	'se' => Array (
	),
	'pt' => Array (
	),
	'ru' => Array (
	),
	'ro' => Array (
	),
	'ch' => Array (
	),
	'sk' => Array (
	),
	'lt' => Array (
	),
	'is' => Array (
	),
	'hr' => Array (
	),
	'hu' => Array (
	),
	'gl' => Array (
	),
	'th' => Array (
	),
	'gr' => Array (
	),
	'hk' => Array (
	),
	'eu' => Array (
	),
	'bg' => Array (
	),
	'br' => Array (
	),
	'et' => Array (
	),
	'ar' => Array (
	),
	'he' => Array (
	),
	'ua' => Array (
	),
	'lv' => Array (
	),
	'jp' => Array (
	),
	'vn' => Array (
	),
);
?>