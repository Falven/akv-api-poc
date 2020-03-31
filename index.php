<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>PHP AKV POC</title>
  </head>
  <body>
    <?php

      function sanitize_input($data)
      {
        return htmlspecialchars(trim($data));
      }

      if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST["secretName"]))
      {
        $secretName = sanitize_input($_POST["secretName"]);
      }

      include ('./PHPAzure/KeyVault/AKVClient.php');

      use PHPAzure\KeyVault\AKVClient;

      $ac = new AKVClient();
      $secretResponse = $ac->getSecret();
      if($secretResponse)
      {
        $secretValue = $secretResponse->value;
      }
    ?>

    <h2>TRUE Azure Key Vault POC</h2>
    <form method="post" action="<?=htmlspecialchars($_SERVER["PHP_SELF"]);?>">
      Secret name: <input type="text" name="secretName" value="<?= $secretName;?>">
      Secret value: <?=$secretValue?>
      <input type="submit" name="submit" value="Retrieve">
    </form>
  </body>
</html>