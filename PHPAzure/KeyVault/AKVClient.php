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
    private $tenantId = '72f988bf-86f1-41af-91ab-2d7cd011db47';
    private $subscriptionId = '4be583c6-356a-4338-9649-f7ae5c77372e';
    private $vaultUri = 'https://kv-trueakv.vault.azure.net/';
    private $secretName = 'truesecretname';
    private $apiVersion = '2016-10-01';
    private $resource = 'https://vault.azure.net';
    private $grantType = 'client_credentials';

    /*
     * The following code will POST to the Azure Oauth/Token endpoint to get a Bearer Token.
     * The token will used in the Authorization header when you make the GetSecret request.
     */
    private function getBearerToken()
    {
        $ch = curl_init();
        if($ch)
        {
            curl_setopt_array($ch, array(
                CURLOPT_URL => sprintf('https://login.microsoftonline.com/%s/oauth2/token', $this->tenantId),
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => http_build_query(array(
                    'grant_type' => $this->grantType,
                    'client_id' => $this->clientId,
                    'client_secret' => $this->clientSecret,
                    'resource' => $this->resource
                )),
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_SSL_VERIFYHOST => false,
                CURLOPT_SSL_VERIFYPEER => false
            ));

            $reqInfo = curl_getinfo($ch);

            // Execute request
            $response = curl_exec($ch);

            // Retry 3 times while CURLE_OPERATION_TIMEDOUT error occurrs
            $retry = 0;
            while(curl_errno($ch) == 28 && $retry++ < 3)
            {
                $response = curl_exec($ch);
            }

            // Close cURL resource to free up system resources
            curl_close($ch);

            if($response)
            {
                return json_decode($response);
            }
            return $response;
        }
    }

    function getSecret()
    {
        $bearerResponse = $this->getBearerToken();

        if($bearerResponse)
        {
            $ch = curl_init();
            if($ch)
            {
                curl_setopt_array($ch, array(
                    CURLOPT_URL => sprintf('%s/secrets/%s/?%s',
                     $this->vaultUri,
                     $this->secretName,
                     http_build_query(array('api-version' => $this->apiVersion))),
                    CURLOPT_HTTPHEADER => array(
                        'Authorization: Bearer ' . $bearerResponse->access_token,
                        'Content-Type: application/json'
                    ),
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_SSL_VERIFYHOST => false,
                    CURLOPT_SSL_VERIFYPEER => false,
                    CURLOPT_TIMEOUT => 5
                ));
    
                $reqInfo = curl_getinfo($ch);
    
                // Execute request
                $response = curl_exec($ch);
    
                // Retry 3 times while CURLE_OPERATION_TIMEDOUT error occurrs
                $retry = 0;
                while(curl_errno($ch) == 28 && $retry++ < 3)
                {
                    $response = curl_exec($ch);
                }
    
                // Close cURL resource to free up system resources
                curl_close($ch);
    
                if($response)
                {
                    return json_decode($response);
                }
                return $response;
            }
        }
    }
}
?>