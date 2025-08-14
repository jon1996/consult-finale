from django.contrib import admin
from django.contrib.auth.admin import UserAdmin as BaseUserAdmin
from django.utils.translation import gettext_lazy as _
from .models import User


@admin.register(User)
class UserAdmin(BaseUserAdmin):
	fieldsets = (
		(None, {"fields": ("username", "password")}),
		(_("Informations personnelles"), {"fields": ("first_name", "last_name", "email", "phone", "company", "sector")}),
		(_("Permissions"), {"fields": ("is_active", "email_verified", "is_staff", "is_superuser", "groups", "user_permissions")}),
		(_("Important dates"), {"fields": ("last_login", "date_joined")}),
	)
	add_fieldsets = (
		(None, {
			"classes": ("wide",),
			"fields": ("username", "email", "password1", "password2", "first_name", "last_name", "phone", "company", "sector", "is_active", "is_staff"),
		}),
	)
	list_display = ("username", "email", "first_name", "last_name", "is_staff", "email_verified", "company", "sector")
	list_filter = ("is_staff", "is_superuser", "is_active", "groups", "email_verified")
	search_fields = ("username", "first_name", "last_name", "email", "company", "sector", "phone")
	ordering = ("username",)
