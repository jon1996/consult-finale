from django.shortcuts import render
from django.contrib.auth.decorators import login_required


@login_required
def space(request):
	# A minimal context; extend later with real data
	return render(request, 'dashboard/space.html', {})
