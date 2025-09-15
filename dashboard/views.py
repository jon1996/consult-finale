from django.shortcuts import render, Http404
from django.http import JsonResponse
from django.views.decorators.http import require_POST
from django.contrib.auth.decorators import login_required, user_passes_test
from .models import (
	ServiceRequest,
	Service,
	RequestEvent,
	HotelReservation,
	VehicleRental,
	ConciergerieRequest,
	AirportAssistance,
	TDGRequest,
	VisaCarteRequest,
	OtherServicesRequest,
)
from django.utils.crypto import salted_hmac
from django.utils import timezone
from datetime import timedelta
from django.core.mail import send_mail
from django.urls import reverse
from django.shortcuts import redirect
from django.views.decorators.http import require_http_methods
from django.core.files.storage import default_storage
from django.utils.text import slugify
import time
import os
from django.contrib.auth import get_user_model
from django.db.models import Count
from django.db.models.functions import TruncDate
from io import BytesIO
from PIL import Image
from django.views.decorators.http import require_http_methods
from django.contrib.auth.models import Group
from django.views.decorators.csrf import csrf_exempt
from django.conf import settings as dj_settings
from django.utils.translation import activate
from . import cinetpay as cinetpay_lib
import json


@login_required
def space(request):
	# A minimal context; extend later with real data
	return render(request, 'dashboard/space.html', {})


def _staff_required(view_func):
	return user_passes_test(lambda u: u.is_active and u.is_staff)(login_required(view_func))


@_staff_required
def backoffice(request):
	"""Staff-only admin dashboard inspired by the user space UI."""
	return render(request, 'dashboard/backoffice.html', {})


@login_required
def switch_language(request, code: str):
	"""Lightweight GET-based language switch (wraps Django's set_language).
	Accepts only languages declared in settings.LANGUAGES.
	"""
	allowed = {c for c, _ in getattr(dj_settings, 'LANGUAGES', [])}
	# Map common aliases
	alias = {'zh': 'zh-hans', 'zh-cn': 'zh-hans', 'zh-hant': 'zh-hant'}
	code_norm = alias.get(code.lower(), code.lower())
	if code_norm not in allowed:
		# Fallback to default
		code_norm = dj_settings.LANGUAGE_CODE.split('-')[0]
	# Activate for current request and set session cookie
	activate(code_norm)
	request.session["django_language"] = code_norm
	next_url = request.GET.get('next') or request.META.get('HTTP_REFERER') or '/'
	return redirect(next_url)


@login_required
def form_partial(request, key: str):
	template_map = {
		'hotel': 'forms/form-reservation-hotel.html',
		'location': 'forms/form-location-vehicule.html',
		'conciergerie': 'forms/form-conciergerie.html',
		'aeroport': 'forms/form-accompagnement-aeroport.html',
		'tdg': 'forms/form-traduction-assistance-guide.html',
		'visa': 'forms/form-visa-carte.html',
	'autres': 'forms/form-autres-services.html',
	}
	tpl = template_map.get(key)
	if not tpl:
		raise Http404()
	return render(request, tpl, {})


@login_required
@require_POST
def submit_request(request, key: str):
	# Accept only known kinds
	allowed = {'hotel', 'location', 'conciergerie', 'aeroport', 'tdg', 'visa', 'autres'}
	if key not in allowed:
		return JsonResponse({'ok': False, 'error': 'Type de service inconnu.'}, status=400)

	# Collect POST data (flat) and files (ignored for now)
	data = request.POST
	sr = ServiceRequest.objects.create(user=request.user, kind=key, data=dict(data.items()))

	# Create typed record
	if key == 'hotel':
		HotelReservation.objects.create(
			request=sr,
			ville=data.get('ville',''),
			date_arrivee=data.get('date_arrivee'),
			date_depart=data.get('date_depart'),
			hebergement=data.get('hebergement',''),
			chambre=data.get('chambre',''),
			budget=data.get('budget',''),
			besoins=request.POST.getlist('besoins[]') if hasattr(request.POST, 'getlist') else [],
			infos=data.get('infos',''),
		)
	elif key == 'location':
		services = request.POST.getlist('services[]') if hasattr(request.POST, 'getlist') else []
		VehicleRental.objects.create(
			request=sr,
			ville=data.get('ville',''),
			date_debut=data.get('date_debut'),
			heure_debut=data.get('heure_debut'),
			duree=data.get('duree',''),
			duree_autre=data.get('duree_autre',''),
			vehicule=data.get('vehicule',''),
			finalite=data.get('finalite',''),
			services=services,
			lieu_depose=data.get('lieu_depose',''),
			infos=data.get('infos',''),
		)
	elif key == 'conciergerie':
		ConciergerieRequest.objects.create(
			request=sr,
			nature=data.get('nature',''),
			urgence=data.get('urgence',''),
			contact=data.get('contact',''),
			agent=data.get('agent',''),
			details=data.get('details',''),
		)
	elif key == 'aeroport':
		AirportAssistance.objects.create(
			request=sr,
			service=data.get('service',''),
			aeroport=data.get('aeroport',''),
			date_vol=data.get('date_vol'),
			heure_vol=data.get('heure_vol'),
			num_vol=data.get('num_vol',''),
			langue=data.get('langue',''),
			langue_autre=data.get('langue_autre',''),
			services=request.POST.getlist('services[]') if hasattr(request.POST, 'getlist') else [],
			infos=data.get('infos',''),
		)
	elif key == 'tdg':
		services = request.POST.getlist('services[]') if hasattr(request.POST, 'getlist') else []
		traduction_docs = request.POST.getlist('traduction_docs[]') if hasattr(request.POST, 'getlist') else []
		assistance_options = request.POST.getlist('assistance_options[]') if hasattr(request.POST, 'getlist') else []
		guide_options = request.POST.getlist('guide_options[]') if hasattr(request.POST, 'getlist') else []
		TDGRequest.objects.create(
			request=sr,
			services=services,
			traduction_docs=traduction_docs,
			traduction_docs_autre=data.get('traduction_docs_autre',''),
			langue_source=data.get('langue_source',''),
			langue_cible=data.get('langue_cible',''),
			legalisation=data.get('legalisation',''),
			assistance_options=assistance_options,
			assistance_details=data.get('assistance_details',''),
			guide_accompagnement=data.get('guide_accompagnement',''),
			guide_langue=data.get('guide_langue',''),
			guide_lieux=data.get('guide_lieux',''),
			guide_options=guide_options,
			nom=data.get('nom',''),
			email=data.get('email',''),
			phone=data.get('phone',''),
		)
	elif key == 'visa':
		# Collect new structured fields
		but = data.get('but_sejour','')
		etab_type = data.get('etab_type','')
		# Collect text inputs into details JSON
		detail_keys = [
			# Volant
			'volant_nom','volant_nationalite','volant_lieu_naissance','volant_adresse','volant_raison','volant_preneur','volant_invitation','volant_duree','volant_date_arrivee',
			# Etablissement comunes
			'ord_nom','ord_email','ord_tel','ord_nationalite','ord_situation','ord_infos',
			'trav_nom','trav_email','trav_tel','trav_nationalite','trav_employeur','trav_infos',
			'perm_nom','perm_email','perm_tel','perm_nationalite','perm_infos',
			# Carte de travail
			'carte_nom','carte_email','carte_tel','carte_nationalite','carte_employeur','carte_infos',
		]
		details = {k: data.get(k,'') for k in detail_keys if k in data}
		# Files: process with Pillow and save into MEDIA_ROOT; store paths + meta
		upload_keys = [
			'volant_lettre','volant_passeport','volant_id_preneur','volant_rccm','volant_impot','volant_id_nat',
			'ord_lettre','ord_statuts','ord_rccm','ord_id_nat','ord_quitus','ord_inpp','ord_cnss','ord_banque',
			'trav_rccm','trav_id_nat','trav_nif','trav_photo','trav_inpp_onem','trav_medical','trav_vaccin','trav_carte_travail','trav_contrat','trav_diplome','trav_attestation_service','trav_residence','trav_bonne_vie',
			'carte_formulaire','carte_lettre','carte_etat','carte_contrat','carte_cv','carte_qualification','carte_photo','carte_organigramme','carte_poste','carte_cnss_inpp','carte_passeport',
		]
		def _process_and_save_image(file_obj, base_rel):
			"""Resize large images, convert to JPEG if needed, and create a thumbnail."""
			try:
				img = Image.open(file_obj)
				img = img.convert('RGB')
				# Limit size to ~3000px max dimension to avoid huge originals
				img.thumbnail((3000, 3000))
				buf = BytesIO()
				img.save(buf, format='JPEG', quality=85, optimize=True)
				buf.seek(0)
				# Save main
				path_main = default_storage.save(base_rel + '.jpg', buf)
				# Thumbnail
				thumb = img.copy()
				thumb.thumbnail((600, 600))
				buf_t = BytesIO()
				thumb.save(buf_t, format='JPEG', quality=80, optimize=True)
				buf_t.seek(0)
				path_thumb = default_storage.save(base_rel + '.thumb.jpg', buf_t)
				return {'path': path_main, 'thumb': path_thumb, 'format': 'JPEG'}
			except Exception:
				# Fallback: store raw file
				return {'path': default_storage.save(base_rel + os.path.splitext(file_obj.name)[1].lower(), file_obj), 'thumb': None}

		def _save_visa_uploads(sr_id:int):
			out = {}
			for fk in upload_keys:
				f = request.FILES.get(fk)
				if not f:
					continue
				name, _ = os.path.splitext(f.name)
				safe = slugify(name) or 'file'
				ts = int(time.time())
				base_rel = f"visa/{sr_id}/{fk}-{ts}-{safe}"
				out[fk] = _process_and_save_image(f, base_rel)
			return out

		documents = _save_visa_uploads(sr.id)
		VisaCarteRequest.objects.create(
			request=sr,
			but_sejour=but,
			etab_type=etab_type,
			details=details,
			documents=documents,
		)
	elif key == 'autres':
		OtherServicesRequest.objects.create(
			request=sr,
			comment=data.get('comment',''),
		)

	return JsonResponse({'ok': True, 'id': sr.id})


