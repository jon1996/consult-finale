#!/usr/bin/env python3
"""Send a simulated CinetPay notification (form-encoded) with X-TOKEN HMAC.
Usage: python scripts/send_cinetpay_notify.py --url <notify_url> --site <site_id> --trans <trans_id> --amount <amount> --secret <secret>
"""
import argparse
import hmac
import hashlib
import requests

FIELDS = [
    'cpm_site_id', 'cpm_trans_id', 'cpm_trans_date', 'cpm_amount', 'cpm_currency', 'signature',
    'payment_method', 'cel_phone_num', 'cpm_phone_prefixe', 'cpm_language', 'cpm_version',
    'cpm_payment_config', 'cpm_page_action', 'cpm_custom', 'cpm_designation', 'cpm_error_message',
]


def make_xtoken(body: dict, secret: str) -> str:
    parts = []
    for f in FIELDS:
        v = body.get(f)
        parts.append(str(v) if v is not None else '')
    data = ''.join(parts).encode('utf-8')
    return hmac.new(secret.encode('utf-8'), data, hashlib.sha256).hexdigest()


def main():
    p = argparse.ArgumentParser()
    p.add_argument('--url', required=True)
    p.add_argument('--site', required=True)
    p.add_argument('--trans', required=True)
    p.add_argument('--amount', required=True)
    p.add_argument('--secret', required=True)
    p.add_argument('--currency', default='XOF')
    args = p.parse_args()

    body = {
        'cpm_site_id': args.site,
        'cpm_trans_id': args.trans,
        'cpm_trans_date': '2025-08-24 12:00:00',
        'cpm_amount': str(args.amount),
        'cpm_currency': args.currency,
        'signature': '',
        'payment_method': 'OM',
        'cel_phone_num': '',
        'cpm_phone_prefixe': '',
        'cpm_language': 'FR',
        'cpm_version': 'V4',
        'cpm_payment_config': '',
        'cpm_page_action': 'Payment',
        'cpm_custom': '',
        'cpm_designation': '',
        'cpm_error_message': '',
    }

    xtoken = make_xtoken(body, args.secret)
    headers = {'X-TOKEN': xtoken, 'Content-Type': 'application/x-www-form-urlencoded'}
    resp = requests.post(args.url, data=body, headers=headers)
    print('Status:', resp.status_code)
    print(resp.text)


if __name__ == '__main__':
    main()
