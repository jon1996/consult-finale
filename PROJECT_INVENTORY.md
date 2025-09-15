## INVENTAIRE TECHNIQUE — Projet Consult

Résumé rapide
- Objectif : inventaire technique complet du projet Django (apps, config, modèles, endpoints, intégration CinetPay).
- Emplacement du fichier : `PROJECT_INVENTORY.md` (racine du projet).

Plan d'action (ce que j'ai fait)
- Lecture des fichiers clés : `consult/settings.py`, `requirements.txt`, `dashboard/models.py`, `dashboard/views.py`, `dashboard/urls.py`, `dashboard/cinetpay.py`, scripts `scripts/send_cinetpay_notify.*`, `accounts/models.py`, `dashboard/static/components/forms/forms.js`, `manage.py`, `README.md`.

1) Vue d’ensemble du projet
- Nom du projet : consult (Django project)
- Apps Django détectées (dossiers top-level) : `accounts`, `dashboard`, `core`, `services`.
- INSTALLED_APPS (extrait) :
  - `django.contrib.admin`, `django.contrib.auth`, `django.contrib.contenttypes`, `django.contrib.sessions`, `django.contrib.messages`, `django.contrib.staticfiles`, `accounts`, `dashboard`  (`consult/settings.py`).
- Versions détectables :
  - Django==5.2.1 (`requirements.txt`)
  - Python recommandé : 3.9+ (`README.md`).
- Dépendances (fichier) : `requirements.txt` (racine) — contient notamment Django==5.2.1, gunicorn, psycopg[binary], polib, requests.

2) Configuration & environnements
- Fichier principal : `consult/settings.py` (consult/settings.py)
- Variables d'environnement référencées (clé : où lue) :
  - `DJANGO_SECRET_KEY` (`consult/settings.py`: SECRET_KEY)
  - `DJANGO_DEBUG` (`consult/settings.py`: DEBUG)
  - `DJANGO_ALLOWED_HOSTS` (`consult/settings.py`: ALLOWED_HOSTS)
  - `DJANGO_CSRF_TRUSTED_ORIGINS` (`consult/settings.py`: CSRF_TRUSTED_ORIGINS)
  - Email: `EMAIL_BACKEND`, `EMAIL_HOST`, `EMAIL_PORT`, `EMAIL_HOST_USER`, `EMAIL_HOST_PASSWORD`, `EMAIL_USE_TLS`, `EMAIL_USE_SSL`, `EMAIL_TIMEOUT`, `DEFAULT_FROM_EMAIL`, `SERVER_EMAIL`
  - `DJANGO_STATIC_ROOT` (STATIC_ROOT)
  - Session: `DJANGO_SESSION_IDLE_TIMEOUT`, `DJANGO_SESSION_COOKIE_AGE`
  - CinetPay: `CINETPAY_API_BASE`, `CINETPAY_SITE_ID`, `CINETPAY_API_KEY`, `CINETPAY_VERIFY_ENDPOINT`, `CINETPAY_SECRET_KEY`

- Paramètres importants (extraits) :
  - DEBUG lu depuis `DJANGO_DEBUG`.
  - DATABASES: PostgreSQL par défaut (NAME=consult, USER=consult, PASSWORD=changeme, HOST=localhost, PORT=5432).
  - EMAIL: console backend en DEBUG, SMTP configurable.
  - CACHES: Non trouvé.
  - ALLOWED_HOSTS: lu depuis env.
  - TIME_ZONE: UTC.
  - STATIC/MEDIA: STATIC_URL='/static/', MEDIA_URL='/media/', STATIC_ROOT=`BASE_DIR/staticfiles` (ou DJANGO_STATIC_ROOT), MEDIA_ROOT=`BASE_DIR/media`.
  - LOGGING: Non trouvé (aucune configuration explicite).

3) Modèles (Models)
- Fichier principal : `dashboard/models.py`

Tableau des modèles (App | Modèle | Champs principaux | Relations | Meta/options)

