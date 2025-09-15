from django import forms
from django.contrib.auth.forms import AuthenticationForm
class LoginForm(AuthenticationForm):
    username = forms.CharField(label="Nom d'utilisateur ou Email", max_length=150, widget=forms.TextInput(attrs={"class": "form-control", "placeholder": "Nom d'utilisateur ou Email"}))
    password = forms.CharField(label="Mot de passe", widget=forms.PasswordInput(attrs={"class": "form-control", "placeholder": "Mot de passe"}))
from django.contrib.auth.forms import PasswordResetForm, SetPasswordForm

class CustomPasswordResetForm(PasswordResetForm):
    email = forms.EmailField(label="Adresse email", max_length=254, widget=forms.EmailInput(attrs={"class": "form-control", "placeholder": "Votre email"}))


class CustomSetPasswordForm(SetPasswordForm):
    """Subclass SetPasswordForm to add form-control classes and placeholders to widgets

    This ensures templates that render {{ field }} get Bootstrap styling without
    using method calls in templates.
    """
    def __init__(self, *args, **kwargs):
        super().__init__(*args, **kwargs)
        if 'new_password1' in self.fields:
            self.fields['new_password1'].widget.attrs.update({
                'class': 'form-control',
                'placeholder': 'Nouveau mot de passe'
            })
        if 'new_password2' in self.fields:
            self.fields['new_password2'].widget.attrs.update({
                'class': 'form-control',
                'placeholder': 'Confirmer le mot de passe'
            })
from django import forms
from django.contrib.auth.forms import UserCreationForm
from .models import User
import re

class RegisterForm(UserCreationForm):
    username = forms.CharField(label="Nom d'utilisateur", max_length=150, required=True, widget=forms.TextInput(attrs={"class": "form-control", "placeholder": "Nom d'utilisateur"}))
    first_name = forms.CharField(label="Prénom", max_length=30, required=True, widget=forms.TextInput(attrs={"class": "form-control", "placeholder": "Votre prénom"}))
    last_name = forms.CharField(label="Nom", max_length=30, required=True, widget=forms.TextInput(attrs={"class": "form-control", "placeholder": "Votre nom"}))
    email = forms.EmailField(required=True, widget=forms.EmailInput(attrs={"class": "form-control", "placeholder": "votre.email@exemple.com"}))
    phone = forms.CharField(
        label="Numéro de téléphone",
        max_length=20,
        required=True,
        widget=forms.TextInput(
            attrs={
                "class": "form-control",
                "placeholder": "+243 970 000 000",
                # Client-side constraint: E.164-like (+ and 8-15 digits total)
                "pattern": r"^\+[1-9][0-9]{7,14}$",
                "inputmode": "tel",
                "title": "Format international requis: +[indicatif][numéro], ex: +243970000000",
            }
        ),
    )
    company = forms.CharField(label="Entreprise/Organisation", max_length=100, required=False, widget=forms.TextInput(attrs={"class": "form-control", "placeholder": "Nom de votre entreprise (optionnel)"}))
    sector = forms.ChoiceField(label="Secteur d'activité", choices=[
        ("", "Sélectionnez votre secteur"),
        ("banque", "Banque et Finance"),
        ("telecom", "Télécommunications"),
        ("energie", "Énergie"),
        ("mines", "Mines et Ressources"),
        ("commerce", "Commerce et Distribution"),
        ("transport", "Transport et Logistique"),
        ("agriculture", "Agriculture"),
        ("education", "Éducation"),
        ("sante", "Santé"),
        ("technologie", "Technologie"),
        ("autre", "Autre"),
    ], required=True, widget=forms.Select(attrs={"class": "form-control"}))
    password1 = forms.CharField(label="Mot de passe", widget=forms.PasswordInput(attrs={"class": "form-control", "placeholder": "Minimum 8 caractères"}), help_text="8 caractères minimum, 1 majuscule, 1 chiffre.")
    password2 = forms.CharField(label="Confirmer le mot de passe", widget=forms.PasswordInput(attrs={"class": "form-control", "placeholder": "Répétez votre mot de passe"}))
    terms = forms.BooleanField(label="J'accepte les conditions d'utilisation et la politique de confidentialité", required=True)
    newsletter = forms.BooleanField(label="Je souhaite recevoir la newsletter", required=False, initial=True)

    class Meta:
        model = User
        fields = ("username", "first_name", "last_name", "email", "phone", "company", "sector")

    def clean_email(self):
        email = self.cleaned_data.get("email")
        if User.objects.filter(email=email).exists():
            raise forms.ValidationError("Cet email est déjà utilisé.")
        return email

    def clean_password1(self):
        password = self.cleaned_data.get("password1")
        # Validation avancée du mot de passe
        if len(password) < 8:
            raise forms.ValidationError("Le mot de passe doit contenir au moins 8 caractères.")
        if not any(c.isupper() for c in password):
            raise forms.ValidationError("Le mot de passe doit contenir une majuscule.")
        if not any(c.isdigit() for c in password):
            raise forms.ValidationError("Le mot de passe doit contenir un chiffre.")
        return password

    def clean_phone(self):
        raw = (self.cleaned_data.get("phone") or "").strip()
        # Normalize: remove spaces, dashes, parentheses; convert leading 00->+
        normalized = re.sub(r"[\s\-()]", "", raw)
        if normalized.startswith("00"):
            normalized = "+" + normalized[2:]
        if not normalized.startswith("+"):
            raise forms.ValidationError("Format invalide. Utilisez le format international, ex: +243970000000")
        if not re.fullmatch(r"\+[1-9][0-9]{7,14}", normalized):
            raise forms.ValidationError("Numéro invalide. Saisissez 8 à 15 chiffres après l’indicatif (ex: +243970000000).")
        return normalized

    def clean(self):
        cleaned_data = super().clean()
        password1 = cleaned_data.get("password1")
        password2 = cleaned_data.get("password2")
        if password1 and password2 and password1 != password2:
            self.add_error("password2", "Les mots de passe ne correspondent pas.")
        return cleaned_data