@login_required
def my_requests(request):
	qs = ServiceRequest.objects.filter(user=request.user).exclude(status=ServiceRequest.Status.DRAFT).order_by('-created_at')[:50]
	items = []
	for r in qs:
		items.append({
			'id': r.id,
			'kind': r.kind,
			'status': r.status,
			'created_at': r.created_at.isoformat(),
			'data': r.data,
		})
	return JsonResponse({'ok': True, 'items': items})


@_staff_required
def admin_requests(request):
	"""List latest requests for staff. Supports optional status filter and search by id/kind/user/email."""
	qs = ServiceRequest.objects.exclude(status=ServiceRequest.Status.DRAFT).order_by('-created_at')
	status = request.GET.get('status')
	q = request.GET.get('q')
	if status:
		qs = qs.filter(status=status)
	if q:
		from django.db.models import Q
		qs = qs.filter(Q(id__icontains=q) | Q(kind__icontains=q) | Q(user__username__icontains=q) | Q(user__email__icontains=q))
	qs = qs.select_related('user','service')[:100]
	items = [{
		'id': r.id,
		'kind': r.kind,
		'service': r.service.name if r.service else r.kind,
		'user': r.user.get_username(),
		'email': r.user.email,
		'status': r.status,
		'payment_method': r.payment_method,
		'created_at': r.created_at.isoformat(),
	} for r in qs]
	return JsonResponse({'ok': True, 'items': items})


@_staff_required
def admin_request_detail(request, id: int):
	sr = ServiceRequest.objects.filter(id=id).first()
	if not sr or sr.status == ServiceRequest.Status.DRAFT:
		return JsonResponse({'ok': False, 'error': 'Demande introuvable.'}, status=404)
	data = {
		'ok': True,
		'id': sr.id,
		'kind': sr.kind,
		'service': sr.service.name if sr.service else sr.kind,
		'user': sr.user.get_username(),
		'email': sr.user.email,
		'status': sr.status,
		'payment_method': sr.payment_method,
		'created_at': sr.created_at.isoformat(),
		'submitted_at': sr.submitted_at.isoformat() if sr.submitted_at else None,
		'data': sr.data or {},
	}
	# Enrich with typed details for specific kinds
	if sr.kind == 'visa':
		# Prefer documents from sr.data (wizard path), fallback to typed model (direct submit path)
		docs = {}
		if isinstance(sr.data, dict) and 'documents' in (sr.data or {}):
			docs = sr.data.get('documents') or {}
		elif hasattr(sr, 'visa') and sr.visa and isinstance(sr.visa.documents, dict):
			docs = sr.visa.documents or {}
		data['documents'] = docs
		# Include high-level fields when available from typed record
		if hasattr(sr, 'visa') and sr.visa:
			data['but_sejour'] = sr.visa.but_sejour
			data['etab_type'] = sr.visa.etab_type
			# Merge textual details for convenience (won't override existing data keys)
			try:
				details = sr.visa.details or {}
				if isinstance(details, dict):
					merged = dict(data['data'])
					for k, v in details.items():
						merged.setdefault(k, v)
					data['data'] = merged
			except Exception:
				pass
	return JsonResponse(data)


@_staff_required
@require_POST
def admin_propose_offer(request, id: int):
	"""Staff-only: propose an offer to the user with a message and a price.
	This will store admin_note and price on the ServiceRequest, generate a payment token
	(reusing existing mechanism) and email the user a message containing the admin message
	and a secure payment link that includes the price as a query parameter.
	"""
	sr = ServiceRequest.objects.filter(id=id).first()
	if not sr or sr.status == ServiceRequest.Status.DRAFT:
		return JsonResponse({'ok': False, 'error': 'Demande introuvable.'}, status=404)
	# Collect inputs
	note = request.POST.get('message', '').strip()
	price_raw = request.POST.get('price', '').strip()
	try:
		price = None
		if price_raw != '':
			from decimal import Decimal, InvalidOperation
			price = Decimal(price_raw)
	except Exception:
		return JsonResponse({'ok': False, 'error': 'Prix invalide.'}, status=400)

	# Save admin note and price
	changed = False
	if note != '':
		sr.admin_note = note; changed = True
	if price is not None:
		sr.price = price; changed = True
	if changed:
		sr.save(update_fields=['admin_note','price','updated_at'])

	# Ensure payment method exists on request (optional - payment_method can be selected later)

	# Generate a short-lived payment token so the user can pay the offered amount
	from django.conf import settings as dj_settings
	secret = getattr(request, 'settings', None).SECRET_KEY if hasattr(request, 'settings') else dj_settings.SECRET_KEY
	token, exp = _generate_payment_token(sr.user.id, sr.id, secret, minutes=60)
	sr.payment_token = token
	sr.token_expires_at = exp
	sr.status = ServiceRequest.Status.PENDING_PAYMENT
	sr.submitted_at = timezone.now()
	sr.save(update_fields=['payment_token','token_expires_at','submitted_at','status','updated_at'])

	# Build payment URL and include amount query param for convenience
	pay_url = request.build_absolute_uri(reverse('dashboard:pay') + f"?token={token}")
	if sr.price is not None:
		# Attach price to link (PSP handler can read it later). Keep as cents-safe string.
		pay_url = pay_url + f"&amount={str(sr.price)}"

	subject_user = f"Offre pour votre demande #{sr.id} – {sr.service.name if sr.service else sr.kind}"
	body_user = (
		f"Bonjour {sr.user.get_username()},\n\n"
		f"Un administrateur a proposé une offre pour votre demande #{sr.id}.\n\n"
		f"Message de l\'admin:\n{note}\n\n"
	f"Prix proposé: {sr.price if sr.price is not None else 'À définir'}\n\n"
	f"Service: {sr.service.name if sr.service else sr.kind}\n\n"
		f"Pour accepter et finaliser le paiement, utilisez ce lien sécurisé (valide 60 minutes):\n{pay_url}\n\n"
		f"Si vous avez des questions, répondez simplement à cet email.\n"
	)
	from django.conf import settings as dj_settings
	if getattr(dj_settings, 'SEND_PAYMENT_EMAILS', True):
		try:
			send_mail(subject_user, body_user, None, [sr.user.email], fail_silently=False)
			sr.email_sent_at = timezone.now(); sr.save(update_fields=['email_sent_at','updated_at'])
			RequestEvent.objects.create(request=sr, type='email.admin_offer', metadata={'ok': True, 'price': str(sr.price) if sr.price is not None else None})
		except Exception as e:
			RequestEvent.objects.create(request=sr, type='email.admin_offer', metadata={'ok': False, 'error': str(e)})
			return JsonResponse({'ok': False, 'error': "L'email n'a pas pu être envoyé."}, status=500)
	else:
		RequestEvent.objects.create(request=sr, type='email.admin_offer', metadata={'ok': False, 'skipped': True, 'reason': 'SEND_PAYMENT_EMAILS disabled', 'price': str(sr.price) if sr.price is not None else None})

	return JsonResponse({'ok': True, 'request_id': sr.id, 'pay_url': pay_url})


@_staff_required
def admin_stats(request):
	"""Simple KPIs for the overview header cards."""
	from django.utils import timezone as tz
	now = tz.now()
	last7 = now - timedelta(days=7)
	total = ServiceRequest.objects.exclude(status=ServiceRequest.Status.DRAFT).count()
	last7_count = ServiceRequest.objects.exclude(status=ServiceRequest.Status.DRAFT).filter(created_at__gte=last7).count()
	paid = ServiceRequest.objects.filter(status=ServiceRequest.Status.PAID).count()
	pending = ServiceRequest.objects.filter(status=ServiceRequest.Status.PENDING_PAYMENT).count()
	return JsonResponse({'ok': True, 'total': total, 'last7': last7_count, 'paid': paid, 'pending': pending})


