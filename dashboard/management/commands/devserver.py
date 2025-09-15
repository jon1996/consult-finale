from django.core.management.base import BaseCommand
from django.core.management import call_command
import os


class Command(BaseCommand):
    help = "Run Django development server with convenient defaults (env HOST/PORT supported)."

    def add_arguments(self, parser):
        parser.add_argument('--host', default=os.environ.get('HOST', '127.0.0.1'), help='Host/IP to bind')
        parser.add_argument('--port', default=os.environ.get('PORT', '8000'), help='Port to listen on')

    def handle(self, *args, **options):
        host = options['host']
        port = options['port']
        addr = f"{host}:{port}"
        self.stdout.write(self.style.SUCCESS(f"Starting dev server at http://{addr}/"))
        call_command('runserver', addr)
