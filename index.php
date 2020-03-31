<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>PHP AKV POC</title>
  </head>
  <body>
<?php

include './PHPAzure/KeyVault/AKVClient.php';

use AKVClient;

echo "Hello Azure!<br />";
echo "Initializing AKVClient...<br />";
echo "Test Var: $testVar";

$akvVar = new AKVClient();
echo $akvVar->displayVar(), "<br />";

?>
  </body>
</html>