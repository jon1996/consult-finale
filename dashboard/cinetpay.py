from django.conf import settings


def initiate_payment(payload: dict, timeout: int = 10) -> dict:
    """Call CinetPay create payment endpoint. Returns parsed JSON response."""
    import requests
    url = f"{settings.CINETPAY_API_BASE.rstrip('/')}/v2/payment"
    headers = {"Authorization": f"Bearer {settings.CINETPAY_API_KEY}"} if getattr(settings, 'CINETPAY_API_KEY', '') else {}
    headers["Content-Type"] = "application/json"
    resp = requests.post(url, json=payload, headers=headers, timeout=timeout)
    resp.raise_for_status()
    return resp.json()


def verify_payment(transaction_id: str, timeout: int = 10) -> dict:
    """Verify a transaction with CinetPay. Returns parsed JSON response."""
    import requests
    url = getattr(settings, 'CINETPAY_VERIFY_ENDPOINT')
    headers = {'Content-Type': 'application/json'}
    payload = {
        'site_id': settings.CINETPAY_SITE_ID,
        'transaction_id': transaction_id,
        'apikey': getattr(settings, 'CINETPAY_API_KEY', ''),
    }
    resp = requests.post(url, json=payload, headers=headers, timeout=timeout)
    resp.raise_for_status()
    return resp.json()


def validate_hmac_x_token(body: dict, received_token: str, secret_key: str) -> bool:
    """Validate the X-TOKEN HMAC following CinetPay doc.

    The concatenation order:
    cpm_site_id + cpm_trans_id + cpm_trans_date + cpm_amount + cpm_currency + signature + 
    payment_method + cel_phone_num + cpm_phone_prefixe + cpm_language + cpm_version 
    + cpm_payment_config + cpm_page_action + cpm_custom + cpm_designation + cpm_error_message
    """
    import hmac
    import hashlib

    fields = [
        'cpm_site_id', 'cpm_trans_id', 'cpm_trans_date', 'cpm_amount', 'cpm_currency', 'signature',
        'payment_method', 'cel_phone_num', 'cpm_phone_prefixe', 'cpm_language', 'cpm_version',
        'cpm_payment_config', 'cpm_page_action', 'cpm_custom', 'cpm_designation', 'cpm_error_message',
    ]
    # Delegate to make_hmac_token for consistency
    generated = make_hmac_token(body, secret_key)
    # Normalize both tokens to avoid case/whitespace mismatches from headers
    gen_norm = (generated or '').strip().lower()
    rec_norm = (received_token or '').strip().lower()
    return hmac.compare_digest(gen_norm, rec_norm)


def make_hmac_token(body: dict, secret_key: str) -> str:
    """Return hex HMAC-SHA256 token for the given CinetPay notification body."""
    import hmac
    import hashlib
    fields = [
        'cpm_site_id', 'cpm_trans_id', 'cpm_trans_date', 'cpm_amount', 'cpm_currency', 'signature',
        'payment_method', 'cel_phone_num', 'cpm_phone_prefixe', 'cpm_language', 'cpm_version',
        'cpm_payment_config', 'cpm_page_action', 'cpm_custom', 'cpm_designation', 'cpm_error_message',
    ]
    parts = []
    for f in fields:
        v = body.get(f) if isinstance(body, dict) else None
        parts.append(str(v) if v is not None else '')
    data = ''.join(parts).encode('utf-8')
    return hmac.new(secret_key.encode('utf-8'), data, hashlib.sha256).hexdigest()
