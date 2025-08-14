from django.db import models

# Create your models here.
from django.contrib.auth.models import AbstractUser


class User(AbstractUser):
	phone = models.CharField(max_length=20, blank=True)
	email_verified = models.BooleanField(default=False)
	company = models.CharField(max_length=100, blank=True)
	sector = models.CharField(max_length=30, blank=True)

	def __str__(self):
		return self.username