@_staff_required
def admin_stats_detail(request):
	"""Detailed insights for overview: last-7-day counts, latest lists, top services."""
	base = ServiceRequest.objects.exclude(status=ServiceRequest.Status.DRAFT)
	# Recent by day (last 7 days including today)
	from django.utils import timezone as tz
	today = tz.localdate()
	start = today - timedelta(days=6)
	by_day_qs = (
		base.filter(created_at__date__gte=start)
			.annotate(d=TruncDate('created_at'))
			.values('d')
			.annotate(c=Count('id'))
			.order_by('d')
	)
	by_day_map = {row['d'].isoformat(): row['c'] for row in by_day_qs}
	recent_by_day = []
	for i in range(7):
		d = start + timedelta(days=i)
		recent_by_day.append({'date': d.isoformat(), 'count': by_day_map.get(d.isoformat(), 0)})

	# Top services (last 30 days)
	last30 = tz.now() - timedelta(days=30)
	top_qs = (
		base.filter(created_at__gte=last30)
			.values('kind')
			.annotate(c=Count('id'))
			.order_by('-c')[:6]
	)
	top_services = [{'kind': r['kind'], 'count': r['c'] } for r in top_qs]

	def _ser(q):
		return [{
			'id': r.id,
			'kind': r.kind,
			'email': r.user.email,
			'status': r.status,
			'created_at': r.created_at.isoformat(),
		} for r in q]

	latest = _ser(base.select_related('user').order_by('-created_at')[:10])
	latest_paid = _ser(base.select_related('user').filter(status=ServiceRequest.Status.PAID).order_by('-created_at')[:10])
	latest_pending = _ser(base.select_related('user').filter(status=ServiceRequest.Status.PENDING_PAYMENT).order_by('-created_at')[:10])

	return JsonResponse({
		'ok': True,
		'recent_by_day': recent_by_day,
		'top_services': top_services,
		'latest': latest,
		'latest_paid': latest_paid,
		'latest_pending': latest_pending,
	})


@_staff_required
def admin_profile(request):
	"""Return the current staff user's profile information."""
	u = request.user
	return JsonResponse({
		'ok': True,
		'user': {
			'id': u.id,
			'username': u.get_username(),
			'email': u.email,
			'first_name': u.first_name,
			'last_name': u.last_name,
			'is_staff': u.is_staff,
			'is_superuser': u.is_superuser,
		}
	})


@_staff_required
@require_POST
def admin_profile_update(request):
	"""Update current staff user's profile (firstname, lastname, email)."""
	u = request.user
	first_name = request.POST.get('first_name', '').strip()
	last_name = request.POST.get('last_name', '').strip()
	email = request.POST.get('email', '').strip()
	errors = {}
	if email == '':
		errors['email'] = 'Email requis.'
	if errors:
		return JsonResponse({'ok': False, 'errors': errors}, status=400)
	# Save minimal fields
	changed = False
	if first_name != '' and first_name != u.first_name:
		u.first_name = first_name; changed = True
	if last_name != '' and last_name != u.last_name:
		u.last_name = last_name; changed = True
	if email and email != u.email:
		User = get_user_model()
		if User.objects.exclude(id=u.id).filter(email=email).exists():
			return JsonResponse({'ok': False, 'errors': {'email': 'Email déjà utilisé.'}}, status=400)
		u.email = email; changed = True
	if changed:
		u.save(update_fields=['first_name','last_name','email'])
	return JsonResponse({'ok': True})


# --- Backoffice: Users management ---
@_staff_required
def admin_users(request):
	"""List users for staff backoffice. Supports search by username/email/name and optional staff filter."""
	User = get_user_model()
	qs = User.objects.all().order_by('-date_joined')
	q = request.GET.get('q', '').strip()
	staff = request.GET.get('staff', '').strip()
	if q:
		from django.db.models import Q
		qs = qs.filter(
			Q(username__icontains=q) |
			Q(email__icontains=q) |
			Q(first_name__icontains=q) |
			Q(last_name__icontains=q)
		)
	if staff in ('1', 'true', 'yes'):
		qs = qs.filter(is_staff=True)
	qs = qs[:100]
	items = []
	for u in qs:
		items.append({
			'id': u.id,
			'username': u.get_username(),
			'email': u.email,
			'first_name': u.first_name,
			'last_name': u.last_name,
			'is_staff': bool(getattr(u, 'is_staff', False)),
			'email_verified': bool(getattr(u, 'email_verified', False)),
			'company': getattr(u, 'company', ''),
			'sector': getattr(u, 'sector', ''),
			'phone': getattr(u, 'phone', ''),
			'date_joined': getattr(u, 'date_joined', None).isoformat() if getattr(u, 'date_joined', None) else None,
			'last_login': getattr(u, 'last_login', None).isoformat() if getattr(u, 'last_login', None) else None,
		})
	return JsonResponse({'ok': True, 'items': items})


@_staff_required
@require_POST
def admin_user_create(request):
	"""Create a new user from the backoffice. Minimal fields and validation."""
	User = get_user_model()
	username = request.POST.get('username', '').strip()
	email = request.POST.get('email', '').strip()
	password = request.POST.get('password', '').strip()
	first_name = request.POST.get('first_name', '').strip()
	last_name = request.POST.get('last_name', '').strip()
	phone = request.POST.get('phone', '').strip()
	company = request.POST.get('company', '').strip()
	sector = request.POST.get('sector', '').strip()
	is_staff_val = request.POST.get('is_staff', '').strip().lower() in ('1','true','yes','on')

	errors = {}
	if not username:
		errors['username'] = 'Nom d’utilisateur requis.'
	if not email:
		errors['email'] = 'Email requis.'
	if not password:
		errors['password'] = 'Mot de passe requis.'
	if username and User.objects.filter(username__iexact=username).exists():
		errors['username'] = 'Nom d’utilisateur déjà utilisé.'
	if email and User.objects.filter(email__iexact=email).exists():
		errors['email'] = 'Email déjà utilisé.'
	if errors:
		return JsonResponse({'ok': False, 'errors': errors}, status=400)

	# Create the user
	user = User(
		username=username,
		email=email,
		first_name=first_name,
		last_name=last_name,
		is_active=True,
		is_staff=is_staff_val,
	)
	# Optional extended fields
	if hasattr(user, 'phone'):
		user.phone = phone
	if hasattr(user, 'company'):
		user.company = company
	if hasattr(user, 'sector'):
		user.sector = sector
	if hasattr(user, 'email_verified'):
		user.email_verified = True
	user.set_password(password)
	user.save()
	return JsonResponse({'ok': True, 'id': user.id})


@_staff_required
def admin_user_detail(request, id: int):
	"""Return detailed info about a user for backoffice editing, including groups list."""
	User = get_user_model()
	u = User.objects.filter(id=id).first()
	if not u:
		return JsonResponse({'ok': False, 'error': 'Utilisateur introuvable.'}, status=404)
	groups = [{'id': g.id, 'name': g.name} for g in u.groups.all().order_by('name')]
	all_groups = [{'id': g.id, 'name': g.name} for g in Group.objects.all().order_by('name')]
	data = {
		'ok': True,
		'user': {
			'id': u.id,
			'username': u.get_username(),
			'email': u.email,
			'first_name': u.first_name,
			'last_name': u.last_name,
			'phone': getattr(u, 'phone', ''),
			'company': getattr(u, 'company', ''),
			'sector': getattr(u, 'sector', ''),
			'is_active': bool(u.is_active),
			'is_staff': bool(u.is_staff),
			'is_superuser': bool(u.is_superuser),
			'email_verified': bool(getattr(u, 'email_verified', False)),
			'date_joined': getattr(u, 'date_joined', None).isoformat() if getattr(u, 'date_joined', None) else None,
			'last_login': getattr(u, 'last_login', None).isoformat() if getattr(u, 'last_login', None) else None,
			'groups': groups,
		},
		'all_groups': all_groups,
	}
	return JsonResponse(data)


