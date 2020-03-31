<?php

include 'PHPAzure/KeyVault/AKVClient.php';

use AKVClient;

echo "Hello Azure!<br />";
echo "Initializing AKVClient<br />";
$akvVar = new AKVClient();

$akvVar->displayVar();