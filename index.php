<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>PHP AKV POC</title>
  </head>
  <body>
<?php

include 'PHPAzure/KeyVault/AKVClient.php';

use AKVClient;

echo "Hello Azure!<br />\n";
echo "Initializing AKVClient<br />\n";
$akvVar = new AKVClient();

echo $akvVar->displayVar(), "<br />\n";

?>
  </body>
</html>