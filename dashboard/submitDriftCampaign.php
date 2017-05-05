<?php

require_once("include/db_connect.php");




// $redir = "";
// if (isset($_POST["redirect"])) {
//   $redir = $_POST["redirect"];
// }
$lead_type = $_POST["lead_type"];
$lead_id = $_POST["search_leads_auto_completec"];
$email_template_id = $_POST["template"];
$date_to_send = $_POST["date_to_send"];
$subject = $_POST["subject"];
$body_content = $_POST["messageMassEmailc"];
$status = 1;
$date_created = date("Y-m-d H:i:s");


//if ($first_name != "" && $last_name != "" && $client_email != "" && $first_name != "1" && $last_name != "1" && $client_email != "1") {

  $stmt = $mysqli->prepare("INSERT INTO drip_campaign (lead_type, lead_id, email_template_id, date_to_send, subject,
			body_content, status, date_created)
			VALUES ('$lead_type', '$lead_id', '$email_template_id', '$date_to_send', '$subject', '$body_content', $status, '$date_created')
			") or die($mysqli->error);
  // $stmt->bind_param("ssssssssis",
		//   $mysqli->real_escape_string(stripslashes($lead_type)),
		//   $mysqli->real_escape_string(stripslashes($lead_id)),
		//   $mysqli->real_escape_string(stripslashes($email_template_id)),
		//   $mysqli->real_escape_string($date_to_send),
		//   $mysqli->real_escape_string($subject),
		//   $mysqli->real_escape_string($body_content),
		//   $mysqli->real_escape_string($status),
		//   $date_created
		//   ) or die($mysqli->error);

  $stmt->execute() or die($mysqli->error);
  $lead_id = $mysqli->insert_id;
  $stmt->close() or die($mysqli->error);


echo "<pre>";
print_r($_POST);
echo "</pre>";
exit; 


//   if ($mysqli->affected_rows == 0) {
// 	header("Location: error.php");
//   }

//   if ($redir == "") {
// 	header("Location: thanks.php");
//   } else {
// 	header("Location: " . $redir);
//   }

// } else {
//   header("Location: error.php");
// }
?>