| App | Modèle | Champs (nom:type [null/default]) | Relations | Meta / options |
|---|---|---|---|---|
| dashboard | Service | key:SlugField, name:CharField, description:TextField(blank=True), active:BooleanField(default=True) | - | ordering=['name'] |
| dashboard | ServiceRequest | user:FK, kind:CharField, data:JSONField(default=dict), service:FK(Service) null, payment_method:CharField(blank), payment_token:CharField(null, db_index), price:DecimalField(null), admin_note:TextField(blank), token_expires_at:DateTimeField(null), submitted_at:DateTimeField(null), status:CharField(default='draft'), created_at/updated_at | user -> accounts.User | ordering=['-created_at'] |
| dashboard | RequestEvent | request:FK(ServiceRequest), type:CharField, metadata:JSONField(default=dict), created_at | request -> ServiceRequest | ordering=['-created_at'] |
| dashboard | HotelReservation | request:OneToOne(ServiceRequest), ville:CharField, date_arrivee:DateField, date_depart:DateField, hebergement, chambre, budget, besoins:JSONField, infos:TextField | request -> ServiceRequest | - |
| dashboard | VehicleRental | request:OneToOne(ServiceRequest), ville, date_debut, heure_debut, duree, vehicule, finalite, services:JSONField, lieu_depose, infos | request -> ServiceRequest | - |
| dashboard | ConciergerieRequest | request:OneToOne, nature, urgence, contact, agent, details | request -> ServiceRequest | - |
| dashboard | AirportAssistance | request:OneToOne, service, aeroport, date_vol, heure_vol, num_vol, langue, services:JSONField, infos | request -> ServiceRequest | - |
| dashboard | TDGRequest | request:OneToOne, services:JSONField, traduction_docs:JSONField, nom,email,phone, ... | request -> ServiceRequest | - |
| dashboard | VisaCarteRequest | request:OneToOne, type,pays,duree,nom,email,phone,but_sejour,etab_type, details:JSONField, documents:JSONField | request -> ServiceRequest | - |
| dashboard | OtherServicesRequest | request:OneToOne, comment:TextField | request -> ServiceRequest | - |
| accounts | User | AbstractUser + phone,email_verified,company,sector | - | - |

Notes complémentaires sur modèles
- Signals personnalisés : Non trouvé.
- Managers custom : Non trouvé.
- Validators custom : Non trouvé.

4) URLs & Endpoints
- Fichier : `dashboard/urls.py`

Tableau des endpoints (Méthode(s) | URL | Vue/Handler | Permissions / Décorateurs | Notes)

| Méthode(s) | URL | Vue/Handler | Permissions / Décorateurs | Notes |
|---:|---|---|---|---|
| GET | /app/space/ (dashboard:space) | `space` | @login_required | Page espace utilisateur
| GET | /app/backoffice/ | `backoffice` | @_staff_required | Backoffice
| GET | /app/forms/<key>/ | `form_partial` | @login_required | Partial forms
| POST | /app/forms/<key>/submit/ | `submit_request` | @login_required, @require_POST | Création ServiceRequest + typed model
| GET | /app/requests/me/ | `my_requests` | @login_required | Liste demandes utilisateur
| GET | /app/requests/<id>/ | `request_detail` | @login_required | Détail demande
| POST | /app/requests/<id>/update/ | `update_request` | @login_required, @require_POST | MAJ + uploads
| POST | /app/requests/<id>/payment-method/ | `update_request_payment_method` | @login_required, @require_POST |
| POST | /app/requests/<id>/resubmit/ | `resubmit_request` | @login_required, @require_POST | regen token, envoi mail
| POST | /app/api/wizard/service | `wizard_service` | @login_required, @require_POST |
| POST | /app/api/wizard/details | `wizard_details` | @login_required, @require_POST |
| POST | /app/api/wizard/payment-method | `wizard_payment_method` | @login_required, @require_POST |
| POST | /app/api/wizard/submit | `wizard_submit` | @login_required, @require_POST |
| GET | /app/pay | `pay` | public | Landing payment (JSON)
| POST | /app/cinetpay/start/<id>/ | `cinetpay_start_payment` | @login_required, @require_POST | Initie paiement CinetPay
| POST | /app/cinetpay/notify/ | `cinetpay_notify` | @csrf_exempt, @require_POST | Webhook CinetPay, valide HMAC
| GET | /app/cinetpay/return/ | `cinetpay_return` | public | Return user

