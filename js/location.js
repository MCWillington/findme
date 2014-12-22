
function Location(callback) {

	var self = this;
	this.latitude = null;
	this.longitude = null;
	this.callback = callback || null;

	var options = {
	  enableHighAccuracy: true,
	  timeout: 5000,
	  maximumAge: 0
	};

	function success(pos) {
	  var crd = pos.coords;
	  
	  self.latitude = crd.latitude;
	  self.longitude = crd.longitude;
	  
		if(typeof self.callback == "function") {
			console.log(self.callback);
			self.callback();
		}
	  
	};

	function error(err) {
		console.warn('ERROR(' + err.code + '): ' + err.message);
	  
		if(err.code == 1) // Permission denied
		{

		}
		
		if(err.code == 2) // Position unavailable
		{
			alert("We are unable to obtain your position at this time.");
		}
		
		if(err.code == 3) // Timeout
		{
			alert("We are unable to obtain your position at this time.");
		}
	  
	};

	function getPosition() {
		navigator.geolocation.getCurrentPosition(success, error, options);
	}
	
	getPosition();
}