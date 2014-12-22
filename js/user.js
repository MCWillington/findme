
function User() {

	var self = this;
	this.loc = new Location(function(){ user.init(); });
	this.groupLoc = new Object;
	this.groupMarker = null;
	this.locationMarker = null;

	this.init = function() {
	
		if (self.loc !== false) {
			
			console.log(self.loc.latitude + " " + self.loc.longitude);
			 
			map.setCenter(self.loc.latitude, self.loc.longitude);
			 
			self.locationMarker = new Marker({
				latitude: self.loc.latitude,
				longitude: self.loc.longitude,
				icon: unpinnedMarker,
				callback: user.showMarkerForm
			});

		} else {
			alert("Unfortunately your browser is outdated and some features of this website may not work properly.");
		}
	
	}
	
	this.showMarkerForm = function() {

		$('#popup').show();
		$('#form-1').show();
		$('#form-1 input[type=submit]').click(self.pinUserMarker);
	
	}	
	
	this.pinUserMarker = function() {
	
		console.log("pinUserMarker");
		var data = new Object;
		data.name = $('#form-1 input[name=name]').val();
		data.ageRange = $('#form-1 select[name=age-range]').val();
		data.loc = $('#form-1 input[name=loc]').val();
		data.groupNumber = $('#form-1 input[name=group-number]').val();
		
		//userPin.setIcon("./img/pinned-marker.png");
		
		self.groupMarker = new Marker({
			latitude: self.loc.latitude,
			longitude: self.loc.longitude,
			icon: pinnedMarker
		});
		
		self.groupLoc.latitude = self.loc.latitude;
		self.groupLoc.longitude = self.loc.longitude;
		
		google.maps.event.addListener(self.groupMarker, 'click', function() {

			new google.maps.InfoWindow({
				pixelOffset: new google.maps.Size(0, 17),	
				content:  
					'<div style="width:300px;max-width:100%;" class="infowindow">' +
					'<p>'+data.name+'</p>' +
					'<p>'+data.ageRange+'</p>' +
					'<p>'+data.loc+'</p>' +
					'<p>'+data.groupNumber+'</p>' +
					'<p>-</p>' +
					'<p>-</p>' +
					'</div>'
			}).open(map.getInstance(), self.groupMarker);
			
			map.panTo(self.groupLoc.latitude, self.groupLoc.longitude);
			
		});
		
		map.panTo(self.loc.latitude, self.loc.longitude);
			
		self.updateUserData(data);
			
		$('#popup').hide();
		$('#form-1').hide();
		
		setTimeout(function(){
		
			$('#popup').show();
			$('#form-2').show();
			$('#form-2 input[type=submit]').click(function(){
				$('#popup').hide();
				$('#form-2').hide();
				
				$('#popup').show();
				$('#form-3').show();
				$('#form-3 input[type=submit]').click(function(){
					$('#popup').hide();
					$('#form-3').hide();
				});
				
			});
			
		},1000);

	}
	
	this.updateUserData = function(data) {
		
		$.ajax({
			url: 'create_user.php',
			method: 'POST',
			data: {
				name: data.name,
				age: data.ageRange,
				loc: data.loc,
				group_number: data.groupNumber,
				latitude: self.loc.latitude,
				longitude: self.loc.longitude
			},
			success: function(data) {
				console.log("DATA UPDATE: Success.");
			},
			error: function(data) {
				console.log("DATA UPDATE: Error.");
			}
		});
	}

	this.setCurrentPosition = function() {
	
		console.log(self.loc.latitude);
		self.locationMarker.marker.setPosition(new google.maps.LatLng(self.loc.latitude,self.loc.longitude));
	
		navigator.geolocation.watchPosition(function(position) {
			self.locationMarker.marker.setPosition(new google.maps.LatLng(position.coords.latitude,position.coords.longitude));
		});
	
	}
	
	this.deleteGroupMarker = function() {
		self.groupMarker.setMap(null);
		self.groupMarker = null;
	}
	
}