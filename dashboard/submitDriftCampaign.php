<?php
require_once("include/checklogin.php");
require_once("include/db_connect.php");

$lead_type 	= $_POST["lead_type"];
$lead_id 	= $_POST["search_leads_auto_completec"];
$email_template_id = $_POST["template"];
$date_to_send 	= $_POST["date_to_send"];
$subject 		= $_POST["subject"];
$body_content 	= $_POST["messageMassEmailc"];
$status 		= 1;
$date_created 	= date("Y-m-d H:i:s");


$stmt = $mysqli->prepare("INSERT INTO drip_campaign (
								lead_type, lead_id, email_template_id, date_to_send, subject, 
								body_content, status, date_created)
								VALUES (?, ?, ?, ?, ?, ?, ?, ?)
								") or die($mysqli->error);
		$stmt->bind_param("ssssssss",
			$mysqli->real_escape_string($lead_type),
			$mysqli->real_escape_string($lead_id),
			$mysqli->real_escape_string($email_template_id),
			$mysqli->real_escape_string($date_to_send),
			$mysqli->real_escape_string($subject),
			$mysqli->real_escape_string($body_content),
			$mysqli->real_escape_string($status),
			$mysqli->real_escape_string($date_created)
		) or die($mysqli->error);

		/* Execute the statement */
		$stmt->execute() or die("Error: Could not execute statement");

		/* close statement */
		$stmt->close() or die("Error: Could not close statement");

header("Location: dripCampaign.php");
?>