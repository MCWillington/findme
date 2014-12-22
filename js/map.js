
function Map() {
	
	var map = null;

	this.getInstance = function(style) {
		
		if(map == null) {
		
			// Basic options for a simple Google Map
			// For more options see: https://developers.google.com/maps/documentation/javascript/reference#MapOptions
			var mapOptions = {
				minZoom: 6,
				// How zoomed in you want the map to start at (always required)
				zoom: 10,
				// disable map/satellite option
				disableDefaultUI: false,
				// The latitude and longitude to center the map (always required)
				center: new google.maps.LatLng(50.9,-0.126),
				// Set map style
				styles: style,
				
				scaleControl: true,
				streetViewControl: true,
				streetViewControlOptions: {
					position: google.maps.ControlPosition.TOP_RIGHT
				},
				zoomControl: true,
				zoomControlOptions: {
					style: google.maps.ZoomControlStyle.LARGE,
					position: google.maps.ControlPosition.TOP_RIGHT
				},				
				panControl: true,
				panControlOptions: {
					position: google.maps.ControlPosition.TOP_RIGHT
				}
			};

			// Get the HTML DOM element that will contain your map 
			// We are using a div with id="map" seen below in the <body>
			var mapElement = document.getElementById('map');

			// Create the Google Map using out element and options defined above
			map = new google.maps.Map(mapElement, mapOptions);
			
			return map;
		
		} else {
		
			return map;
		
		}
		
	}

	this.setCenter = function(lat,lng) {
		
		map.setCenter(new google.maps.LatLng(lat,lng));
		
	}
	
	this.panTo = function(lat,lng) {
	
		map.panTo(new google.maps.LatLng(lat,lng));
	
	}
	
	this.showAllMarkers = function(LatLongArray) {
	
		//  Create a new viewpoint bound
		var bounds = new google.maps.LatLngBounds ();
		//  Go through each...
		for (var i = 0, LtLgLen = LatLongArray.length; i < LtLgLen; i++) {
		  //  And increase the bounds to take this point
		  bounds.extend (LatLongArray[i]);
		}
		//  Fit these bounds to the map
		map.fitBounds (bounds);
	
	}

}