Remarque endpoints paiement : `/cinetpay/start/`, `/cinetpay/notify/`, `/cinetpay/return/` présents. Webhook CSRF-exempt.

5) Vues / Services / Intégrations
- Fichier central : `dashboard/views.py`
- Vues clés : space, backoffice, form_partial, submit_request, wizard_* (service/details/payment_method/submit), admin_* (requests, propose_offer), pay, cinetpay_start_payment, cinetpay_notify, cinetpay_return.

Intégration CinetPay — résumé technique (fichiers : `dashboard/cinetpay.py`, `dashboard/views.py`)
- Endpoints appelés :
  - POST `{CINETPAY_API_BASE.rstrip('/')}/v2/payment` via `initiate_payment(payload)`
  - POST `CINETPAY_VERIFY_ENDPOINT` via `verify_payment(transaction_id)`
- Données envoyées à la création : `site_id`, `amount`, `currency`, `transaction_id`, `description`, `return_url`, `notify_url`, `customer_name`, `customer_email`.
- HMAC / X-TOKEN :
  - `make_hmac_token(body, secret_key)` crée HMAC-SHA256 hexdigest sur concaténation de champs.
  - `validate_hmac_x_token(body, received_token, secret_key)` compare en constant time.
  - Webhook lit header `X-TOKEN` et valide via `CINETPAY_SECRET_KEY` si présent.
- notify_url & return_url : construites via `request.build_absolute_uri(reverse(...))`.
- Statuts : la vue `cinetpay_notify` appelle `verify_payment`, puis marque `ServiceRequest.status = PAID` si status dans `{'ACCEPTED','PAID','SUCCESS','SUCCES','COMPLETED','OK'}`.

6) Templates & Static
- Templates principaux : dossier `templates/` (templates/accounts, templates/dashboard, templates/forms).
- Fichiers templates référencés : `templates/forms/form-reservation-hotel.html`, `templates/forms/form-visa-carte.html`, `templates/dashboard/space.html`, etc.
- JS critique : `dashboard/static/components/forms/forms.js` — initialise formulaires TDG et Visa (API `FormsInit`).
- CSS critique : Non trouvé explicitement.

7) Emails & Notifications
- Backend email : console en DEBUG par défaut, sinon SMTP configurable via env.
- Templates d'email : `templates/accounts/email_sent.html`, `templates/accounts/email_confirmed.html`, etc.
- Envoi d'emails : `send_mail` utilisé dans `dashboard/views.py` (wizard_submit, resubmit_request, admin_propose_offer).
- Notifications internes : `RequestEvent` stocke événements et métadonnées.

8) Tâches/Commandes
- `manage.py` présent.
- Scripts utilitaires : `scripts/send_cinetpay_notify.py` et `scripts/send_cinetpay_notify.ps1` (simulateur webhook avec calcul X-TOKEN).
- Management commands custom : dossier existant mais pas d'commands lisibles.
- Celery / tâches asynchrones : Non trouvé.

9) Tests existants
- Fichiers `tests.py` présents dans `accounts/`, `dashboard/`, `core/`, `services/`.
- Couverture : Non mesurée ici.

10) Journalisation & Traçabilité
- Modèle `RequestEvent` (dashboard/models.py) ; utilisé largement pour tracer emails, erreurs, notifications.
- LOGGING global : Non trouvé.
- Données stockées : metadata JSON (headers, body, verify responses).

11) Sécurité
- Middleware CSRF actif; webhook CinetPay est `@csrf_exempt` mais valide X-TOKEN HMAC si clé configurée.
- Secrets : SECRET_KEY, CinetPay keys lu depuis env; éviter commit.
- CORS / HSTS : Non trouvé / Non configuré.

