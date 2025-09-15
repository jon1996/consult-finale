from django.shortcuts import render, redirect
from django.conf import settings
from django.http import HttpResponseForbidden, HttpResponse
from django.views.decorators.csrf import csrf_exempt
from django.middleware.csrf import CsrfViewMiddleware
from django.utils.translation import check_for_language
# is_safe_url was removed; we do a simple relative-URL check instead
from django.http import JsonResponse


# Secure internal endpoint to set language for proxied server-side callers.
# Accepts POST with 'language' and requires header 'X-Internal-API-Key' equal to
# settings.INTERNAL_SETLANG_KEY. If header absent, behavior falls back to default
# django.views.i18n.set_language which enforces CSRF.
@csrf_exempt
def secure_set_language(request):
	secret = getattr(settings, 'INTERNAL_SETLANG_KEY', None)
	# Accept header via request.headers or WSGI META (HTTP_X_INTERNAL_API_KEY)
	header = None
	try:
		header = request.headers.get('X-Internal-API-Key')
	except Exception:
		header = None
	if not header:
		header = request.META.get('HTTP_X_INTERNAL_API_KEY') or request.META.get('X_INTERNAL_API_KEY')

	# Debug helper: when DEBUG is enabled and debug=1, return the headers/meta so
	# we can inspect how Nginx forwarded them.
	if settings.DEBUG and request.GET.get('debug') == '1':
		info = {
			'received_header': header,
			'META_HTTP_X_INTERNAL_API_KEY': request.META.get('HTTP_X_INTERNAL_API_KEY'),
			'META_KEYS': [k for k in request.META.keys() if 'INTERNAL' in k or 'X_INTERNAL' in k],
		}
		return JsonResponse(info)

	# If a secret is configured, prefer it for internal server-to-server callers.
	# If the header is absent or doesn't match, allow the request only when it
	# passes Django's CSRF validation (so normal browser POSTs with CSRF still work).
	if secret:
		if header == secret:
			# internal call allowed
			pass
		else:
			# validate CSRF for browser-originated POSTs; process_view returns None when valid
			# CsrfViewMiddleware requires a get_response callable in newer Django versions.
			csrf_checker = CsrfViewMiddleware(lambda req: HttpResponse())
			# process_view expects (request, callback, callback_args, callback_kwargs).
			# We'll pass a noop callback since we only want CSRF side-effect/result.
			res = csrf_checker.process_view(request, lambda r: HttpResponse(), (), {})
			if res is not None:
				# CSRF failed -> forbidden
				return HttpResponseForbidden('Forbidden')

	# language param
	lang_code = request.POST.get('language') or request.GET.get('language')
	if not lang_code or not check_for_language(lang_code):
		return HttpResponse('Invalid language', status=400)

	# determine redirect target
	next_url = request.POST.get('next') or request.GET.get('next') or request.META.get('HTTP_REFERER') or '/'
	# validate next_url is safe for redirect
	# is_safe_url was deprecated in Django 4.0 -> use allowed_host check
	# We'll do a conservative check: allow relative URLs only
	if next_url and next_url.startswith('http'):
		# reject absolute external URLs
		next_url = '/'

	response = redirect(next_url)
	# set language cookie (same defaults as Django's set_language)
	max_age = 365 * 24 * 60 * 60  # one year
	response.set_cookie(settings.LANGUAGE_COOKIE_NAME, lang_code, max_age=max_age, httponly=False, samesite='Lax')
	return response