@_staff_required
@require_POST
def admin_user_update(request, id: int):
	"""Update a user's properties from backoffice. Only superusers can change is_superuser.
	Prevent locking yourself out by disallowing staff/superuser changes on self if would remove access.
	"""
	User = get_user_model()
	target = User.objects.filter(id=id).first()
	if not target:
		return JsonResponse({'ok': False, 'error': 'Utilisateur introuvable.'}, status=404)

	me = request.user
	# Collect fields
	first_name = request.POST.get('first_name', '').strip()
	last_name = request.POST.get('last_name', '').strip()
	email = request.POST.get('email', '').strip()
	phone = request.POST.get('phone', '').strip()
	company = request.POST.get('company', '').strip()
	sector = request.POST.get('sector', '').strip()
	is_active = request.POST.get('is_active', '').lower() in ('1','true','yes','on')
	is_staff = request.POST.get('is_staff', '').lower() in ('1','true','yes','on')
	is_superuser_flag = request.POST.get('is_superuser', '').lower() in ('1','true','yes','on')
	email_verified = request.POST.get('email_verified', '').lower() in ('1','true','yes','on')
	groups_ids = request.POST.getlist('groups[]') if hasattr(request.POST, 'getlist') else []

	errors = {}
	if email == '':
		errors['email'] = 'Email requis.'
	if email and User.objects.exclude(id=target.id).filter(email__iexact=email).exists():
		errors['email'] = 'Email déjà utilisé.'

	# Permission checks
	if is_superuser_flag != target.is_superuser:
		if not me.is_superuser:
			errors['is_superuser'] = "Seul un superuser peut modifier ce droit."
	# Prevent self lockout: a user cannot remove their own staff/superuser access
	if me.id == target.id:
		if target.is_superuser and not is_superuser_flag:
			errors['is_superuser'] = "Vous ne pouvez pas retirer votre propre superuser."
		if target.is_staff and not is_staff:
			errors['is_staff'] = "Vous ne pouvez pas retirer votre propre accès staff."

	if errors:
		return JsonResponse({'ok': False, 'errors': errors}, status=400)

	# Apply changes
	changed_fields = []
	def setf(attr, val):
		if getattr(target, attr) != val:
			setattr(target, attr, val)
			changed_fields.append(attr)
	if first_name:
		setf('first_name', first_name)
	if last_name:
		setf('last_name', last_name)
	if email:
		setf('email', email)
	if hasattr(target, 'phone'):
		setf('phone', phone)
	if hasattr(target, 'company'):
		setf('company', company)
	if hasattr(target, 'sector'):
		setf('sector', sector)
	setf('is_active', is_active)
	setf('is_staff', is_staff)
	if me.is_superuser:
		setf('is_superuser', is_superuser_flag)
	if hasattr(target, 'email_verified'):
		setf('email_verified', email_verified)

	if changed_fields:
		target.save(update_fields=changed_fields)

	# Groups assignment
	if groups_ids:
		try:
			ids = [int(x) for x in groups_ids]
		except Exception:
			ids = []
		new_groups = Group.objects.filter(id__in=ids)
		target.groups.set(new_groups)

	return JsonResponse({'ok': True})


@login_required
def request_detail(request, id: int):
	sr = ServiceRequest.objects.filter(id=id, user=request.user).first()
	if not sr:
		return JsonResponse({'ok': False, 'error': 'Demande introuvable.'}, status=404)
	# Hide drafts from user-facing details
	if sr.status == ServiceRequest.Status.DRAFT:
		return JsonResponse({'ok': False, 'error': 'Demande introuvable.'}, status=404)
	data = {
		'ok': True,
		'id': sr.id,
		'kind': sr.kind,
		'service': sr.service.name if sr.service else sr.kind,
		'status': sr.status,
		'payment_method': sr.payment_method,
		# Prefer per-request price; else expose default price if configured (for UI visibility of Pay button)
		'price': (str(sr.price) if sr.price is not None else (
			str(getattr(dj_settings, 'DEFAULT_PRICES', {}).get(sr.kind)) if getattr(dj_settings, 'DEFAULT_PRICES', {}).get(sr.kind) is not None else None
		)),
		'currency': getattr(dj_settings, 'CINETPAY_CURRENCY', 'XOF'),
		"alternative_currency": "",
		'created_at': sr.created_at.isoformat(),
		'submitted_at': sr.submitted_at.isoformat() if sr.submitted_at else None,
		'token_expires_at': sr.token_expires_at.isoformat() if sr.token_expires_at else None,
		'data': sr.data or {},
	}
	# Include visa documents and high-level fields for convenience
	if sr.kind == 'visa':
		docs = {}
		if isinstance(sr.data, dict) and 'documents' in (sr.data or {}):
			docs = sr.data.get('documents') or {}
		elif hasattr(sr, 'visa') and sr.visa and isinstance(sr.visa.documents, dict):
			docs = sr.visa.documents or {}
		data['documents'] = docs
		if hasattr(sr, 'visa') and sr.visa:
			data['but_sejour'] = sr.visa.but_sejour
			data['etab_type'] = sr.visa.etab_type
			try:
				details = sr.visa.details or {}
				if isinstance(details, dict):
					merged = dict(data['data'])
					for k, v in details.items():
						merged.setdefault(k, v)
					data['data'] = merged
			except Exception:
				pass
	return JsonResponse(data)


@login_required
@require_POST
def update_request(request, id: int):
	"""Allow a user to update their own request data (and files for visa).
	Permitted statuses: draft, pending_payment. Others are locked.
	Supports optional delete of existing visa document keys via delete_documents[]
	"""
	sr = ServiceRequest.objects.filter(id=id, user=request.user).first()
	if not sr:
		return JsonResponse({'ok': False, 'error': 'Demande introuvable.'}, status=404)
	# Allow edits for draft and pending_payment. For paid, allow limited edits (documents only).
	is_paid = sr.status == ServiceRequest.Status.PAID
	if sr.status not in (ServiceRequest.Status.DRAFT, ServiceRequest.Status.PENDING_PAYMENT, ServiceRequest.Status.PAID):
		return JsonResponse({'ok': False, 'error': 'Cette demande ne peut plus être modifiée.'}, status=400)

	# Merge posted fields into data
	data = dict(request.POST.items())
	cur = sr.data or {}
	# If PAID, do not overwrite textual fields; only handle document ops below
	if not is_paid:
		cur.update(data)

	# Handle document deletions (visa only)
	delete_keys = request.POST.getlist('delete_documents[]') if hasattr(request.POST, 'getlist') else []
	if delete_keys:
		docs = cur.get('documents') or {}
		for k in delete_keys:
			if k in docs:
				docs.pop(k, None)
		cur['documents'] = docs

	# Handle new file uploads (visa only)
	if sr.kind == 'visa':
		upload_keys = [
			'volant_lettre','volant_passeport','volant_id_preneur','volant_rccm','volant_impot','volant_id_nat',
			'ord_lettre','ord_statuts','ord_rccm','ord_id_nat','ord_quitus','ord_inpp','ord_cnss','ord_banque',
			'trav_rccm','trav_id_nat','trav_nif','trav_photo','trav_inpp_onem','trav_medical','trav_vaccin','trav_carte_travail','trav_contrat','trav_diplome','trav_attestation_service','trav_residence','trav_bonne_vie',
			'carte_formulaire','carte_lettre','carte_etat','carte_contrat','carte_cv','carte_qualification','carte_photo','carte_organigramme','carte_poste','carte_cnss_inpp','carte_passeport',
		]
		def _process_and_save_image_or_file(file_obj, base_rel):
			# Try image pipeline; fallback to raw file (e.g., PDF)
			try:
				img = Image.open(file_obj).convert('RGB')
				img.thumbnail((3000, 3000))
				buf = BytesIO(); img.save(buf, format='JPEG', quality=85, optimize=True); buf.seek(0)
				path_main = default_storage.save(base_rel + '.jpg', buf)
				thumb = img.copy(); thumb.thumbnail((600, 600))
				buf_t = BytesIO(); thumb.save(buf_t, format='JPEG', quality=80, optimize=True); buf_t.seek(0)
				path_thumb = default_storage.save(base_rel + '.thumb.jpg', buf_t)
				return {'path': path_main, 'thumb': path_thumb, 'format': 'JPEG'}
			except Exception:
				ext = os.path.splitext(file_obj.name)[1].lower() or ''
				saved = default_storage.save(base_rel + ext, file_obj)
				fmt = 'PDF' if ext == '.pdf' else (ext.lstrip('.') or 'file')
				return {'path': saved, 'thumb': None, 'format': fmt.upper()}

		out = cur.get('documents') or {}
		for fk in upload_keys:
			f = request.FILES.get(fk)
			if not f:
				continue
			name, _ = os.path.splitext(f.name)
			safe = slugify(name) or 'file'
			ts = int(timezone.now().timestamp())
			base_rel = f"visa/{sr.id}/{fk}-{ts}-{safe}"
			out[fk] = _process_and_save_image_or_file(f, base_rel)
		if out:
			cur['documents'] = out

	sr.data = cur
	sr.save(update_fields=['data','updated_at'])
	# Sync typed record for visa if it exists
	if sr.kind == 'visa' and hasattr(sr, 'visa') and sr.visa:
		v = sr.visa
		if not is_paid:
			# Update high-level fields if provided
			if 'but_sejour' in data:
				v.but_sejour = data.get('but_sejour') or v.but_sejour
			if 'etab_type' in data:
				v.etab_type = data.get('etab_type') or v.etab_type
			# Merge textual details from posted data (limited to expected prefixes)
			prefixes = ('volant_', 'ord_', 'trav_', 'perm_', 'carte_')
			details = dict(v.details or {})
			for k, val in data.items():
				if any(k.startswith(p) for p in prefixes):
					details[k] = val
			v.details = details
		# Replace or set documents snapshot (always allowed)
		if isinstance(cur.get('documents'), dict):
			v.documents = cur.get('documents')
		v.save(update_fields=['but_sejour','etab_type','details','documents'])
	RequestEvent.objects.create(request=sr, type='request.update', metadata={'keys': list(data.keys())})
	return JsonResponse({'ok': True})


