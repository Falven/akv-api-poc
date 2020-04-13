# It is recommended to follow [Azure Naming and Tagging Best Practices](https://docs.microsoft.com/en-us/azure/cloud-adoption-framework/ready/azure-best-practices/naming-and-tagging)

# You can get this from the Azure Portal under "Navigate" select "Subscriptions" and copy the appropriate ID.
# Or you can use the Get-AzureSubscription cmdlet.
$SUBSCRIPTION_ID = ''

$RESOURCE_GROUP_NAME = 'rg-akvapipoc'
$RESOURCE_GROUP_LOCATION = 'eastus'

$KEYVAULT_NAME = 'kv-akvapipoc'
$KEYVAULT_SECRET_NAME = 'secretname'
$KEYVAULT_SECRET_VALUE = 'secretvalue'

$SERVICE_PRINCIPAL_NAME = 'sp-akvapipoc'

$NL = [Environment]::NewLine

Write-Host "$($NL)Log into our Azure Account" -ForegroundColor DarkBlue
az login

Write-Host "$($NL)Set our subscription" -ForegroundColor DarkBlue
az account set --subscription $SUBSCRIPTION_ID

Write-Host "$($NL)Delete our Resource Group if it exists" -ForegroundColor DarkRed
az group delete --name $RESOURCE_GROUP_NAME --yes

Write-Host "$($NL)Create a Resource Group to hold our Key Vault" -ForegroundColor DarkBlue
az group create --name $RESOURCE_GROUP_NAME --location $RESOURCE_GROUP_LOCATION

Write-Host "$($NL)Purge our Key Vault if it exists" -ForegroundColor DarkRed
az keyvault purge --name $KEYVAULT_NAME

Write-Host "$($NL)Create Our Key Vault within our Resource Group" -ForegroundColor DarkBlue
az keyvault create --name $KEYVAULT_NAME --resource-group $RESOURCE_GROUP_NAME

Write-Host "$($NL)Get our Key Vault's URI" -ForegroundColor DarkBlue
$KEYVAULT_URI = $(az keyvault show --name $KEYVAULT_NAME --query properties.vaultUri -otsv)
Write-Host $KEYVAULT_URI

Write-Host "$($NL)Create our secret" -ForegroundColor DarkBlue
az keyvault secret set --name $KEYVAULT_SECRET_NAME --vault-name $KEYVAULT_NAME --value $KEYVAULT_SECRET_VALUE

Write-Host "$($NL)Delete our service principal if it exists" -ForegroundColor DarkRed
$SERVICE_PRINCIPAL_APP_ID = $(az ad sp list --display-name $SERVICE_PRINCIPAL_NAME --query [0].appId -otsv)
if (-not ([string]::IsNullOrEmpty($SERVICE_PRINCIPAL_APP_ID))) { az ad sp delete --id $SERVICE_PRINCIPAL_APP_ID } else { Write-Host "Could not find service principal." -ForegroundColor DarkRed }

Write-Host "$($NL)Create a service principal to access the Key Vault" -ForegroundColor DarkBlue
$SERVICE_PRINCIPAL_PASSWORD = $((az ad sp create-for-rbac --name $SERVICE_PRINCIPAL_NAME) | Out-String | ConvertFrom-Json).password

Write-Host "$($NL)Get the App ID for our Service Principal" -ForegroundColor DarkBlue
$SERVICE_PRINCIPAL_APP_ID = $(az ad sp list --display-name $SERVICE_PRINCIPAL_NAME --query [0].appId -otsv)

Write-Host "$($NL)Give our Service Principal 'get' access to our Key Vault" -ForegroundColor DarkBlue
az keyvault set-policy --name $KEYVAULT_NAME --spn $SERVICE_PRINCIPAL_NAME --secret-permissions get

Write-Host "$($NL)Get the tenant id for our account" -ForegroundColor DarkBlue
$TENANT_ID = $(az account show --query tenantId -otsv)

Write-Host @"
Azure key Vault REST API Parameters:
Client Id:          $SERVICE_PRINCIPAL_APP_ID
Client Secret:      $SERVICE_PRINCIPAL_PASSWORD
Tenant Id:          $TENANT_ID
Subscription Id:    $SUBSCRIPTION_ID
Vault Uri:          $KEYVAULT_URI
Secret Name:        $KEYVAULT_SECRET_NAME
Api Version:        2016-10-01
Key Vault Resource: https://vault.azure.net
"@ -ForegroundColor DarkGreen
