<?php
require_once("include/checklogin.php");
require_once("include/session.php");
require_once("include/db_connect.php");

//parse URL for variables
$action = $_GET["action"];
$lead_id = $_GET["lead_id"];
if ($lead_id==null) {
	$lead_id = $_POST["lead_id"];
}
$property_id = $_GET["property_id"];
if ($property_id==null) {
	$property_id = $_POST["property_id"];
}

if ($action=="getPropertyInfo") {
	if ($property_id!=null) {
		$results = $mysqli->query("SELECT * FROM properties JOIN providers ON properties.PROVIDER_ID=providers.PROVIDER_ID WHERE property_id=" . $property_id) or die($mysqli->error);
		$data = mysqli_fetch_assoc($results);
		mysqli_free_result($results);
		echo json_encode($data);
	} else {
		echo "Not Found";
	}
} else {
	echo "Error: Action not specified";
}

?>