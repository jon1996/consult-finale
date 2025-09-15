from django.conf import settings
from django.contrib import messages
from django.contrib.auth import logout as auth_logout
from django.shortcuts import redirect
from django.utils import timezone
from django.utils.translation import gettext as _
from urllib.parse import quote


class InactiveUserLogoutMiddleware:
    """
    Logs out authenticated users after SESSION_IDLE_TIMEOUT seconds of inactivity.
    Stores last activity timestamp in the session and enforces a sliding window.
    """

    def __init__(self, get_response):
        self.get_response = get_response

    def __call__(self, request):
        timeout = getattr(settings, "SESSION_IDLE_TIMEOUT", None)
        if timeout and request.user.is_authenticated:
            now_ts = timezone.now().timestamp()
            last_ts = request.session.get("last_activity_ts")

            if last_ts is not None:
                idle_secs = now_ts - float(last_ts)
                if idle_secs > float(timeout):
                    # Expire session and redirect to login with message
                    auth_logout(request)
                    messages.info(
                        request,
                        _("Votre session a expiré après 10 minutes d'inactivité. Veuillez vous reconnecter."),
                    )
                    login_url = settings.LOGIN_URL or "/login/"
                    # Preserve next path
                    next_url = quote(request.get_full_path(), safe="")
                    return redirect(f"{login_url}?next={next_url}")

            # Update last activity timestamp
            request.session["last_activity_ts"] = now_ts

        response = self.get_response(request)
        return response
