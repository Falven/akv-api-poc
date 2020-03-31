<?php

/**
 * PHP version 7.4
 *
 * @category  Microsoft Azure
 *
 * @author    Fran Aguilera <fraguile@microsoft.com>
 * @copyright 2020 Microsoft Corporation
 */

namespace PHPAzure\KeyVault;

$testVar = 'This is a test var.';

class AKVClient
{

    private $clientId = 'bc680ae1-00cd-423b-b5c3-41584e162472';
    private $clientSecret = '22a4ae3d-e214-42b9-9a91-45247cec5f0d';
    private $tenantId = 'tenantId';
    private $subscriptionId = '4be583c6-356a-4338-9649-f7ae5c77372e';
    private $vaultUri = 'https://kv-trueakv.vault.azure.net/';
    private $secretName = 'truesecretname';
    private $apiVersion = '2016-10-01';
    private $resource = 'https://vault.azure.net';

    /*
     * The following code will POST to the Azure Oauth/Token endpoint to get a Bearer Token.
     * The token will used in the Authorization header when you make the GetSecret request.
     */
    function getBearerToken()
    {
        $ch = curl_init('https://login.microsoftonline.com/' + $this->$tenantId + '/oauth2/token');
        if($ch)
        {
            // Post request
            curl_setopt($ch, CURLOPT_POST, true);
            // Request headers
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
            // Request body
            $body = urlencode(sprintf('[
                {key: "grant_type", value: "client_credentials", disabled: false},
                {key: "client_id", value: %s, disabled: false},
                {key: "client_secret", value: %s, disabled: false},
                {key: "resource", value: %s, disabled: false}
            ]'), $this->$clientId, $this->$clientSecret, $this->$resource);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
            // Execute request
            return parseBearerToken(curl_exec($ch));
        }
    }

    function parseBearerToken($postResult)
    {
        echo $postResult;
    }

    function getSecret()
    {

    }

    // method declaration
    function getMessage()
    {
        return $this->message;
    }

    // Method: POST, PUT, GET etc
    // Data: array("param" => "value") ==> index.php?param=value
    public function callAPI($method, $url, $data = false)
    {
        

        switch ($method)
        {
            case "POST":
                curl_setopt($curl, CURLOPT_POST, 1);

                if ($data)
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                break;
            case "PUT":
                curl_setopt($curl, CURLOPT_PUT, 1);
                break;
            default:
                if ($data)
                    $url = sprintf("%s?%s", $url, http_build_query($data));
        }

        // Optional Authentication:
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($curl, CURLOPT_USERPWD, "username:password");

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        $result = curl_exec($curl);

        curl_close($curl);

        return $result;
    }
}
?>