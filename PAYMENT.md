# Paiements – Intégration CinetPay

Ce document explique en détail la configuration, le flux, les endpoints et la sécurité de l’intégration CinetPay dans cette application.

## 1) Configuration

Variables d’environnement (fichier `.env` à la racine du projet):
- CINETPAY_API_BASE: hôte API (par défaut `https://api-checkout.cinetpay.com`)
- CINETPAY_SITE_ID: identifiant site fourni par CinetPay
- CINETPAY_API_KEY: clé API publique / checkout
- CINETPAY_SECRET_KEY: clé secrète utilisée pour la signature HMAC (webhook)
- CINETPAY_CURRENCY: devise des transactions (par défaut `USD`)

Réglages dans `consult/settings.py`:
- Lecture automatique du `.env`
- Valeurs de repli sécurisées
- DEFAULT_PRICES: tarifs par type de demande (uniquement pour tests)

## 2) Flux de paiement

Résumé:
1. L’utilisateur ouvre le détail d’une demande (UI `templates/dashboard/space.html`).
2. Si la demande est en `pending_payment` et un montant est défini, le bouton “Payer” apparaît.
3. Le clic appelle l’endpoint serveur pour créer la transaction CinetPay.
4. On reçoit `payment_url` et `payment_token`; le navigateur est redirigé vers CinetPay.
5. CinetPay rappelle notre webhook (`notify_url`) pour confirmer le statut.
6. L’utilisateur est renvoyé sur `return_url` pour une page de confirmation UX. La source de vérité reste le webhook.

Détails côté serveur:
- Création paiement: `dashboard/views.py::cinetpay_start_payment(id)`
  - Vérifie l’accès et le montant: `ServiceRequest.price` sinon `DEFAULT_PRICES[kind]` (test).
  - Compose le payload: `site_id`, `apikey`, `amount` (formaté `"xx.xx"`), `currency`, `transaction_id`, `description`, `return_url`, `notify_url`, informations client (nom, email, phone), `channels`, `lang`.
  - Appelle `dashboard/cinetpay.py::initiate_payment(payload)` => POST `{CINETPAY_API_BASE}/v2/payment`.
  - Enregistre des événements avec `RequestEvent` en cas d’erreur ou succès.
  - Réponse JSON: `{ ok, payment_url, payment_token }`.

- Webhook: `dashboard/views.py::cinetpay_notify`
  - CSRF exempt, `POST`.
  - Parse JSON/form, récupère `cpm_trans_id` et vérifie la signature `X-TOKEN` (si fournie) via `dashboard/cinetpay.py::validate_hmac_x_token`/`make_hmac_token` et `CINETPAY_SECRET_KEY`.
  - Vérifie le paiement via `verify_payment(transaction_id)` sur `/v2/payment/check`.
  - Si `status` ∈ {ACCEPTED, PAID, SUCCESS, SUCCES, COMPLETED, OK} => marque la demande `PAID`.
  - Loggue les événements (`cinetpay.confirmed`, `cinetpay.failed`, erreurs de vérif, etc.).

- Retour utilisateur: `dashboard/views.py::cinetpay_return`
  - Endpoint GET minimaliste qui renvoie `{ ok, request_id, status }` d’après `transaction_id`.
  - La décision finale reste le webhook.

## 3) Endpoints

- POST `/app/cinetpay/start/<id>/` (nom: `dashboard:cinetpay_start`)
  - Sécurisé (auth + ownership)
  - Body: none (CSRF requis)
  - Réponse: `{ ok: true, payment_url, payment_token }` ou `{ ok: false, error }`

- POST `/app/cinetpay/notify/` (nom: `dashboard:cinetpay_notify`)
  - CSRF exempt, appelé par CinetPay
  - En-tête: `X-TOKEN` (HMAC) si activé
  - Body: JSON ou form; doit contenir `cpm_trans_id` (ou `transaction_id`) et `cpm_site_id` (ou `site_id`)
  - Réponse: `{ ok: true }` en cas d’acceptation; `403` si HMAC invalide; `400/404/502` selon erreurs

- GET `/app/cinetpay/return/` (nom: `dashboard:cinetpay_return`)
  - Query: `transaction_id` (ou `payment_id`/`tx`)
  - Réponse: `{ ok, request_id, status }`

## 4) Sécurité

- HMAC du webhook: le header `X-TOKEN` est validé contre un HMAC SHA-256 calculé à partir d’un ensemble de champs (`cpm_*`, `signature`, `payment_method`, etc.) et `CINETPAY_SECRET_KEY`.
- Authentification côté start: seul le propriétaire (ou staff) peut initier un paiement sur sa demande.
- Journalisation: `RequestEvent` trace les états/erreurs, utile pour audit et support.

## 5) Devise et montants

- Devise par défaut: `USD` (modifiable via `CINETPAY_CURRENCY`).
- Le montant vient d’abord de `ServiceRequest.price`; sinon un fallback `DEFAULT_PRICES[kind]` peut s’appliquer pour les tests locaux.
- Le montant est envoyé en chaîne avec deux décimales.

## 6) Gestion des erreurs courantes

- `HTTP 400 code 608 MINIMUM_REQUIRED_FIELDS`:
  - Vérifier présence de `site_id`, `apikey`, `amount`, `currency` dans le payload.
  - S’assurer que `.env` est chargé (voir `consult/settings.py`).
  - Confirmer que la `currency` correspond aux devises activées sur le compte CinetPay.

- `HTTP 404` à la création:
  - Utiliser `https://api-checkout.cinetpay.com/v2/payment` et non l’ancien hôte.

- HMAC invalide sur webhook (`403 invalid_hmac`):
  - Vérifier `CINETPAY_SECRET_KEY` et que le header `X-TOKEN` est transmis par CinetPay.
  - Confirmer les champs du body attendus par le calcul HMAC.

## 7) UI

- Le bouton “Payer” s’affiche quand `status` = `pending_payment` et qu’un `price` est connu.
- L’UI affiche: `Montant: <price> <currency>`.
- Au clic, création de paiement puis redirection vers `payment_url`.

## 8) Tests

- Voir `dashboard/tests.py` pour des tests unitaires autour du start, du webhook (mock verify) et du HMAC.

## 9) Check-list déploiement

- Remplir `.env` en production: `CINETPAY_SITE_ID`, `CINETPAY_API_KEY`, `CINETPAY_SECRET_KEY`, `CINETPAY_CURRENCY`.
- Configurer `ALLOWED_HOSTS` et `CSRF_TRUSTED_ORIGINS`.
- Exposer publiquement `/app/cinetpay/notify/` (HTTPS recommandé) vers CinetPay.

## 10) Roadmap / améliorations possibles

- Backoffice “Proposer une offre” pour fixer les prix par demande.
  - Fait (action Admin): éditer `price` puis action “Proposer une offre” pour passer la demande en `pending_payment` et journaliser l’événement.
- Intégration du SDK JS CinetPay pour une expérience overlay.
  - À faire: utiliser `payment_token` pour initialiser le SDK et proposer un paiement in-page.
- Gestion avancée des statuts et des retries webhook.
  - À faire: mapper plus finement les statuts, stocker les tentatives, et rejouer la vérification si timeout.

## 11) Administration

- Dans l’admin Django (`/admin/` → Service requests):
  - Vous pouvez définir `price` directement depuis la liste (colonne éditable).
  - Sélectionnez une ou plusieurs demandes et utilisez l’action “Proposer une offre” pour les passer en `pending_payment`.
  - Un `RequestEvent` `admin.propose_offer` est créé avec le prix et l’auteur.
