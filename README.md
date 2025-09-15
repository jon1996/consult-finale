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
## Quick start: venv + runserver

Use any of the following options:

1) Shell script

```bash
# from repo root
python3 -m venv .venv
. .venv/bin/activate
pip install -r requirements.txt
./scripts/runserver.sh            # defaults HOST=127.0.0.1 PORT=8000
# or override
HOST=0.0.0.0 PORT=8001 ./scripts/runserver.sh
```

2) Makefile

```bash
make venv
make install
make migrate
make run                # HOST/PORT optional: make run HOST=0.0.0.0 PORT=8001
```

3) Django management command

```bash
. .venv/bin/activate
python manage.py devserver                 # uses HOST/PORT env if set
HOST=0.0.0.0 PORT=8001 python manage.py devserver
python manage.py devserver --host 0.0.0.0 --port 8001
```

Notes
- Script and targets assume the virtualenv at `.venv/` in the project root.
- If `.venv` is missing, the script will print how to create it.
