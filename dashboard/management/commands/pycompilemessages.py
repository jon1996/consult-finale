from __future__ import annotations
import os
import sys
from pathlib import Path
from django.core.management.base import BaseCommand, CommandError

try:
    import polib  # type: ignore
except Exception as exc:  # pragma: no cover
    polib = None


class Command(BaseCommand):
    help = (
        "Compile .po files to .mo using polib (pure Python). Useful on Windows when GNU gettext is missing.\n"
        "Usage: python manage.py pycompilemessages [-l en -l fr -l zh_Hans]"
    )

    def add_arguments(self, parser):
        parser.add_argument(
            "-l",
            "--locale",
            dest="locales",
            action="append",
            help="Locale(s) to process (repeat for multiple). If omitted, process all locales under LOCALE_PATHS.",
        )

    def handle(self, *args, **options):
        if polib is None:
            raise CommandError("polib is not installed. Please install it (pip install polib).")

        from django.conf import settings

        locale_paths = getattr(settings, "LOCALE_PATHS", [])
        if not locale_paths:
            self.stdout.write(self.style.WARNING("No LOCALE_PATHS configured; nothing to do."))
            return

        requested_locales = options.get("locales")
        total_compiled = 0
        for base in locale_paths:
            base_path = Path(base)
            if not base_path.exists():
                continue

            # Iterate locales
            for loc_dir in base_path.iterdir():
                if not loc_dir.is_dir():
                    continue
                locale_code = loc_dir.name
                if requested_locales and locale_code not in requested_locales:
                    # Allow zh-hans vs zh_Hans mismatch by normalizing
                    norm_requested = {l.replace("-", "_") for l in requested_locales}
                    if locale_code.replace("-", "_") not in norm_requested:
                        continue

                lc_messages = loc_dir / "LC_MESSAGES"
                if not lc_messages.exists():
                    continue

                for po_path in lc_messages.glob("*.po"):
                    mo_path = po_path.with_suffix(".mo")
                    try:
                        po = polib.pofile(str(po_path))
                        po.save_as_mofile(str(mo_path))
                        total_compiled += 1
                        self.stdout.write(
                            self.style.SUCCESS(f"Compiled {po_path.relative_to(base_path)} -> {mo_path.name}")
                        )
                    except Exception as exc:
                        raise CommandError(f"Failed to compile {po_path}: {exc}") from exc

        if total_compiled == 0:
            self.stdout.write(self.style.WARNING("No .po files compiled. Ensure locales exist and contain .po files."))
        else:
            self.stdout.write(self.style.SUCCESS(f"Done. Compiled {total_compiled} file(s)."))
