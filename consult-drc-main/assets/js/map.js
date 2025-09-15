/*===========================================
              Location Map
===========================================*/

function initMap() {
  var map = new google.maps.Map(document.getElementById("location-map"), {
    zoom: 14,
    center: new google.maps.LatLng(-20.0052713, 57.6413037),
    disableDefaultUI: true,
    styles: [
      {
        elementType: "labels",
        stylers: [
          {
            visibility: "on"
          }
        ]
      },
      {
        elementType: "geometry",
        stylers: [
          {
            color: "#EFEFEF",
            visibility: "off"
          }
        ]
      },
      {
        featureType: "administrative.locality",
        elementType: "labels.text.fill",
        stylers: [
          {
            color: "#2e2e2e",
            visibility: "off"
          }
        ]
      },
      {
        featureType: "poi.park",
        elementType: "geometry",
        stylers: [
          {
            color: "#EFEFEF",
            visibility: "off"
          }
        ]
      },
      {
        featureType: "road",
        elementType: "geometry",
        stylers: [
          {
            color: "#DADADA",
            visibility: "off"
          }
        ]
      },
      {
        featureType: "road",
        elementType: "geometry.stroke",
        stylers: [
          {
            color: "#DADADA",
            visibility: "off"
          }
        ]
      },
      {
        featureType: "road.highway",
        elementType: "geometry",
        stylers: [
          {
            color: "#DADADA",
            visibility: "off"
          }
        ]
      },
      {
        featureType: "road.highway",
        elementType: "geometry.stroke",
        stylers: [
          {
            color: "#DADADA",
            visibility: "off"
          }
        ]
      },
      {
        featureType: "road.highway",
        elementType: "labels",
        stylers: [
          {
            color: "#000000",
            visibility: "off"
          }
        ]
      },
      {
        featureType: "water",
        elementType: "geometry",
        stylers: [
          {
            color: "#ffffff"
          }
        ]
      },
      {
        featureType: "water",
        elementType: "labels.text.fill",
        stylers: [
          {
            color: "#ededed",
            visibility: "on"
          }
        ]
      },
      {
        featureType: "road",
        elementType: "labels",
        stylers: [{ visibility: "off" }]
      },
      {
        featureType: "transit.station.bus",
        elementType: "labels",
        stylers: [{ visibility: "off" }]
      },
      {
        elementType: "labels",
        stylers: [{ visibility: "on" }]
      },
      {
        elementType: "labels.icon",
        stylers: [{ visibility: "off" }]
      }
    ]
  });

  marker = new google.maps.Marker({
    position: new google.maps.LatLng(-20.0052713, 57.6413037),
    map: map,
  });
}

// Open in Google Maps handler for side popup
document.addEventListener('click', function (e) {
  var target = e.target;
  // allow clicks on children of the anchor (e.g., icon/text)
  while (target && target !== document) {
    if (target.matches && target.matches('.open-maps')) {
      e.preventDefault();
      var lat = target.getAttribute('data-lat');
      var lng = target.getAttribute('data-lng');

      var url = '';
      // Prefer explicit data-address if present, otherwise use coordinates, otherwise fallback to .address text
      var dataAddress = target.getAttribute('data-address');
      if (dataAddress) {
        url = 'https://www.google.com/maps/search/?api=1&query=' + encodeURIComponent(dataAddress);
      } else if (lat && lng) {
        // Use lat,lng query for precise location
        url = 'https://www.google.com/maps/search/?api=1&query=' + encodeURIComponent(lat + ',' + lng);
      } else {
        // Fallback: try to read nearby address text in the popup
        var popup = target.closest('.location-popup');
        var addressEl = popup ? popup.querySelector('.address') : null;
        var address = addressEl ? addressEl.textContent.trim() : '';
        if (address) {
          url = 'https://www.google.com/maps/search/?api=1&query=' + encodeURIComponent(address);
        }
      }

      if (url) {
        // Open in new tab (anchor has target=_blank too) but do it programmatically for older browsers
        window.open(url, '_blank', 'noopener');
      } else {
        console.warn('Open in Maps: no coordinates or address available');
      }

      return;
    }
    target = target.parentNode;
  }
});
