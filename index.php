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

$ac = new AKVClient();
echo $ac->getBearerToken();

?>
  </body>
</html>