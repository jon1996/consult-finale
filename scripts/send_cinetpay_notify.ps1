param(
    [Parameter(Mandatory=$true)][string] $url,
    [Parameter(Mandatory=$true)][string] $site,
    [Parameter(Mandatory=$true)][string] $trans,
    [Parameter(Mandatory=$true)][string] $amount,
    [Parameter(Mandatory=$true)][string] $secret,
    [string] $currency = 'XOF'
)

# Build body
$body = @{
    cpm_site_id = $site
    cpm_trans_id = $trans
    cpm_trans_date = (Get-Date).ToString('yyyy-MM-dd HH:mm:ss')
    cpm_amount = $amount
    cpm_currency = $currency
    signature = ''
    payment_method = 'OM'
    cel_phone_num = ''
    cpm_phone_prefixe = ''
    cpm_language = 'FR'
    cpm_version = 'V4'
    cpm_payment_config = ''
    cpm_page_action = 'Payment'
    cpm_custom = ''
    cpm_designation = ''
    cpm_error_message = ''
}

# Concatenate fields in order
$fields = 'cpm_site_id','cpm_trans_id','cpm_trans_date','cpm_amount','cpm_currency','signature','payment_method','cel_phone_num','cpm_phone_prefixe','cpm_language','cpm_version','cpm_payment_config','cpm_page_action','cpm_custom','cpm_designation','cpm_error_message'
$string = ''
foreach ($f in $fields) { $string += ($body[$f] -as [string]) }

# Compute HMAC-SHA256
$hmac = [System.BitConverter]::ToString((New-Object System.Security.Cryptography.HMACSHA256([System.Text.Encoding]::UTF8.GetBytes($secret))).ComputeHash([System.Text.Encoding]::UTF8.GetBytes($string))).Replace('-','').ToLower()

# Send POST
Invoke-RestMethod -Uri $url -Method Post -Body $body -ContentType 'application/x-www-form-urlencoded' -Headers @{ 'X-TOKEN' = $hmac }

Write-Output "Sent notify to $url with X-TOKEN $hmac"