12) TODO / FIXME / dette technique
- TODO/FIXME : Aucun match trouvé via grep simple.
- Dette technique notable :
  - Corps d'emails inline (pas de templates dédiés).
  - Absence de LOGGING centralisé.
  - `pay` retourne JSON (manque UI paiement).

13) Risques & manques pour passage en local/sandbox (CinetPay)
- Nécessaire pour tests locaux :
  1. Env vars : CINETPAY_SITE_ID, CINETPAY_API_KEY, CINETPAY_SECRET_KEY, (CINETPAY_API_BASE si sandbox).
  2. Exposer notify_url (ngrok) ou utiliser `scripts/send_cinetpay_notify.py` pour simuler webhook.
  3. Migrer DB et créer superuser.
  4. Configurer EMAIL_BACKEND (console utile).
  5. Attention au verify endpoint (sandbox vs prod).

Checklist "Prêt à tester" (minimum) :
- [ ] pip install -r requirements.txt
- [ ] python manage.py migrate
- [ ] définir env vars: DJANGO_DEBUG, DJANGO_SECRET_KEY, CINETPAY_SITE_ID, CINETPAY_API_KEY, CINETPAY_SECRET_KEY
- [ ] python manage.py runserver
- [ ] (optionnel) exposer webhook via ngrok et tester
- [ ] simuler webhook via `python scripts/send_cinetpay_notify.py --url <notify_url> --site <site_id> --trans <trans_id> --amount <amount> --secret <secret>`

Extraits utiles minimaux
- Exemple payload pour initier un paiement (depuis `cinetpay_start_payment`):

```json
{
  "site_id": "<CINETPAY_SITE_ID>",
  "amount": 123.45,
  "currency": "XOF",
  "transaction_id": "sr-<request_id>-<ts>",
  "description": "Paiement demande #<id>",
  "return_url": "https://your.domain/app/cinetpay/return/",
  "notify_url": "https://your.domain/app/cinetpay/notify/",
  "customer_name": "Nom Client",
  "customer_email": "client@example.com"
}
```

- Calcul X-TOKEN : HMAC-SHA256 sur concaténation de champs en ordre défini, key=`CINETPAY_SECRET_KEY` (`dashboard/cinetpay.py`).

Plan d'actions priorisé (10 points) pour fiabiliser l'intégration CinetPay en local — du plus rapide au plus impactant
1. Définir et charger les env vars locales (CINETPAY_SITE_ID, CINETPAY_API_KEY, CINETPAY_SECRET_KEY) en `.env` ou via shell.
2. Utiliser `scripts/send_cinetpay_notify.py` pour valider localement la réception et la validation X-TOKEN.
3. Exposer temporairement la webhook via ngrok pour tests end-to-end avec sandbox CinetPay.
4. Ajouter des tests unitaires pour `dashboard/cinetpay.py` (initiate_payment, verify_payment, make_hmac_token, validate_hmac_x_token).
5. Ajouter une configuration LOGGING minimale dans `consult/settings.py`.
6. Rendre l'UI `/pay` un template HTML pour guider l'utilisateur vers le PSP.
7. Externaliser les mails dans des templates (templates/email/*.txt) et remplacer les corps inline par rendu de templates.
8. Mettre en place un playbook local pour génération de transactions (script create+start payment).
9. Ajouter métriques/monitoring pour webhooks invalides (compteur/alerte) et idempotence via transaction_id.
10. Documenter le mode sandbox CinetPay dans `README.md` (exemples de commandes et checklist).

---
Si vous voulez, je peux :
- générer des tests unitaires pour `dashboard/cinetpay.py` et un script d'intégration local qui utilise `scripts/send_cinetpay_notify.py`, ou
- créer un `.env.example` listant les variables d'environnement nécessaires et ajouter une configuration LOGGING minimale.

--- FIN DE L'INVENTAIRE (Partie 1/1)
