<?php


	if(!isset($_COOKIE['PHPSESSID']) && !isset($_GET['PHPSESSID']) ) {
		session_id(uniqid("User--"));
		session_start();
		$_SESSION['id']=session_id();
		$newUser = "true";
	} else {
		session_start();
		$newUser = "false";
	}
	
	function UserData() {

		if (!empty($_SERVER["HTTP_CLIENT_IP"])) {
			//check for ip from share internet
			$ip = $_SERVER["HTTP_CLIENT_IP"];
		} elseif (!empty($_SERVER["HTTP_X_FORWARDED_FOR"])) {
			// Check for the Proxy User
			$ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
		} else {
			$ip = $_SERVER["REMOTE_ADDR"];
		}
			
		if($ip == "127.0.0.1")		
			$ip = "78.147.172.0";
			
		$_SESSION['ip'] = $ip;

		$url = "http://freegeoip.net/json/" . $ip;
		// create a new cURL resource
		$ch = curl_init();

		// set URL and other appropriate options
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 

		// grab URL and pass it to the browser
		$data = json_decode( curl_exec($ch) );

		$http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

		// close cURL resource, and free up system resources
		curl_close($ch);
		
		if($http_status != 200)
			return false;
		
		return $data;
	}
	
	$UserData = UserData();
	
	if($UserData !== false) {
		$latitude = $UserData->latitude != 0 ? $UserData->latitude : -0.1313;
		$longitude = $UserData->longitude != 0 ? $UserData->longitude : 50.8429;
	} else {
		$latitude = -0.1313;
		$longitude = 50.8429;
	}
	
?>