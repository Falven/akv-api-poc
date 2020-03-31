<?php

include 'PHPAzure/KeyVault/AKVClient.php';

use AKVClient;

echo "Hello Azure!";
echo "Initializing AKVClient";
$akvVar = new AKVClient();

$akvVar->displayVar();