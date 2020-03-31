<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>PHP AKV POC</title>
  </head>
  <body>
<?php

include ('./PHPAzure/KeyVault/AKVClient.php');

use AKVClient;

echo "Hello Azure!<br />";
echo "Initializing AKVClient...<br />";
echo "Test Var: $testVar";

$ac = new AKVClient();
echo $ac->getMessage();

include ("file1.php");

class ClassB
{

    function __construct()
    {
    }

    function callA()
    {
        $classA = new ClassA();
        $name = $classA->getName();
        echo $name;    //Prints John
    }
}

$classb = new ClassB();
$classb->callA();

?>
  </body>
</html>