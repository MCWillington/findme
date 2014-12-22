<?php

$hostname = "localhost";
$username = "findmeuser";
$password = "wh3r37r3u";
$database = "findme";

$mysqli = new mysqli($hostname, $username, $password, $database);
/* check connection */
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}

// check if entry already exists in database under this IP
$query = mysqli_query($mysqli, "SELECT * FROM users WHERE session_id='" . $mysqli->real_escape_string($_POST['session_id']) . "'");

// if it does then just update the details
if(mysqli_num_rows($query) > 0) {

    $stmt = $mysqli->prepare("UPDATE users SET lat = ?, lng = ? WHERE session_id = ?");
	$stmt->bind_param('sss',
		$_POST['latitude'],
		$_POST['longitude'],
	   $_POST['session_id']);
	$stmt->execute(); 
	$stmt->close();
	
// else insert as a new row	
} else {
	
	$stmt = $mysqli->prepare("INSERT INTO users(session_id,assoc_session_id,lat,lng) VALUES (?, ?, ?, ?)");
	$stmt->bind_param('ssss', 
	$_POST['session_id'], 
	$_POST['assoc_session_id'],
	$_POST['latitude'],
	$_POST['longitude']);
	$stmt->execute(); 
	$stmt->close();

}



?>