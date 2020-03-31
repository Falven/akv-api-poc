<?php

include 'Azure/KeyVault/AKVClient.php';

echo "Hello Azure!";

$akvVar = new AKVClient();

$akvVar->displayVar();