FROM python:3.12-slim

ENV PYTHONDONTWRITEBYTECODE=1 \
    PYTHONUNBUFFERED=1

WORKDIR /app

# System deps (add gcc/libpq-dev if using Postgres)
RUN apt-get update && apt-get install -y --no-install-recommends \
    build-essential curl && \
    rm -rf /var/lib/apt/lists/*

COPY requirements.txt /app/requirements.txt
RUN pip install --no-cache-dir -r /app/requirements.txt && \
    adduser --disabled-password --gecos '' appuser

# Copy whole repo (manage.py at root, project at /consult)
COPY . /app

# Collect static into /app/staticfiles (mounted to nginx as /srv/django/static)
RUN python manage.py collectstatic --noinput || true

USER appuser

EXPOSE 8000

