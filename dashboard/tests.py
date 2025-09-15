from django.test import TestCase, Client
from django.contrib.auth import get_user_model
from django.urls import reverse
from unittest import mock

from .models import ServiceRequest


class CinetPayTests(TestCase):
	def setUp(self):
		User = get_user_model()
		self.user = User.objects.create_user(username='u1', email='u1@example.com', password='pass')
		self.client = Client()
		self.client.login(username='u1', password='pass')

		from django.conf import settings as _s
		_s.CINETPAY_SECRET_KEY = '1685606443689d9feb2af1f7.85476367'
		_s.CINETPAY_SITE_ID = '1576395816689d9f238de535.75359567'

	@mock.patch('dashboard.cinetpay.initiate_payment')
	def test_start_payment_happy_path(self, mock_initiate):
		sr = ServiceRequest.objects.create(user=self.user, kind='tdg', price=10)
		sr.status = ServiceRequest.Status.PENDING_PAYMENT
		sr.save(update_fields=['status'])
		mock_initiate.return_value = {'payment_url': 'https://pay.example/abc'}
		url = reverse('dashboard:cinetpay_start', args=[sr.id])
		resp = self.client.post(url)
		self.assertEqual(resp.status_code, 200)
		j = resp.json()
		self.assertTrue(j.get('ok'))
		self.assertEqual(j.get('payment_url'), 'https://pay.example/abc')
		sr.refresh_from_db()
		self.assertTrue(sr.payment_token.startswith('sr-'))


	def _hmac_for_body(self, body: dict, secret: str) -> str:
		import hmac, hashlib
		fields = [
			'cpm_site_id', 'cpm_trans_id', 'cpm_trans_date', 'cpm_amount', 'cpm_currency', 'signature',
			'payment_method', 'cel_phone_num', 'cpm_phone_prefixe', 'cpm_language', 'cpm_version',
			'cpm_payment_config', 'cpm_page_action', 'cpm_custom', 'cpm_designation', 'cpm_error_message',
		]
		parts = []
		for f in fields:
			v = body.get(f)
			parts.append(str(v) if v is not None else '')
		data = ''.join(parts).encode('utf-8')
		return hmac.new(secret.encode('utf-8'), data, hashlib.sha256).hexdigest()

	@mock.patch('dashboard.cinetpay.verify_payment')
	def test_notify_hmac_valid_marks_paid(self, mock_verify):
		# Setup
		sr = ServiceRequest.objects.create(user=self.user, kind='tdg', price=100)
		transaction_id = 'TRANS-12345'
		sr.payment_token = transaction_id
		sr.data = {'cinetpay_transaction_id': transaction_id}
		sr.status = ServiceRequest.Status.PENDING_PAYMENT
		sr.save()

		# Mock verification to return ACCEPTED
		mock_verify.return_value = {'code': '00', 'message': 'SUCCES', 'data': {'status': 'ACCEPTED'}}

		body = {
			'cpm_site_id': '1576395816689d9f238de535.75359567',
			'cpm_trans_id': transaction_id,
			'cpm_trans_date': '2025-08-24 12:00:00',
			'cpm_amount': str(sr.price),
			'cpm_currency': 'XOF',
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
		from dashboard import cinetpay as cp
		xtoken = cp.make_hmac_token(body, '1685606443689d9feb2af1f7.85476367')

		# Send POST as form-encoded with X-TOKEN header
		url = reverse('dashboard:cinetpay_notify')
		resp = self.client.post(url, data=body, content_type='application/x-www-form-urlencoded', **{'HTTP_X_TOKEN': xtoken})
		self.assertEqual(resp.status_code, 200)
		sr.refresh_from_db()
		self.assertEqual(sr.status, ServiceRequest.Status.PAID)

	def test_notify_hmac_invalid_rejected(self):
		sr = ServiceRequest.objects.create(user=self.user, kind='tdg', price=100)
		transaction_id = 'TRANS-INVALID'
		sr.payment_token = transaction_id
		sr.data = {'cinetpay_transaction_id': transaction_id}
		sr.status = ServiceRequest.Status.PENDING_PAYMENT
		sr.save()

		body = {
			'cpm_site_id': '1576395816689d9f238de535.75359567',
			'cpm_trans_id': transaction_id,
			'cpm_trans_date': '2025-08-24 12:00:00',
			'cpm_amount': str(sr.price),
			'cpm_currency': 'XOF',
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
		# Wrong secret -> wrong token
		wrong_token = 'deadbeef'
		url = reverse('dashboard:cinetpay_notify')
		resp = self.client.post(url, data=body, content_type='application/x-www-form-urlencoded', **{'HTTP_X_TOKEN': wrong_token})
		self.assertEqual(resp.status_code, 403)