@login_required
@require_POST
def update_request_payment_method(request, id: int):
	"""Allow a user to update the payment method for an existing request while editing.
	Permitted statuses: draft, pending_payment. Paid/canceled are locked for payment changes.
	"""
	sr = ServiceRequest.objects.filter(id=id, user=request.user).first()
	if not sr:
		return JsonResponse({'ok': False, 'error': 'Demande introuvable.'}, status=404)
	if sr.status not in (ServiceRequest.Status.DRAFT, ServiceRequest.Status.PENDING_PAYMENT):
		return JsonResponse({'ok': False, 'error': 'Méthode de paiement non modifiable pour cet état.'}, status=400)
	method = request.POST.get('payment_method')
	if not method:
		return JsonResponse({'ok': False, 'error': 'Méthode de paiement requise.'}, status=400)
	sr.payment_method = method
	sr.save(update_fields=['payment_method','updated_at'])
	RequestEvent.objects.create(request=sr, type='request.payment_method', metadata={'method': method})
	return JsonResponse({'ok': True})


@login_required
@require_POST
def resubmit_request(request, id: int):
	"""Regenerate a payment token and (re)send the payment email for an existing request being edited.
	Allowed when status is draft or pending_payment. Creates typed record if missing.
	"""
	sr = ServiceRequest.objects.filter(id=id, user=request.user).first()
	if not sr:
		return JsonResponse({'ok': False, 'error': 'Demande introuvable.'}, status=404)
	if sr.status not in (ServiceRequest.Status.DRAFT, ServiceRequest.Status.PENDING_PAYMENT):
		return JsonResponse({'ok': False, 'error': 'Cette demande ne peut pas être renvoyée.'}, status=400)
	if not sr.payment_method:
		return JsonResponse({'ok': False, 'error': 'Méthode de paiement manquante.'}, status=400)
	if not sr.data:
		return JsonResponse({'ok': False, 'error': 'Détails requis.'}, status=400)

	# Ensure typed record exists as in wizard_submit
	created_typed = False
	if sr.kind == 'hotel' and not hasattr(sr, 'hotel'):
		HotelReservation.objects.create(
			request=sr,
			ville=sr.data.get('ville',''),
			date_arrivee=sr.data.get('date_arrivee'),
			date_depart=sr.data.get('date_depart'),
			hebergement=sr.data.get('hebergement',''),
			chambre=sr.data.get('chambre',''),
			budget=sr.data.get('budget',''),
			besoins=sr.data.get('besoins[]', []),
			infos=sr.data.get('infos',''),
		); created_typed = True
	elif sr.kind == 'location' and not hasattr(sr, 'location'):
		VehicleRental.objects.create(
			request=sr,
			ville=sr.data.get('ville',''),
			date_debut=sr.data.get('date_debut'),
			heure_debut=sr.data.get('heure_debut'),
			duree=sr.data.get('duree',''),
			duree_autre=sr.data.get('duree_autre',''),
			vehicule=sr.data.get('vehicule',''),
			finalite=sr.data.get('finalite',''),
			services=sr.data.get('services[]', []),
			lieu_depose=sr.data.get('lieu_depose',''),
			infos=sr.data.get('infos',''),
		); created_typed = True
	elif sr.kind == 'conciergerie' and not hasattr(sr, 'conciergerie'):
		ConciergerieRequest.objects.create(
			request=sr,
			nature=sr.data.get('nature',''),
			urgence=sr.data.get('urgence',''),
			contact=sr.data.get('contact',''),
			agent=sr.data.get('agent',''),
			details=sr.data.get('details',''),
		); created_typed = True
	elif sr.kind == 'aeroport' and not hasattr(sr, 'aeroport'):
		AirportAssistance.objects.create(
			request=sr,
			service=sr.data.get('service',''),
			aeroport=sr.data.get('aeroport',''),
			date_vol=sr.data.get('date_vol'),
			heure_vol=sr.data.get('heure_vol'),
			num_vol=sr.data.get('num_vol',''),
			langue=sr.data.get('langue',''),
			langue_autre=sr.data.get('langue_autre',''),
			services=sr.data.get('services[]', []),
			infos=sr.data.get('infos',''),
		); created_typed = True
	elif sr.kind == 'tdg' and not hasattr(sr, 'tdg'):
		TDGRequest.objects.create(
			request=sr,
			services=sr.data.get('services[]', []),
			traduction_docs=sr.data.get('traduction_docs[]', []),
			traduction_docs_autre=sr.data.get('traduction_docs_autre',''),
			langue_source=sr.data.get('langue_source',''),
			langue_cible=sr.data.get('langue_cible',''),
			legalisation=sr.data.get('legalisation',''),
			assistance_options=sr.data.get('assistance_options[]', []),
			assistance_details=sr.data.get('assistance_details',''),
			guide_accompagnement=sr.data.get('guide_accompagnement',''),
			guide_langue=sr.data.get('guide_langue',''),
			guide_lieux=sr.data.get('guide_lieux',''),
			guide_options=sr.data.get('guide_options[]', []),
			nom=sr.data.get('nom',''),
			email=sr.data.get('email',''),
			phone=sr.data.get('phone',''),
		); created_typed = True
	elif sr.kind == 'visa' and not hasattr(sr, 'visa'):
		VisaCarteRequest.objects.create(
			request=sr,
			but_sejour=sr.data.get('but_sejour',''),
			etab_type=sr.data.get('etab_type',''),
			details={k:v for k,v in (sr.data or {}).items() if isinstance(v, str) and (k.startswith('volant_') or k.startswith('ord_') or k.startswith('trav_') or k.startswith('perm_') or k.startswith('carte_'))},
			documents=sr.data.get('documents', {}),
		); created_typed = True
	elif sr.kind == 'autres' and not hasattr(sr, 'autres'):
		OtherServicesRequest.objects.create(
			request=sr,
			comment=sr.data.get('comment',''),
		); created_typed = True

	# Generate/refresh payment token and send emails
	from django.conf import settings as dj_settings
	secret = getattr(request, 'settings', None).SECRET_KEY if hasattr(request, 'settings') else dj_settings.SECRET_KEY
	token, exp = _generate_payment_token(request.user.id, sr.id, secret, minutes=60)
	sr.payment_token = token
	sr.token_expires_at = exp
	sr.submitted_at = timezone.now()
	sr.status = ServiceRequest.Status.PENDING_PAYMENT
	sr.save(update_fields=['payment_token','token_expires_at','submitted_at','status','updated_at'])
	RequestEvent.objects.create(request=sr, type='request.resubmit', metadata={'typed_created': created_typed})

	# Emails
	pay_url = request.build_absolute_uri(reverse('dashboard:pay') + f"?token={token}")
	subject_user = f"Finalisez votre paiement – {sr.service.name if sr.service else sr.kind}"
	body_user = (
		f"Bonjour {request.user.get_username()},\n\n"
		f"Votre demande a été mise à jour. Merci de finaliser votre paiement via ce lien sécurisé (valide 60 minutes):\n{pay_url}\n\n"
		f"Service: {sr.service.name if sr.service else sr.kind}\n"
		f"Méthode de paiement: {sr.payment_method}\n"
	)
	# Optionally skip sending payment emails when disabled by configuration
	from django.conf import settings as dj_settings
	if getattr(dj_settings, 'SEND_PAYMENT_EMAILS', True):
		try:
			send_mail(subject_user, body_user, None, [request.user.email], fail_silently=False)
			sr.email_sent_at = timezone.now()
			sr.save(update_fields=['email_sent_at','updated_at'])
			RequestEvent.objects.create(request=sr, type='email.user_payment_link', metadata={'ok': True})
		except Exception as e:
			RequestEvent.objects.create(request=sr, type='email.user_payment_link', metadata={'ok': False, 'error': str(e)})
			return JsonResponse({'ok': False, 'error': "L'email n'a pas pu être envoyé. Réessayez ou contactez le support."}, status=500)
	else:
		# Log event that email was intentionally skipped
		RequestEvent.objects.create(request=sr, type='email.user_payment_link', metadata={'ok': False, 'skipped': True, 'reason': 'SEND_PAYMENT_EMAILS disabled'})

	# Notify admin (basic)
	try:
		send_mail(
			f"Demande mise à jour en attente de paiement – {sr.service.name if sr.service else sr.kind}",
			f"Request #{sr.id} par {request.user.get_username()} ({request.user.email})\nStatus: {sr.status}",
			None,
			[
				# Replace with your admin email env or setting later
			],
			fail_silently=True,
		)
		RequestEvent.objects.create(request=sr, type='email.admin_resubmit', metadata={'ok': True})
	except Exception:
		RequestEvent.objects.create(request=sr, type='email.admin_resubmit', metadata={'ok': False})

	return JsonResponse({'ok': True, 'request_id': sr.id, 'pay_url': pay_url})


