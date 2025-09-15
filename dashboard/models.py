from django.db import models
from django.conf import settings
from django.utils import timezone


class Service(models.Model):
	key = models.SlugField(max_length=50, unique=True)
	name = models.CharField(max_length=120)
	description = models.TextField(blank=True)
	active = models.BooleanField(default=True)

	class Meta:
		ordering = ['name']

	def __str__(self):
		return self.name


class ServiceRequest(models.Model):
	class Status(models.TextChoices):
		DRAFT = 'draft', 'Brouillon'
		PENDING_PAYMENT = 'pending_payment', 'En attente de paiement'
		PAID = 'paid', 'Payé'
		CANCELED = 'canceled', 'Annulé'
		NEW = 'new', 'Nouveau'
		IN_PROGRESS = 'in_progress', 'En cours'
		DONE = 'done', 'Terminé'
		REJECTED = 'rejected', 'Rejeté'

	user = models.ForeignKey(settings.AUTH_USER_MODEL, on_delete=models.CASCADE, related_name='service_requests')
	kind = models.CharField(max_length=50)
	data = models.JSONField(default=dict, blank=True)
	service = models.ForeignKey(Service, on_delete=models.SET_NULL, null=True, blank=True, related_name='requests')
	payment_method = models.CharField(max_length=30, blank=True, default='')
	payment_token = models.CharField(max_length=255, blank=True, null=True, db_index=True)
	# Price proposed by admin (optional)
	price = models.DecimalField(max_digits=10, decimal_places=2, null=True, blank=True)
	# Freeform note/message entered by admin when proposing an offer
	admin_note = models.TextField(blank=True, default='')
	token_expires_at = models.DateTimeField(blank=True, null=True)
	submitted_at = models.DateTimeField(blank=True, null=True)
	email_sent_at = models.DateTimeField(blank=True, null=True)
	status = models.CharField(max_length=20, choices=Status.choices, default=Status.DRAFT)
	created_at = models.DateTimeField(auto_now_add=True)
	updated_at = models.DateTimeField(auto_now=True)

	class Meta:
		ordering = ['-created_at']

	def __str__(self):
		return f"{self.get_kind_display() if hasattr(self, 'get_kind_display') else self.kind} #{self.pk}"


class RequestEvent(models.Model):
	request = models.ForeignKey(ServiceRequest, on_delete=models.CASCADE, related_name='events')
	type = models.CharField(max_length=80)
	metadata = models.JSONField(default=dict, blank=True)
	created_at = models.DateTimeField(auto_now_add=True)

	class Meta:
		ordering = ['-created_at']

	def __str__(self):
		return f"{self.type} for req #{self.request_id}"


class HotelReservation(models.Model):
	request = models.OneToOneField(ServiceRequest, on_delete=models.CASCADE, related_name='hotel')
	ville = models.CharField(max_length=100)
	date_arrivee = models.DateField()
	date_depart = models.DateField()
	hebergement = models.CharField(max_length=50)
	chambre = models.CharField(max_length=50)
	budget = models.CharField(max_length=50)
	besoins = models.JSONField(default=list, blank=True)
	infos = models.TextField(blank=True)

	def __str__(self):
		return f"Hotel {self.ville} ({self.date_arrivee}→{self.date_depart})"


class VehicleRental(models.Model):
	request = models.OneToOneField(ServiceRequest, on_delete=models.CASCADE, related_name='location')
	ville = models.CharField(max_length=100)
	date_debut = models.DateField()
	heure_debut = models.TimeField()
	duree = models.CharField(max_length=50)
	duree_autre = models.CharField(max_length=100, blank=True)
	vehicule = models.CharField(max_length=50)
	finalite = models.CharField(max_length=100)
	services = models.JSONField(default=list, blank=True)
	lieu_depose = models.CharField(max_length=150, blank=True)
	infos = models.TextField(blank=True)

	def __str__(self):
		return f"Location {self.ville} {self.date_debut} {self.vehicule}"


class ConciergerieRequest(models.Model):
	request = models.OneToOneField(ServiceRequest, on_delete=models.CASCADE, related_name='conciergerie')
	nature = models.CharField(max_length=100)
	urgence = models.CharField(max_length=30)
	contact = models.CharField(max_length=30)
	agent = models.CharField(max_length=3, blank=True, default='')  # 'oui' | 'non'
	details = models.TextField(blank=True)

	def __str__(self):
		return f"Conciergerie {self.nature} ({self.urgence})"


class AirportAssistance(models.Model):
	request = models.OneToOneField(ServiceRequest, on_delete=models.CASCADE, related_name='aeroport')
	service = models.CharField(max_length=20)  # arrivee|depart|transit
	aeroport = models.CharField(max_length=120)
	date_vol = models.DateField()
	heure_vol = models.TimeField()
	num_vol = models.CharField(max_length=60, blank=True)
	langue = models.CharField(max_length=10)
	langue_autre = models.CharField(max_length=80, blank=True)
	services = models.JSONField(default=list, blank=True)
	infos = models.TextField(blank=True)

	def __str__(self):
		return f"Aéroport {self.aeroport} {self.service} {self.date_vol}"


class TDGRequest(models.Model):
	request = models.OneToOneField(ServiceRequest, on_delete=models.CASCADE, related_name='tdg')
	services = models.JSONField(default=list, blank=True)
	traduction_docs = models.JSONField(default=list, blank=True)
	traduction_docs_autre = models.CharField(max_length=150, blank=True)
	langue_source = models.CharField(max_length=10, blank=True)
	langue_cible = models.CharField(max_length=10, blank=True)
	legalisation = models.CharField(max_length=3, blank=True)  # 'oui' | 'non'
	assistance_options = models.JSONField(default=list, blank=True)
	assistance_details = models.TextField(blank=True)
	guide_accompagnement = models.CharField(max_length=20, blank=True)  # journee | demi_journee
	guide_langue = models.CharField(max_length=10, blank=True)
	guide_lieux = models.TextField(blank=True)
	guide_options = models.JSONField(default=list, blank=True)
	nom = models.CharField(max_length=120)
	email = models.EmailField()
	phone = models.CharField(max_length=50)

	def __str__(self):
		return f"TDG {','.join(self.services) if self.services else '—'}"


class VisaCarteRequest(models.Model):
	request = models.OneToOneField(ServiceRequest, on_delete=models.CASCADE, related_name='visa')
	# Legacy simple fields (kept for compatibility with previous UI)
	type = models.CharField(max_length=20, blank=True)  # visa|carte
	pays = models.CharField(max_length=80, blank=True)
	duree = models.CharField(max_length=20, blank=True)
	nom = models.CharField(max_length=120, blank=True)
	email = models.EmailField(blank=True)
	phone = models.CharField(max_length=50, blank=True)
	# New flexible structure
	but_sejour = models.CharField(max_length=20, blank=True)  # volant|etablissement|carte
	etab_type = models.CharField(max_length=20, blank=True)   # ordinaire|travail|permanent
	details = models.JSONField(default=dict, blank=True)      # all textual inputs from the chosen path
	documents = models.JSONField(default=dict, blank=True)    # saved file paths keyed by input name

	def __str__(self):
		return f"{self.type.title()} {self.pays} ({self.nom})"


class OtherServicesRequest(models.Model):
	request = models.OneToOneField(ServiceRequest, on_delete=models.CASCADE, related_name='autres')
	comment = models.TextField()

	def __str__(self):
		return f"Autres services #{self.request_id}"

# Create your models here.
