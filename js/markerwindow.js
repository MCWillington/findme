

function MarkerWindow(marker, data) {

		var infoWindow = new google.maps.InfoWindow({
			pixelOffset: new google.maps.Size(0, 17),	
			content: 
				'<div style="width:300px;max-width:100%;" class="infowindow">' +
				'<p>'+data.name ? data.name : "-" +'</p>' +
				'<p>'+data.age_range ? data.age_range : "-" +'</p>' +
				'<p>'+data.loc ? data.loc : "-" +'</p>' +
				'<p>'+data.group_number ? data.group_number : "-" +'</p>' +
				'<p>'+data.tell ? data.tell : "-" +'</p>' +
				'<p>'+data.icebreaker ? data.icebreaker : "-" +'</p>' +
				'</div>'
		});
		
		google.maps.event.addListener(marker, 'click', function(){ 
			infoWindow.open(map.getInstance(),marker); 
		});
}