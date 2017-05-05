<?php

require_once("include/db_connect.php");

$redir = "";
if (isset($_POST["redirect"])) {
  $redir = $_POST["redirect"];
}
$company_name = $_POST["company_name"];
$first_name = $_POST["first_name"];
$last_name = $_POST["last_name"];
$client_email = $_POST["client_email"];
$office_phone = $_POST["office_phone"];
$property_type = $_POST["property_type"];
$search_city = $_POST["search_city"];
$provider_info = $_POST["provider_info"];
$affiliate_id = $_POST["affiliate_id"];

if ($first_name != "" && $last_name != "" && $client_email != "" && $first_name != "1" && $last_name != "1" && $client_email != "1") {

  $stmt = $mysqli->prepare("INSERT INTO leads (COMPANY_NAME, FIRST_NAME, LAST_NAME, CLIENT_EMAIL, OFFICE_PHONE,
			PROPERTY_TYPE, SEARCH_CITY, PROVIDER_INFO,
			STATUS, USERNAME, AFFILIATE_ID, DATE_ADDED)
			VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'New', 'Not Assigned', ?, ?)
			") or die($mysqli->error);
  $stmt->bind_param("ssssssssis",
		  $mysqli->real_escape_string(stripslashes($company_name)),
		  $mysqli->real_escape_string(stripslashes($first_name)),
		  $mysqli->real_escape_string(stripslashes($last_name)),
		  $mysqli->real_escape_string($client_email),
		  $mysqli->real_escape_string($office_phone),
		  $mysqli->real_escape_string($property_type),
		  $mysqli->real_escape_string(stripslashes($search_city)),
		  stripslashes(str_replace('\r\n', ' ', $mysqli->real_escape_string($provider_info))),
		  $mysqli->real_escape_string($affiliate_id),
		  date("Y-m-d H:i:s")
		  ) or die($mysqli->error);

  $stmt->execute() or die($mysqli->error);
  $lead_id = $mysqli->insert_id;
  $stmt->close() or die($mysqli->error);

  if ($mysqli->affected_rows == 0) {
	header("Location: error.php");
  }

  if ($redir == "") {
	header("Location: thanks.php");
  } else {
	header("Location: " . $redir);
  }

} else {
  header("Location: error.php");
}
?>