# --- Wizard API ---
def _get_or_create_draft(user):
	sr = ServiceRequest.objects.filter(user=user, status=ServiceRequest.Status.DRAFT).order_by('-created_at').first()
	if sr:
		return sr
	return ServiceRequest.objects.create(user=user, kind='', data={})


@login_required
@require_POST
def wizard_service(request):
	key = request.POST.get('service')
	if not key:
		return JsonResponse({'ok': False, 'error': 'Service requis.'}, status=400)
	# Find or create the configured Service
	svc = Service.objects.filter(key=key, active=True).first()
	if not svc:
		# Fall back to known kinds (existing map)
		allowed = {'hotel','location','conciergerie','aeroport','tdg','visa','autres'}
		if key not in allowed:
			return JsonResponse({'ok': False, 'error': 'Service inconnu.'}, status=400)
		svc = None
	sr = _get_or_create_draft(request.user)
	sr.kind = key
	sr.service = svc
	sr.save(update_fields=['kind','service','updated_at'])
	RequestEvent.objects.create(request=sr, type='wizard.service', metadata={'service': key})
	return JsonResponse({'ok': True, 'request_id': sr.id})


@login_required
@require_POST
def wizard_details(request):
	sr = _get_or_create_draft(request.user)
	if not sr.kind:
		return JsonResponse({'ok': False, 'error': 'Aucun service sélectionné.'}, status=400)

	# Basic server-side required validation by kind (minimal example)
	errors = {}
	data = request.POST
	kind = sr.kind
	def req(field):
		if not data.get(field):
			errors[field] = 'Champ obligatoire'

	if kind == 'hotel':
		for f in ['ville','date_arrivee','date_depart','hebergement','chambre','budget']:
			req(f)
	elif kind == 'location':
		for f in ['ville','date_debut','heure_debut','duree','vehicule','finalite']:
			req(f)
	elif kind == 'conciergerie':
		for f in ['nature','urgence','contact','agent']:
			req(f)
	elif kind == 'aeroport':
		for f in ['service','aeroport','date_vol','heure_vol','langue']:
			req(f)
	elif kind == 'tdg':
		for f in ['nom','email','phone']:
			req(f)
	elif kind == 'visa':
		for f in ['but_sejour']:
			req(f)

	if errors:
		return JsonResponse({'ok': False, 'errors': errors}, status=400)

	# Save raw data snapshot, preserving multi-value fields like besoins[] / services[] and other arrays
	data_dict = dict(data.items())
	for mv in (
		'besoins[]',
		'services[]',
		'traduction_docs[]',
		'assistance_options[]',
		'guide_options[]',
	):
		vals = request.POST.getlist(mv) if hasattr(request.POST, 'getlist') else []
		if vals:
			data_dict[mv] = vals

	# Handle file uploads for specific kinds (e.g., visa)
	if kind == 'visa':
		upload_keys = [
			'volant_lettre','volant_passeport','volant_id_preneur','volant_rccm','volant_impot','volant_id_nat',
			'ord_lettre','ord_statuts','ord_rccm','ord_id_nat','ord_quitus','ord_inpp','ord_cnss','ord_banque',
			'trav_rccm','trav_id_nat','trav_nif','trav_photo','trav_inpp_onem','trav_medical','trav_vaccin','trav_carte_travail','trav_contrat','trav_diplome','trav_attestation_service','trav_residence','trav_bonne_vie',
			'carte_formulaire','carte_lettre','carte_etat','carte_contrat','carte_cv','carte_qualification','carte_photo','carte_organigramme','carte_poste','carte_cnss_inpp','carte_passeport',
		]
		def _process_and_save_image(file_obj, base_rel):
			try:
				img = Image.open(file_obj).convert('RGB')
				img.thumbnail((3000, 3000))
				buf = BytesIO(); img.save(buf, format='JPEG', quality=85, optimize=True); buf.seek(0)
				path_main = default_storage.save(base_rel + '.jpg', buf)
				thumb = img.copy(); thumb.thumbnail((600, 600))
				buf_t = BytesIO(); thumb.save(buf_t, format='JPEG', quality=80, optimize=True); buf_t.seek(0)
				path_thumb = default_storage.save(base_rel + '.thumb.jpg', buf_t)
				return {'path': path_main, 'thumb': path_thumb, 'format': 'JPEG'}
			except Exception:
				return {'path': default_storage.save(base_rel + os.path.splitext(file_obj.name)[1].lower(), file_obj), 'thumb': None}
		out = {}
		for fk in upload_keys:
			f = request.FILES.get(fk)
			if not f:
				continue
			name, _ = os.path.splitext(f.name)
			safe = slugify(name) or 'file'
			ts = int(timezone.now().timestamp())
			base_rel = f"visa/{sr.id}/{fk}-{ts}-{safe}"
			out[fk] = _process_and_save_image(f, base_rel)
		if out:
			data_dict['documents'] = out
	sr.data = data_dict
	sr.save(update_fields=['data','updated_at'])
	RequestEvent.objects.create(request=sr, type='wizard.details', metadata={'fields': list(sr.data.keys())})
	return JsonResponse({'ok': True})


@login_required
@require_POST
def wizard_payment_method(request):
	sr = _get_or_create_draft(request.user)
	method = request.POST.get('payment_method')
	if not method:
		return JsonResponse({'ok': False, 'error': 'Méthode de paiement requise.'}, status=400)
	sr.payment_method = method
	sr.save(update_fields=['payment_method','updated_at'])
	RequestEvent.objects.create(request=sr, type='wizard.payment_method', metadata={'method': method})
	return JsonResponse({'ok': True})


def _generate_payment_token(user_id: int, request_id: int, secret: str, minutes: int = 60) -> tuple[str, timezone.datetime]:
	message = f"pay:{user_id}:{request_id}:{int(timezone.now().timestamp())}"
	sig = salted_hmac('payment', message, secret=secret).hexdigest()
	expires = timezone.now() + timedelta(minutes=minutes)
	token = f"{request_id}.{sig}"
	return token, expires


