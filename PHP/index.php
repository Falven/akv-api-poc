<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>PHP AKV POC</title>
    <style>
      .error {color: #FF0000;}
    </style>
  </head>
  <body>
    <?php

      function sanitize_input($data) {
        return htmlspecialchars(trim($data));
      }

      // truesecretname
      $secretName = "";
      if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST["secretName"])) {
        $secretName = sanitize_input($_POST["secretName"]);
      }

      include ('./AzureSDK/KeyVault/AKVClient.php');

      use PHPAzure\KeyVault\AKVClient;

      $configFile = realpath('../akv.config.json');

      $ac = new AKVClient($configFile);
      $secretResponse = $ac->getSecret($secretName);

      if($secretResponse) {
        if(property_exists($secretResponse, 'error')) {
          $secretValue = $secretResponse->error->message;
        } else {
          $secretValue = $secretResponse->value;
        }
      }
    ?>

    <h2>TRUE Azure Key Vault POC</h2>
    <form method="post" action="<?=htmlspecialchars($_SERVER["PHP_SELF"]);?>">
      Secret name: <input type="text" name="secretName" value="<?= $secretName;?>">
      <br />
      <br />
      Secret value: <span class="<?php if(property_exists($secretResponse, 'error')) { echo 'error'; } ?>"><?=$secretValue?></span>
      <br />
      <br />
      <input type="submit" name="submit" value="Retrieve">
    </form>
  </body>
</html>