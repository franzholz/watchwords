<?php

$key = 'watchwords';
$extensionPath = t3lib_extMgm::extPath($key, $script);

return array(
	'tx_ttproducts_wizicon' => $extensionPath . 'class.tx_ttproducts_wizicon.php',
);
?>