# ...existing code...
from django.contrib.auth.forms import PasswordResetForm, SetPasswordForm
from django.contrib.auth.tokens import default_token_generator
from django.template.loader import render_to_string
from django.utils.http import urlsafe_base64_encode, urlsafe_base64_decode
from django.utils.encoding import force_bytes, force_str
from django.contrib.auth import get_user_model

def password_reset_request(request):
	from .forms import CustomPasswordResetForm
	if request.method == "POST":
		form = CustomPasswordResetForm(request.POST)
		if form.is_valid():
			email = form.cleaned_data["email"]
			user = get_user_model().objects.filter(email__iexact=email).first()
			if user:
				token = default_token_generator.make_token(user)
				uid = urlsafe_base64_encode(force_bytes(user.pk))
				# build reset link that will work when proxied under /app/
				path = reverse("accounts:password_reset_confirm", args=[uid, token])
				reset_url = request.build_absolute_uri('/app' + path)
				subject = _("Réinitialisation du mot de passe")
				# Plain text and HTML versions for better compatibility
				message_txt = render_to_string("accounts/password_reset_email.txt", {"reset_url": reset_url, "user": user})
				message_html = render_to_string("accounts/password_reset_email.html", {"reset_url": reset_url, "user": user})
				send_mail(subject, message_txt, settings.DEFAULT_FROM_EMAIL, [user.email], fail_silently=False, html_message=message_html)
				return render(request, "accounts/password_reset_sent.html", {"email": email})
			else:
				form.add_error("email", _("Aucun compte n'est associé à cette adresse e-mail."))
				return render(request, "accounts/password_reset.html", {"form": form})
	else:
		form = CustomPasswordResetForm()
	return render(request, "accounts/password_reset.html", {"form": form})

def password_reset_confirm(request, uidb64, token):
	try:
		uid = force_str(urlsafe_base64_decode(uidb64))
		user = get_user_model().objects.get(pk=uid)
	except (TypeError, ValueError, OverflowError, User.DoesNotExist):
		user = None
	from .forms import CustomSetPasswordForm
	if user and default_token_generator.check_token(user, token):
		if request.method == "POST":
			form = CustomSetPasswordForm(user, request.POST)
			if form.is_valid():
				form.save()
				return render(request, "accounts/password_reset_complete.html")
		else:
			form = CustomSetPasswordForm(user)
		return render(request, "accounts/password_reset_confirm.html", {"form": form})
	else:
		return render(request, "accounts/password_reset_invalid.html")
# ...existing code...
from django.contrib.auth import authenticate, login as auth_login, logout as auth_logout
from .forms import LoginForm

def login_view(request):
	if request.method == "POST":
		form = LoginForm(request, data=request.POST)
		if form.is_valid():
			user = form.get_user()
			if not user.email_verified:
				form.add_error(None, "Veuillez confirmer votre adresse e-mail avant de vous connecter.")
			else:
				auth_login(request, user)
				# Redirect staff directly to custom backoffice, others to user space
				if user.is_staff or user.is_superuser:
					return redirect("/app/backoffice/")
				return redirect("/app/space/")
	else:
		form = LoginForm()
	return render(request, "accounts/login.html", {"form": form})

def logout_view(request):
	auth_logout(request)
	return redirect("/app/accounts/login/")
from django.shortcuts import render


from django.contrib.auth import login
from django.shortcuts import render, redirect
from django.urls import reverse
from django.utils.translation import gettext as _
from .forms import RegisterForm
from .models import User
from django.core.mail import send_mail
from django.conf import settings
from django.utils.crypto import get_random_string
from django.utils.http import urlsafe_base64_encode, urlsafe_base64_decode
from django.utils.encoding import force_bytes, force_str
from django.contrib.auth.tokens import default_token_generator


def register(request):
	error_message = None
	if request.method == "POST":
		form = RegisterForm(request.POST)
		if form.is_valid():
			user = form.save(commit=False)
			user.is_active = False  # Inactif jusqu'à confirmation email
			user.email_verified = False
			user.company = form.cleaned_data.get("company", "")
			user.sector = form.cleaned_data.get("sector", "")
			user.save()
			# Générer un token de confirmation basé sur uid + token de Django
			uid = urlsafe_base64_encode(force_bytes(user.pk))
			token = default_token_generator.make_token(user)
			# Envoyer email de confirmation
			# ensure confirmation link points to the proxied /app/accounts/ path in production
			confirm_path = reverse("accounts:email_verify", args=[uid, token])
			confirm_url = request.build_absolute_uri('/app' + confirm_path)
			send_mail(
				_("Confirmez votre adresse e-mail"),
				_(f"Bonjour {user.username},\nCliquez sur ce lien pour confirmer votre adresse e-mail : {confirm_url}"),
				settings.DEFAULT_FROM_EMAIL,
				[user.email],
				fail_silently=False,
			)
			return render(request, "accounts/email_sent.html", {"email": user.email})
		else:
			error_message = "Veuillez corriger les erreurs ci-dessous."
	else:
		form = RegisterForm()
	return render(request, "accounts/register.html", {"form": form, "error_message": error_message})

def email_verify(request, uidb64, token):
	try:
		uid = force_str(urlsafe_base64_decode(uidb64))
		user = get_user_model().objects.get(pk=uid)
	except (TypeError, ValueError, OverflowError, User.DoesNotExist):
		user = None
	if user and default_token_generator.check_token(user, token):
		user.is_active = True
		user.email_verified = True
		user.save()
		login(request, user)
		return render(request, "accounts/email_confirmed.html", {"user": user})
	return render(request, "accounts/email_invalid.html")