@login_required
@require_POST
def wizard_submit(request):
	sr = _get_or_create_draft(request.user)
	if not sr.kind:
		return JsonResponse({'ok': False, 'error': 'Service manquant.'}, status=400)
	if not sr.payment_method:
		return JsonResponse({'ok': False, 'error': 'Méthode de paiement manquante.'}, status=400)

	# Ensure details exist minimally
	if not sr.data:
		return JsonResponse({'ok': False, 'error': 'Détails requis.'}, status=400)

	# Promote to pending_payment and create typed record if not exists
	# Reuse the existing submit_request mapper for typed creation when needed
	# Here we only ensure there is a typed record; if none, create one now.
	created_typed = False
	if sr.kind == 'hotel' and not hasattr(sr, 'hotel'):
		HotelReservation.objects.create(
			request=sr,
			ville=sr.data.get('ville',''),
			date_arrivee=sr.data.get('date_arrivee'),
			date_depart=sr.data.get('date_depart'),
			hebergement=sr.data.get('hebergement',''),
			chambre=sr.data.get('chambre',''),
			budget=sr.data.get('budget',''),
			besoins=sr.data.get('besoins[]', []),
			infos=sr.data.get('infos',''),
		); created_typed = True
	elif sr.kind == 'location' and not hasattr(sr, 'location'):
		VehicleRental.objects.create(
			request=sr,
			ville=sr.data.get('ville',''),
			date_debut=sr.data.get('date_debut'),
			heure_debut=sr.data.get('heure_debut'),
			duree=sr.data.get('duree',''),
			duree_autre=sr.data.get('duree_autre',''),
			vehicule=sr.data.get('vehicule',''),
			finalite=sr.data.get('finalite',''),
			services=sr.data.get('services[]', []),
			lieu_depose=sr.data.get('lieu_depose',''),
			infos=sr.data.get('infos',''),
		); created_typed = True
	elif sr.kind == 'conciergerie' and not hasattr(sr, 'conciergerie'):
		ConciergerieRequest.objects.create(
			request=sr,
			nature=sr.data.get('nature',''),
			urgence=sr.data.get('urgence',''),
			contact=sr.data.get('contact',''),
			agent=sr.data.get('agent',''),
			details=sr.data.get('details',''),
		); created_typed = True
	elif sr.kind == 'aeroport' and not hasattr(sr, 'aeroport'):
		AirportAssistance.objects.create(
			request=sr,
			service=sr.data.get('service',''),
			aeroport=sr.data.get('aeroport',''),
			date_vol=sr.data.get('date_vol'),
			heure_vol=sr.data.get('heure_vol'),
			num_vol=sr.data.get('num_vol',''),
			langue=sr.data.get('langue',''),
			langue_autre=sr.data.get('langue_autre',''),
			services=sr.data.get('services[]', []),
			infos=sr.data.get('infos',''),
		); created_typed = True
	elif sr.kind == 'tdg' and not hasattr(sr, 'tdg'):
		TDGRequest.objects.create(
			request=sr,
			services=sr.data.get('services[]', []),
			traduction_docs=sr.data.get('traduction_docs[]', []),
			traduction_docs_autre=sr.data.get('traduction_docs_autre',''),
			langue_source=sr.data.get('langue_source',''),
			langue_cible=sr.data.get('langue_cible',''),
			legalisation=sr.data.get('legalisation',''),
			assistance_options=sr.data.get('assistance_options[]', []),
			assistance_details=sr.data.get('assistance_details',''),
			guide_accompagnement=sr.data.get('guide_accompagnement',''),
			guide_langue=sr.data.get('guide_langue',''),
			guide_lieux=sr.data.get('guide_lieux',''),
			guide_options=sr.data.get('guide_options[]', []),
			nom=sr.data.get('nom',''),
			email=sr.data.get('email',''),
			phone=sr.data.get('phone',''),
		); created_typed = True
	elif sr.kind == 'visa' and not hasattr(sr, 'visa'):
		VisaCarteRequest.objects.create(
			request=sr,
			but_sejour=sr.data.get('but_sejour',''),
			etab_type=sr.data.get('etab_type',''),
			details={k:v for k,v in (sr.data or {}).items() if isinstance(v, str) and (k.startswith('volant_') or k.startswith('ord_') or k.startswith('trav_') or k.startswith('perm_') or k.startswith('carte_'))},
			documents=sr.data.get('documents', {}),
		); created_typed = True
	elif sr.kind == 'autres' and not hasattr(sr, 'autres'):
		OtherServicesRequest.objects.create(
			request=sr,
			comment=sr.data.get('comment',''),
		); created_typed = True

	# Payment token
	secret = request.settings.SECRET_KEY if hasattr(request, 'settings') else None
	if not secret:
		from django.conf import settings as dj_settings
		secret = dj_settings.SECRET_KEY
	token, exp = _generate_payment_token(request.user.id, sr.id, secret, minutes=60)
	sr.payment_token = token
	sr.token_expires_at = exp
	sr.submitted_at = timezone.now()
	sr.status = ServiceRequest.Status.PENDING_PAYMENT
	sr.save(update_fields=['payment_token','token_expires_at','submitted_at','status','updated_at'])
	RequestEvent.objects.create(request=sr, type='wizard.submit', metadata={'typed_created': created_typed})

	# Emails
	pay_url = request.build_absolute_uri(reverse('dashboard:pay') + f"?token={token}")
	subject_user = f"Finalisez votre paiement – {sr.service.name if sr.service else sr.kind}"
	body_user = (
		f"Bonjour {request.user.get_username()},\n\n"
		f"Votre demande a été enregistrée. Merci de finaliser votre paiement via ce lien sécurisé (valide 60 minutes):\n{pay_url}\n\n"
		f"Service: {sr.service.name if sr.service else sr.kind}\n"
		f"Méthode de paiement: {sr.payment_method}\n"
	)
	from django.conf import settings as dj_settings
	if getattr(dj_settings, 'SEND_PAYMENT_EMAILS', True):
		try:
			send_mail(subject_user, body_user, None, [request.user.email], fail_silently=False)
			sr.email_sent_at = timezone.now()
			sr.save(update_fields=['email_sent_at','updated_at'])
			RequestEvent.objects.create(request=sr, type='email.user_payment_link', metadata={'ok': True})
		except Exception as e:
			RequestEvent.objects.create(request=sr, type='email.user_payment_link', metadata={'ok': False, 'error': str(e)})
			return JsonResponse({'ok': False, 'error': "L'email n'a pas pu être envoyé. Réessayez ou contactez le support."}, status=500)
	else:
		RequestEvent.objects.create(request=sr, type='email.user_payment_link', metadata={'ok': False, 'skipped': True, 'reason': 'SEND_PAYMENT_EMAILS disabled'})

	# Notify admin (basic)
	try:
		send_mail(
			f"Nouvelle demande en attente de paiement – {sr.service.name if sr.service else sr.kind}",
			f"Request #{sr.id} par {request.user.get_username()} ({request.user.email})\nStatus: {sr.status}",
			None,
			[
				# Replace with your admin email env or setting later
			],
			fail_silently=True,
		)
		RequestEvent.objects.create(request=sr, type='email.admin_new_request', metadata={'ok': True})
	except Exception:
		RequestEvent.objects.create(request=sr, type='email.admin_new_request', metadata={'ok': False})

	return JsonResponse({'ok': True, 'request_id': sr.id, 'pay_url': pay_url})


def pay(request):
	"""Landing page for payment link verification. For now returns JSON; wire to template later."""
	token = request.GET.get('token')
	if not token:
		return JsonResponse({'ok': False, 'error': 'Token manquant.'}, status=400)
	try:
		req_id_str, sig = token.split('.', 1)
		req_id = int(req_id_str)
	except Exception:
		return JsonResponse({'ok': False, 'error': 'Token invalide.'}, status=400)

	from django.conf import settings as dj_settings
	sr = ServiceRequest.objects.filter(id=req_id).first()
	if not sr or not sr.payment_token:
		return JsonResponse({'ok': False, 'error': 'Lien invalide.'}, status=404)
	if sr.payment_token != token:
		return JsonResponse({'ok': False, 'error': 'Signature invalide.'}, status=400)
	if sr.token_expires_at and timezone.now() > sr.token_expires_at:
		return JsonResponse({'ok': False, 'error': 'Lien expiré.'}, status=410)

	# Here you would render a payment page or redirect to a PSP
	return JsonResponse({'ok': True, 'request_id': sr.id, 'status': sr.status})


@login_required
@require_POST
def cinetpay_start_payment(request, id: int):
	"""Initiate a CinetPay payment for a ServiceRequest and return payment_url (JSON).
	Expects the request to belong to the logged-in user or the user to be staff.
	"""
	sr = ServiceRequest.objects.filter(id=id).first()
	if not sr or sr.status == ServiceRequest.Status.DRAFT:
		return JsonResponse({'ok': False, 'error': 'Demande introuvable.'}, status=404)
	# Only owner or staff can start a payment
	if not (request.user == sr.user or request.user.is_staff):
		return JsonResponse({'ok': False, 'error': 'Accès refusé.'}, status=403)

	# Determine amount: prefer per-request price; else fallback to default per kind if configured.
	amount = sr.price
	if amount is None:
		default_prices = getattr(dj_settings, 'DEFAULT_PRICES', {}) if 'dj_settings' in globals() else {}
		try:
			amount = default_prices.get(sr.kind)
		except Exception:
			amount = None
	if amount in (None, 0, 0.0):
		return JsonResponse({'ok': False, 'error': 'Aucun montant défini pour cette demande.'}, status=400)

	# Build a transaction id and payload according to CinetPay docs
	transaction_id = f"sr-{sr.id}-{int(time.time())}"
	# Prepare customer fields
	full_name = (request.user.get_full_name() or request.user.username or '').strip()
	first_name = ''
	last_name = ''
	if ' ' in full_name:
		first_name, last_name = full_name.split(' ', 1)
	else:
		first_name = full_name
	user_phone = getattr(request.user, 'phone', '') if hasattr(request.user, 'phone') else ''

	# Build payload for CinetPay create payment
	site_id = getattr(dj_settings, 'CINETPAY_SITE_ID', '')
	api_key = getattr(dj_settings, 'CINETPAY_API_KEY', '')
	if not site_id:
		return JsonResponse({'ok': False, 'error': "Configuration CinetPay manquante: site_id."}, status=500)
	if not api_key:
		return JsonResponse({'ok': False, 'error': "Configuration CinetPay manquante: api_key."}, status=500)
	payload = {
		'site_id': str(site_id),
		'apikey': str(api_key),
		# amount as string with 2 decimals to avoid type issues
		'amount': f"{float(amount):.2f}",
		'currency': getattr(dj_settings, 'CINETPAY_CURRENCY', 'XOF'),
		"alternative_currency": "",
		'transaction_id': transaction_id,
		'description': f"Paiement demande #{sr.id}",
		'return_url': request.build_absolute_uri(reverse('dashboard:cinetpay_return')),
		'notify_url': request.build_absolute_uri(reverse('dashboard:cinetpay_notify')),
		'customer_name': first_name or full_name,
		'customer_surname': last_name,
		'customer_email': request.user.email,
		# Optional but commonly accepted fields
		'channels': 'ALL',
		'lang': (getattr(dj_settings, 'LANGUAGE_CODE', 'fr') or 'fr')[:2],
		'customer_phone_number': user_phone,
	}
	try:
		resp = cinetpay_lib.initiate_payment(payload)
	except Exception as e:
		# Try to capture more info if it's a requests error
		meta = {'error': str(e), 'sent_keys': sorted(list(payload.keys())), 'site_id_present': bool(payload.get('site_id'))}
		try:
			import requests
			if isinstance(e, requests.HTTPError) and getattr(e, 'response', None) is not None:
				meta['status_code'] = e.response.status_code
				meta['response_text'] = e.response.text[:1000]
		except Exception:
			pass
		# Include target URL for visibility
		try:
			meta['url'] = f"{dj_settings.CINETPAY_API_BASE.rstrip('/')}/v2/payment"
		except Exception:
			pass
		RequestEvent.objects.create(request=sr, type='cinetpay.initiate.error', metadata=meta)
		# Slightly more detailed error in DEBUG to aid testing
		msg = 'Impossible de créer le paiement.'
		if getattr(dj_settings, 'DEBUG', False) and meta.get('status_code'):
			# surface part of response to help diagnose (safe in DEBUG)
			body_snip = meta.get('response_text')
			if body_snip:
				body_snip = body_snip.strip().replace('\n', ' ')
				if len(body_snip) > 300:
					body_snip = body_snip[:300] + '…'
				msg = f"Impossible de créer le paiement (HTTP {meta.get('status_code')}): {body_snip}"
			else:
				msg = f"Impossible de créer le paiement (HTTP {meta.get('status_code')})."
		return JsonResponse({'ok': False, 'error': msg}, status=502)

	# According to doc: resp.data.payment_token and resp.data.payment_url
	payment_token = None
	payment_url = None
	if isinstance(resp, dict):
		data = resp.get('data') or {}
		payment_token = data.get('payment_token') or resp.get('payment_token')
		payment_url = data.get('payment_url') or resp.get('payment_url')

	# Persist tokens for later matching
	if payment_token:
		sr.payment_token = payment_token
	else:
		# fallback: save our generated transaction id
		sr.payment_token = transaction_id
	sr.payment_method = 'cinetpay'
	# keep transaction id in sr.data for reference
	sr.data = sr.data or {}
	sr.data['cinetpay_transaction_id'] = transaction_id
	sr.save(update_fields=['payment_token', 'payment_method', 'data', 'updated_at'])
	RequestEvent.objects.create(request=sr, type='cinetpay.initiated', metadata={'request': payload, 'response': resp})

	if not payment_url:
		return JsonResponse({'ok': False, 'error': "Pas d'URL de paiement fournie par CinetPay."}, status=502)
	return JsonResponse({'ok': True, 'payment_url': payment_url, 'payment_token': payment_token})


