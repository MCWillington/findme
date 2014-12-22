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

// get all user entries
//$stmt = $mysqli->prepare("SELECT * FROM users WHERE `updated` > DATE_ADD(CURRENT_TIMESTAMP, INTERVAL -120 MINUTE) AND assoc_session_id = ?");

//$stmt = $mysqli->prepare("SELECT * FROM users WHERE assoc_session_id = ?");
//$stmt->bind_param('s', $_POST['assoc_session_id']);
//$stmt->execute(); 

$result = $mysqli->query("SELECT * FROM users WHERE assoc_session_id = '" . $mysqli->real_escape_string($_POST['assoc_session_id']) ."' AND `updated` > DATE_ADD(CURRENT_TIMESTAMP, INTERVAL -30 MINUTE)");
	
$userArray = null;
while ($row = $result->fetch_assoc()) { 
	$userArray[] = array(
		'session_id' => $row['session_id'],
		'latitude' => $row['lat'],
		'longitude' => $row['lng'],
		'type' => $row['session_id'] == $row['assoc_session_id'] ? 'me' : 'other'
	); 
} 

//$stmt->close();

echo json_encode($userArray);

?>