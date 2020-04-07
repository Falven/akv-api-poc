<?php

/**
 * Client that reads Azure key Vault configuration from a configuration file
 * and can retrieve a secret from Key Vault.
 * Tested using PHP version 7.4
 *
 * @category  Microsoft Azure Key Vault Client
 *
 * @author    Fran Aguilera <fraguile@microsoft.com>
 * @copyright 2020 Microsoft Corporation
 */

namespace PHPAzure\KeyVault;

class AKVClient
{
    private $config;

    /*
     * Reads configuration from provided JSON file.
     */
    function __construct($file) {
        if (!file_exists($file)) {
            throw new FileException('Could not find config: ' . $file);
        }
        $configStr = file_get_contents($file);
        $this->config = json_decode($configStr);
    }

    /*
     * The following code will POST to the Azure Oauth/Token endpoint to get a Bearer Token.
     * The token will used in the Authorization header when you make a GetSecret request.
     */
    private function getBearerToken() {
        $ch = curl_init();
        if($ch) {
            curl_setopt_array($ch, array(
                CURLOPT_URL => sprintf('https://login.microsoftonline.com/%s/oauth2/token', $this->config->tenantId),
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => http_build_query(array(
                    'grant_type' => 'client_credentials',
                    'client_id' => $this->config->clientId,
                    'client_secret' => $this->config->clientSecret,
                    'resource' => 'https://vault.azure.net'
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
            while(curl_errno($ch) == 28 && $retry++ < 3) {
                $response = curl_exec($ch);
            }

            // Close cURL resource to free up system resources
            curl_close($ch);

            if($response) {
                return json_decode($response);
            }
            return $response;
        }
    }

    public function getSecret($secretName) {
        $bearerResponse = $this->getBearerToken();

        if($bearerResponse) {
            $ch = curl_init();
            if($ch) {
                curl_setopt_array($ch, array(
                    CURLOPT_URL => sprintf(
                        '%s/secrets/%s/?%s',
                        $this->config->vaultUri,
                        $secretName,
                        http_build_query(array('api-version' => '2016-10-01'))),
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
                while(curl_errno($ch) == 28 && $retry++ < 3) {
                    $response = curl_exec($ch);
                }
    
                // Close cURL resource to free up system resources
                curl_close($ch);
    
                if($response) {
                    return json_decode($response);
                }
                return $response;
            }
        }
    }
}

?>