@csrf_exempt
@require_POST
def cinetpay_notify(request):
	"""Webhook receiver for CinetPay notifications. Verifies transaction and updates ServiceRequest."""
	# Parse payload robustly based on Content-Type
	content_type = request.META.get('CONTENT_TYPE', '') or request.content_type or ''
	body = {}
	if 'application/json' in content_type:
		try:
			body = json.loads(request.body.decode('utf-8'))
		except Exception:
			body = {}
	elif 'application/x-www-form-urlencoded' in content_type or 'multipart/form-data' in content_type:
		body = request.POST.dict()
	else:
		# Try JSON first, then fallback to POST
		try:
			body = json.loads(request.body.decode('utf-8'))
		except Exception:
			body = request.POST.dict()

	# Validate HMAC token header if provided
	received_xtoken = request.META.get('HTTP_X_TOKEN') or request.META.get('HTTP_X-TOKEN') or request.META.get('X-TOKEN')

	# Some clients (test client) may send the whole dict as a single string key (repr of dict).
	# Detect and recover: if body is a dict with one key that looks like a Python dict string,
	# try to parse it with ast.literal_eval then fallback to json.loads.
	if isinstance(body, dict) and len(body) == 1:
		first_key = next(iter(body.keys()))
		if isinstance(first_key, str) and first_key.strip().startswith('{') and first_key.strip().endswith('}'):
			try:
				import ast
				parsed = ast.literal_eval(first_key)
				if isinstance(parsed, dict):
					body = parsed
			except Exception:
				try:
					body = json.loads(first_key)
				except Exception:
					pass
	if received_xtoken and getattr(dj_settings, 'CINETPAY_SECRET_KEY', ''):
		valid = cinetpay_lib.validate_hmac_x_token(body, received_xtoken, dj_settings.CINETPAY_SECRET_KEY)
		if not valid:
			# Avoid creating RequestEvent with null FK; try to attach to sr if we can find it,
			# otherwise skip creating the DB event and return 403.
			tx_try = body.get('cpm_trans_id')
			sr_try = ServiceRequest.objects.filter(payment_token__icontains=str(tx_try)).first() if tx_try else None
			# Compute generated token for logging
			gen = cinetpay_lib.make_hmac_token(body, dj_settings.CINETPAY_SECRET_KEY)
			# Sanitize headers and body for JSONField storage
			safe_headers = {
				'HTTP_X_TOKEN': request.META.get('HTTP_X_TOKEN') or request.META.get('HTTP_X-TOKEN') or request.META.get('X-TOKEN'),
				'CONTENT_TYPE': request.META.get('CONTENT_TYPE') or request.content_type,
				'REMOTE_ADDR': request.META.get('REMOTE_ADDR'),
			}
			# Ensure body is JSON-serializable; fallback to string
			try:
				import json as _json
				_json.dumps(body)
				safe_body = body
			except Exception:
				safe_body = str(body)
			if sr_try:
				RequestEvent.objects.create(request=sr_try, type='cinetpay.hmac.invalid', metadata={'headers': safe_headers, 'body': safe_body, 'generated': gen, 'received': received_xtoken})
			return JsonResponse({'ok': False, 'error': 'invalid_hmac'}, status=403)

	# Transaction id and site id use CinetPay naming
	transaction_id = body.get('cpm_trans_id') or body.get('transaction_id') or body.get('transaction')
	site_id = body.get('cpm_site_id') or body.get('site_id')
	if not transaction_id or not site_id:
		# Missing required fields; cannot reliably attach to a request so skip RequestEvent creation
		return JsonResponse({'ok': False, 'error': 'missing_fields'}, status=400)

	# Find ServiceRequest by payment_token or stored data
	sr = ServiceRequest.objects.filter(payment_token__icontains=str(transaction_id)).first()
	if not sr:
		sr = ServiceRequest.objects.filter(data__cinetpay_transaction_id=str(transaction_id)).first()
	if not sr:
		# Unknown transaction: cannot attach to a ServiceRequest, skip RequestEvent creation
		return JsonResponse({'ok': False}, status=404)

	# Always call verify endpoint to get canonical status
	try:
		v = cinetpay_lib.verify_payment(transaction_id)
	except Exception as e:
		RequestEvent.objects.create(request=sr, type='cinetpay.verify.error', metadata={'error': str(e), 'body': body})
		return JsonResponse({'ok': False}, status=502)

	# Expected structure: {code: '00', message: 'SUCCES', data: {status: 'ACCEPTED', ...}}
	status = None
	if isinstance(v, dict):
		status = (v.get('data') or {}).get('status') or v.get('status')

	paid_values = {'ACCEPTED', 'PAID', 'SUCCESS', 'SUCCES', 'COMPLETED', 'OK'}
	if status and str(status).upper() in paid_values:
		if sr.status != ServiceRequest.Status.PAID:
			sr.status = ServiceRequest.Status.PAID
			sr.payment_token = transaction_id
			sr.email_sent_at = timezone.now()
			sr.save(update_fields=['status', 'payment_token', 'email_sent_at', 'updated_at'])
			RequestEvent.objects.create(request=sr, type='cinetpay.confirmed', metadata=v)
		else:
			RequestEvent.objects.create(request=sr, type='cinetpay.confirmed.duplicate', metadata=v)
		return JsonResponse({'ok': True})

	# Not accepted
	RequestEvent.objects.create(request=sr, type='cinetpay.failed', metadata={'verify': v})
	return JsonResponse({'ok': False}, status=400)


def cinetpay_return(request):
	"""User-facing return URL after payment. Minimal implementation: show final status.
	The PSP may include params; prefer server-side verification via webhook.
	"""
	transaction_id = request.GET.get('transaction_id') or request.GET.get('payment_id') or request.GET.get('tx')
	if not transaction_id:
		return JsonResponse({'ok': False, 'error': 'transaction_id_missing'}, status=400)
	sr = ServiceRequest.objects.filter(payment_token__icontains=str(transaction_id)).first()
	if not sr:
		return JsonResponse({'ok': False, 'error': 'request_not_found'}, status=404)
	return JsonResponse({'ok': True, 'request_id': sr.id, 'status': sr.status})
