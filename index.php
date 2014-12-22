<?php 
	
	// if(empty($sid)) {
		// session_start();
	// } else {
		// if (!isset($_SESSION['CREATED'])) {
			// $_SESSION['CREATED'] = time();
		// } else if (time() - $_SESSION['CREATED'] > 180) {
			////session started more than 3 minutes ago
			// session_regenerate_id(true);    // change session ID for the current session an invalidate old session ID
			// $_SESSION['CREATED'] = time();  // update creation time
		// }
	// }
	
	function randomString($length) {
		$character_array = array_merge(range(a, z), range(0, 9));
		$string = "";
		for($i = 0; $i < $length; $i++) {
			$string .= $character_array[rand(0, (count($character_array) - 1))];
		}
		return $string;
	}
	
	//session_start();
	//session_regenerate_id(true);
	//$sid = session_id();
	$sid = randomString(6);
?>

<!DOCTYPE html>
<html>
    <head>
        <title>:: Troop ::</title>
		
		<meta name="viewport" content="width=device-width, user-scalable=no">
        
		<link rel="stylesheet" href="css/style.css">
        
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
		
        <!-- 
            You need to include this script on any page that has a Google Map.
            When using Google Maps on your own site you MUST signup for your own API key at:
                https://developers.google.com/maps/documentation/javascript/tutorial#api_key
            After your sign up replace the key in the URL below or paste in the new script tag that Google provides.
        -->
        <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyASm3CwaK9qtcZEWYa-iQwHaGi3gcosAJc&sensor=false"></script>
		<script type="text/javascript" src="js/location.js"></script>
		<script type="text/javascript" src="js/map.js"></script>
		<script type="text/javascript" src="js/marker.js"></script>
		<!--<script type="text/javascript" src="js/markerwindow.js"></script>
        <script type="text/javascript" src="js/user.js"></script>-->
        <script type="text/javascript">
		
			var map;
			var markers = {};
		
			(function(){
			
				<?php echo "var session_id = '" . $sid . "';"; ?>
				
				var windowHash = window.location.hash;
				
				if(windowHash != "") {
					var assoc_session_id = windowHash.substring(1,windowHash.length);
					showPopup('receiver');
				} else {
					var assoc_session_id = session_id;
					window.location.hash = assoc_session_id;
					showPopup('user');
					//alert("Send the current URL to anyone you're looking to meet!");
				}
					
				map = new Map();
				
				$(window).load(init);
				
				function init() {
				
					var mapStyle = [{"featureType":"landscape","stylers":[{"hue":"#F1FF00"},{"saturation":-27.4},{"lightness":9.4},{"gamma":1}]},{"featureType":"road.highway","stylers":[{"hue":"#0099FF"},{"saturation":-20},{"lightness":36.4},{"gamma":1}]},{"featureType":"road.arterial","stylers":[{"hue":"#00FF4F"},{"saturation":0},{"lightness":0},{"gamma":1}]},{"featureType":"road.local","stylers":[{"hue":"#FFB300"},{"saturation":-38},{"lightness":11.2},{"gamma":1}]},{"featureType":"water","stylers":[{"hue":"#00B6FF"},{"saturation":4.2},{"lightness":-63.4},{"gamma":1}]},{"featureType":"poi","stylers":[{"hue":"#9FFF00"},{"saturation":0},{"lightness":0},{"gamma":1}]}];
					
					map.getInstance(mapStyle);
					
					getLocation();
					
					setInterval(interval,3000);
				
				}
				
				function errorCallback(error) {
					var message = ""; 
					  switch (error.code) {
					  case error.PERMISSION_DENIED:
						message = "We do not have permission to discover your location. We need this in order for the application to function correctly!";
						break;
					  case error.POSITION_UNAVAILABLE:
						message = "The current position could not be determined. We need this in order for the application to function correctly!";
						break;
					  case error.PERMISSION_DENIED_TIMEOUT:
						message = "We tried to discover your location but our request timed out. Please refresh your browser.";           
						break;
					}

					if (message == "")
					{
						//var strErrorCode = error.code.toString();
						//message = "The position could not be determined due to " + "an unknown error (Code: " + strErrorCode + ").";
						//message = "We tried to discover your location but failed. Please make sure you have allowed your browser to utilise your geolocation services."; 
					}
					
					if (message != "")
						alert(message);
				} 
				
				function getLocation() {
					if (navigator.geolocation) {
						//navigator.geolocation.watchPosition(updatePosition,function(){},{enableHighAccuracy: true,timeout: 2000,maximumAge: 0});
						function timeout() {
							setTimeout(function () {
								navigator.geolocation.getCurrentPosition(updatePosition,errorCallback, {enableHighAccuracy : true, maximumAge: 4000, timeout: 5000});
								timeout();
							}, 2000);
						} timeout();
					} else {
						alert("Geolocation is not supported by this browser.");
					}
				}
				updatePosition = function updatePosition(position) {

					console.log('Updating...');
					
					$.ajax({
						url:'update_user.php',
						method: 'POST',
						data: {
							latitude: position.coords.latitude,
							longitude: position.coords.longitude,
							session_id: session_id,
							assoc_session_id: assoc_session_id
						},
						success: function(data) {
							// success
						}
					});
				}
				
				function interval(){
					
					$.ajax({
						url:'get_users.php',
						method: 'POST',
						data: {
							assoc_session_id: assoc_session_id
						},
						success: function(data) {
							updateMarkers(JSON.parse(data));
						}
					});
				}
				
				function updateMarkers(data) {
				
					var zoomShowAll = false;
					for(var piece in data) {
						if(markers[ data[piece].session_id ]) {
							markers[ data[piece].session_id ].setMarkerPosition(data[piece].latitude, data[piece].longitude);
						} else {
							markers[ data[piece].session_id ] = new Marker({
								latitude: data[piece].latitude,
								longitude: data[piece].longitude,
								type: data[piece].type
							});
							zoomShowAll = true;
						}
					}
					if(zoomShowAll) {
						var lngLatArray = [];
						for(var piece in data) {
							lngLatArray.push( new google.maps.LatLng (data[piece].latitude, data[piece].longitude) );
						}
						map.showAllMarkers(lngLatArray);
					}
				}
				
				function showPopup(userType) {
					if(userType == "user") {
						setTimeout(function(){
							$('#overlay').fadeIn(100);
							$('#initial-popup').fadeIn(200).click(function(){
								$('#initial-popup').fadeOut(200);
							});
							$('#user-popup').fadeIn(200);
							$('#url-input').val(document.URL);
							$('#url-picker-input').val(document.URL);
							$('#close-popup').click(function(){
								$('#user-popup').fadeOut(200);
								$('#overlay').fadeOut(300);
								$('#url-picker').animate({opacity:1},500);
							});
							//$("input:text").focus(function() { $(this).select(); } );
						},300);
					} else if(userType == "receiver") {
						setTimeout(function(){
							$('#overlay').fadeIn(100);
							$('#initial-popup').fadeIn(200).click(function(){
								$('#initial-popup').fadeOut(200);
							});
							$('#receiver-popup').fadeIn(200);
							$('#receiver-popup').click(function(){
								$('#receiver-popup').fadeOut(200);
								$('#overlay').fadeOut(300);
								$('#url-picker').animate({opacity:1},500);
							});
						},300);
					}
				}
				
			})();
			
			
        </script>
		
    </head>
    <body>
	
		<script>
			$(document).ready(function(){
				$('#url-picker').on('click',function() {
					if(!$(this).hasClass('active'))
						$(this).stop().addClass('active', {duration:500});
					else
						$('#url-picker').stop().removeClass('active');						
					if($(this).val() == "")
						$('#url-picker-input').val(document.URL);
				});
				$('.url-picker-close').on('click',function(e) {
					console.log("clicked");
					e.stopPropagation();
					$('#url-picker').stop().removeClass('active');
				});
			});
		</script>
	
		<div id="url-picker">
			<div class="view-link-image">View Link</div>
			<div class="url-picker">
				<input id="url-picker-input" onClick="this.setSelectionRange(0, this.value.length);window.JSInterface.copyLink(this.value);" type="text" value="" readonly>
				<div class="url-picker-close"></div>
			</div>
		</div>
	
        <!-- The element that will contain our Google Map. This is used in both the Javascript and CSS above. -->
        <div id="map"></div>
		<div id="overlay"></div>
		<div id="initial-popup" class="popup">
		</div>
		<div id="user-popup" class="popup">
			<div class="popup-view">
				<div id="close-popup"></div>
				<input id="url-input" onClick="this.setSelectionRange(0, this.value.length);window.JSInterface.copyLink(this.value);" type="text" value="" readonly>
			</div>
		</div>
		<div id="receiver-popup" class="popup">
		</div>
    </body>
</html>
