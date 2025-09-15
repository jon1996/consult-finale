#!/usr/bin/env bash
set -euo pipefail

# This script activates the local .venv and starts the Django development server.
# Usage:
#   HOST=127.0.0.1 PORT=8000 ./scripts/runserver.sh

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
ROOT_DIR="${SCRIPT_DIR%/scripts}"
VENV_DIR="${ROOT_DIR}/.venv"

if [[ ! -d "${VENV_DIR}" ]]; then
  echo "[!] Virtual environment not found at ${VENV_DIR}"
  echo "    Create it with: python3 -m venv .venv && . .venv/bin/activate && pip install -r requirements.txt"
  exit 1
fi

# shellcheck disable=SC1091
source "${VENV_DIR}/bin/activate"

cd "${ROOT_DIR}"

HOST=${HOST:-127.0.0.1}
PORT=${PORT:-8000}

echo "[i] Using Python: $(command -v python)"
echo "[i] Starting Django server on ${HOST}:${PORT}"

exec python manage.py runserver "${HOST}:${PORT}"
