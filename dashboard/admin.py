from django.contrib import admin
from .models import (
	Service,
	ServiceRequest,
	RequestEvent,
	HotelReservation,
	VehicleRental,
	ConciergerieRequest,
	AirportAssistance,
	TDGRequest,
	VisaCarteRequest,
	OtherServicesRequest,
)
@admin.register(Service)
class ServiceAdmin(admin.ModelAdmin):
	list_display = ('key', 'name', 'active')
	list_filter = ('active',)
	search_fields = ('key', 'name')



@admin.register(ServiceRequest)
class ServiceRequestAdmin(admin.ModelAdmin):
	list_display = ('id', 'user', 'kind', 'service', 'payment_method', 'price', 'status', 'created_at')
	list_filter = ('kind', 'service', 'status', 'created_at')
	search_fields = ('id', 'user__username')
	list_editable = ('price',)
	actions = ('proposer_offre',)

	@admin.action(description="Proposer une offre (marquer 'En attente de paiement')")
	def proposer_offre(self, request, queryset):
		from django.utils import timezone
		from .models import RequestEvent, ServiceRequest
		updated = 0
		for sr in queryset.select_for_update():
			# Require a price to be set
			if sr.price is None:
				continue
			# Update status to pending payment
			if sr.status != ServiceRequest.Status.PENDING_PAYMENT:
				sr.status = ServiceRequest.Status.PENDING_PAYMENT
				sr.token_expires_at = None  # optional: could set an expiry date here
				sr.save(update_fields=['status', 'token_expires_at', 'updated_at'])
				RequestEvent.objects.create(
					request=sr,
					type='admin.propose_offer',
					metadata={'price': str(sr.price), 'by': getattr(request.user, 'username', 'admin')}
				)
				updated += 1
		self.message_user(request, f"Offre proposée pour {updated} demande(s).")
@admin.register(RequestEvent)
class RequestEventAdmin(admin.ModelAdmin):
	list_display = ('id', 'request', 'type', 'created_at')
	list_filter = ('type', 'created_at')
	search_fields = ('request__id', 'type')


@admin.register(HotelReservation)
class HotelReservationAdmin(admin.ModelAdmin):
	list_display = ('request', 'ville', 'date_arrivee', 'date_depart', 'hebergement', 'chambre')
	search_fields = ('request__id', 'ville')


@admin.register(VehicleRental)
class VehicleRentalAdmin(admin.ModelAdmin):
	list_display = ('request', 'ville', 'date_debut', 'vehicule', 'finalite')
	search_fields = ('request__id', 'ville')


@admin.register(ConciergerieRequest)
class ConciergerieRequestAdmin(admin.ModelAdmin):
	list_display = ('request', 'nature', 'urgence', 'contact', 'agent')
	search_fields = ('request__id', 'nature')


@admin.register(AirportAssistance)
class AirportAssistanceAdmin(admin.ModelAdmin):
	list_display = ('request', 'aeroport', 'service', 'date_vol')
	search_fields = ('request__id', 'aeroport')


@admin.register(TDGRequest)
class TDGRequestAdmin(admin.ModelAdmin):
	list_display = ('request', 'nom', 'email', 'phone')
	search_fields = ('request__id', 'nom', 'email')


@admin.register(VisaCarteRequest)
class VisaCarteRequestAdmin(admin.ModelAdmin):
	list_display = ('request', 'type', 'pays', 'nom')
	search_fields = ('request__id', 'pays', 'nom')


@admin.register(OtherServicesRequest)
class OtherServicesRequestAdmin(admin.ModelAdmin):
	list_display = ('request',)
	search_fields = ('request__id',)

# Register your models here.
