<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>PHP AKV POC</title>
  </head>
  <body>
<?php

include ('./PHPAzure/KeyVault/AKVClient.php');

use PHPAzure\KeyVault\AKVClient;

echo "Hello Azure!<br />";
echo "Initializing AKVClient...<br />";
echo "Test Var: $testVar";

$ac = new AKVClient();
echo $ac->getMessage();

?>
  </body>
</html>