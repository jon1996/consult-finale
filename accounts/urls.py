from django.urls import path
from . import views

app_name = "accounts"

urlpatterns = [
    path("register/", views.register, name="register"),
    path("login/", views.login_view, name="login"),
    path("logout/", views.logout_view, name="logout"),
    path("email-verify/<str:token>/", views.email_verify, name="email_verify"),
    path("password-reset/", views.password_reset_request, name="password_reset"),
    path("password-reset/<uidb64>/<token>/", views.password_reset_confirm, name="password_reset_confirm"),
]
