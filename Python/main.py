# Azure Key Vault Secret Client API:
# https://azuresdkdocs.blob.core.windows.net/$web/python/azure-keyvault-secrets/4.0.0/index.html
#
# Prerequisites
# An Azure subscription
# Python 2.7, 3.5.3, or later
# A Key Vault. 

import json
from azure.identity import ClientSecretCredential
from azure.keyvault.secrets import SecretClient

#Retrieve Credentials from config
with open('../akv.config.json') as json_data_file:
    config = json.load(json_data_file)

#Create Azure Identity Obj using Python AzureSDK libs
credential = ClientSecretCredential(
    client_id = config['clientId'],
    client_secret = config['clientSecret'],
    tenant_id = config['tenantId']
)

#Create AKV Client
secret_client = SecretClient(vault_url=config['vaultUri'], credential=credential)

# #Retrieve KeyVaultSecret Obj
secret = secret_client.get_secret("truesecretname")

# #Output Secret Values
print("\nResponse:")
print("Secret Name:  ", secret.name)
print("Secret Value: ", secret.value, "\n")