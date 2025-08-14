# Consult

Django project with custom user auth (registration with email confirmation, login, password reset) and a simple dashboard.

## Local development
- Python 3.9+
- Create venv and install deps:
  - `python -m venv .venv`
  - `./.venv/Scripts/activate` (Windows) or `source .venv/bin/activate` (Unix)
  - `pip install -r requirements.txt`
- Set env vars as needed; defaults use console email backend in DEBUG.
- Run:
  - `python manage.py migrate`
  - `python manage.py runserver`

## Environment variables
- `DJANGO_DEBUG` (true/false)
- `DJANGO_ALLOWED_HOSTS` (comma separated)
- `DJANGO_CSRF_TRUSTED_ORIGINS` (comma separated, URLs)
- `DJANGO_SECRET_KEY` (production secret)
- `EMAIL_HOST`, `EMAIL_PORT`, `EMAIL_HOST_USER`, `EMAIL_HOST_PASSWORD`, `EMAIL_USE_TLS`, `EMAIL_USE_SSL`, `DEFAULT_FROM_EMAIL`

## Deployment
See instructions in the conversation for Gunicorn + Nginx + systemd on AWS.
