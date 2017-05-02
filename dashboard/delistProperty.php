<?php
require_once("include/checklogin.php");
require_once("include/db_connect.php");

// confirm that the 'id' variable has been set
if (isset($_GET["property_id"]) && is_numeric($_GET["property_id"])) {
	// get the 'property_id' variable from the URL
	$id = $_GET["property_id"];

 // delete the entry
 $result = $mysqli->query("UPDATE properties SET delist=1 WHERE property_id=$id") or die($mysqli->error);

	// redirect user after delete is successful
	header("Location: " . $_SERVER['HTTP_REFERER']);
}
else {
	// if the 'id' variable isn't set, display an error message

}

?>