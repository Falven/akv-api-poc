<?php

include 'PHPAzure/KeyVault/AKVClient.php';

use AKVClient;

echo "Hello Azure!";

$akvVar = new AKVClient();

$akvVar->displayVar();