from django.urls import path
from .views import space

app_name = 'dashboard'

urlpatterns = [
    path('space/', space, name='space'),
]
