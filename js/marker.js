
function Marker(data) { // data for marker (object)

// {
	// latitude:
	// longitude:
	// icon:
	// name:
	// age_range:
	// loc:
	// group_number:
	// tell:
	// icebreaker:
	// callback:
// }

	var self = this;
	self.data = data;
	
	// Define Marker properties
	var me = new google.maps.MarkerImage('./img/me-pin.png',
		// This marker is 129 pixels wide by 42 pixels tall.
		new google.maps.Size(25, 25),
		// The origin for this image is 0,0.
		new google.maps.Point(0,0),
		// The anchor for this image is the base of the flagpole at 18,42.
		new google.maps.Point(12.5, 12.5)
	);

	var other = new google.maps.MarkerImage('./img/others-pin.png',
		// This marker is 129 pixels wide by 42 pixels tall.
		new google.maps.Size(25, 25),
		// The origin for this image is 0,0.
		new google.maps.Point(0,0),
		// The anchor for this image is the base of the flagpole at 18,42.
		new google.maps.Point(12.5, 12.5)
	);

	// Add Marker
	self.marker = new google.maps.Marker({
		position: new google.maps.LatLng(self.data.latitude,self.data.longitude),
		map: map.getInstance(),
		zIndex: 2,
		icon: eval(self.data.type),
		animation: google.maps.Animation.DROP
	});
	
	this.setMarkerPosition = function(lat,lng) {
		self.marker.setPosition( new google.maps.LatLng(lat,lng) );
	}
	
	this.deleteMarker = function() {
		self.marker.setMap(null);
	}
	
	var infoWindow = new google.maps.InfoWindow({
		pixelOffset: new google.maps.Size(0, 17),	
		content: 
			'<div style="width:300px;max-width:100%;" class="infowindow">' +
			'<p>' + (self.data.name ? self.data.name : "-") + '</p>' +
			'<p>' + (self.data.age_range ? self.data.age_range : "-") + '</p>' +
			'<p>' + (self.data.loc ? self.data.loc : "-") + '</p>' +
			'<p>' + (self.data.group_number ? self.data.group_number : "-") + '</p>' +
			'<p>' + (self.data.tell ? self.data.tell : "-") + '</p>' +
			'<p>' + (self.data.icebreaker ? self.data.icebreaker : "-") + '</p>' +
			'</div>'
	});
	
	// google.maps.event.addListener(self.marker, 'click', function(){ 
		////infoWindow.open(map.getInstance(),self.marker); 
		// var url = "https://maps.google.com?saddr=Current+Location&daddr=" + self.data.latitude + "," + self.data.longitude;
		// var win = window.open(url, '_blank');
		// win.focus();
	// });
	
}