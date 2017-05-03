<?php
header('Content-Type: application/json');
require_once("../include/db_connect.php");
$param  = $_GET['q'];
$result = $mysqli->query("SELECT LEAD_ID, CLIENT_EMAIL FROM leads WHERE client_email LIKE '%" . $param . "%' LIMIT 10")
		or die(mysqli_error());
while($row = mysqli_fetch_array($result)){
	$arr[] = array('id' => $row['LEAD_ID'], 'name' => $row['CLIENT_EMAIL']);
}
echo json_encode($arr);

# Optionally: Wrap the response in a callback function for JSONP cross-domain support
/*if($_GET["callback"]) {
    $json_response = $_GET["callback"] . "(" . $json_response . ")";
}*/
?>
