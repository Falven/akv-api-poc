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

      // truesecretname
      $secretName = "";
      if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST["secretName"]))
      {
        $secretName = sanitize_input($_POST["secretName"]);
      }

      include ('./PHPAzure/KeyVault/AKVClient.php');

      use PHPAzure\KeyVault\AKVClient;

      $ac = new AKVClient();
      $secretResponse = $ac->getSecret($secretName);
      if($secretResponse)
      {
        if(property_exists($secretResponse, 'error'))
        {
          $secretValue = $secretResponse->error->message;
        }
        else
        {
          $secretValue = $secretResponse->value;
        }
      }
    ?>

    <h2>TRUE Azure Key Vault POC</h2>
    <form method="post" action="<?=htmlspecialchars($_SERVER["PHP_SELF"]);?>">
      Secret name: <input type="text" name="secretName" value="<?= $secretName;?>">
      <br />
      <br />
      Secret value: <?=$secretValue?>
      <br />
      <br />
      <input type="submit" name="submit" value="Retrieve">
    </form>
  </body>
</html>