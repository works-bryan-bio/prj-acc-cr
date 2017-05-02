<?php

require_once("include/checklogin.php");
require_once("include/session.php");
require_once("include/db_connect.php");

//parse URL for variables
$action = $_GET['action'];
$lead_id = $_GET['lead_id'];
$delay = $_GET['delay'];
$property_id = $_GET['property_id'];
if ($property_id == null) {
	$property_id = $_POST['property_id'];
}

if ($action == 'add') {

	$properties = $_POST['selectedProperties'];
	$total = count($properties);
	for ($i = 0; $i < $total; $i++) {
		$query = $mysqli->query("SELECT * FROM search_report WHERE lead_id=" . $lead_id . " AND PROPERTY_ID=" . $properties[$i]) or die($mysqli->error);
		if ($mysqli->affected_rows == 0) {
			$stmt = $mysqli->prepare("INSERT INTO search_report (LEAD_ID, PROPERTY_ID) VALUES (?, ?)") or die($mysqli->error);
			$stmt->bind_param("ii", $mysqli->real_escape_string($lead_id), $mysqli->real_escape_string($properties[$i])
				) or die($mysqli->error);

			/* Execute the statement */
			$stmt->execute() or die("Error: Could not execute statement");

			/* close statement */
			$stmt->close() or die("Error: Could not close statement");
		}
	}
	// once added, redirect back to the search report
	header("Location: searchReport.php?lead_id=$lead_id");

} elseif ($action == 'remove') {

	$stmt = $mysqli->prepare("DELETE FROM search_report WHERE LEAD_ID=? AND PROPERTY_ID=?") or die($mysqli->error);
	$stmt->bind_param("ii", $mysqli->real_escape_string($lead_id), $mysqli->real_escape_string($property_id)
		) or die($mysqli->error);

	/* Execute the statement */
	$stmt->execute() or die("Error: Could not execute statement");

	/* close statement */
	$stmt->close() or die("Error: Could not close statement");

	// once removed, redirect back to the search report
	header("Location: searchReport.php?lead_id=$lead_id");

} elseif ($action == 'favorite') {

	$status = $_GET['status'];
	if ($status == "null") {
		$newval = 0;
	}
	if ($status == "favorite") {
		$newval = 1;
	}
	$stmt = $mysqli->prepare("UPDATE search_report SET FAVORITE=? WHERE LEAD_ID=? AND PROPERTY_ID=?") or die($mysqli->error);
	$stmt->bind_param("iii", $newval, $mysqli->real_escape_string($lead_id), $mysqli->real_escape_string($property_id)
		) or die($mysqli->error);

	/* Execute the statement */
	$stmt->execute() or die("Error: Could not execute statement");

	/* close statement */
	$stmt->close() or die("Error: Could not close statement");

} elseif ($action == 'status') {

	$status = $_GET['status'];
	if ($status == "null") {
		$newval = "ACCEPTED=false, REJECTED=false";
	}
	if ($status == "accepted") {
		$newval = "ACCEPTED=true, REJECTED=false";
	}
	if ($status == "rejected") {
		$newval = "ACCEPTED=false, REJECTED=true";
	}
	$result = $mysqli->query("UPDATE search_report SET $newval WHERE LEAD_ID=$lead_id AND PROPERTY_ID=$property_id") or die("$mysqli->error");

} elseif ($action == 'rejectreason') {

	$reason = $_GET['reason'];
	$result = $mysqli->query("UPDATE search_report SET REJECTED_REASON='" . $reason . "' WHERE LEAD_ID=$lead_id AND PROPERTY_ID=$property_id") or die("$mysqli->error");

} elseif ($action == 'tour_date') {

	$value = $_GET['value'];
	$stmt = $mysqli->prepare("UPDATE search_report SET TOUR_DATE=? WHERE LEAD_ID=? AND PROPERTY_ID=?") or die($mysqli->error);
	$stmt->bind_param("sii", $mysqli->real_escape_string($value), $mysqli->real_escape_string($lead_id), $mysqli->real_escape_string($property_id)
		) or die($mysqli->error);

	$error = "";

	/* Execute the statement */
	$stmt->execute() or die($error = "Error: Could not execute statement");

	/* close statement */
	$stmt->close() or die($error = "Error: Could not close statement");

	echo $error;

} elseif ($action == 'tour_time') {

	$value = $_GET['value'];
	$stmt = $mysqli->prepare("UPDATE search_report SET TOUR_TIME=? WHERE LEAD_ID=? AND PROPERTY_ID=?") or die($mysqli->error);
	$stmt->bind_param("sii", $mysqli->real_escape_string($value), $mysqli->real_escape_string($lead_id), $mysqli->real_escape_string($property_id)
		) or die($mysqli->error);

	$error = "";

	/* Execute the statement */
	$stmt->execute() or die($error = "Error: Could not execute statement");

	/* close statement */
	$stmt->close() or die($error = "Error: Could not close statement");

	echo $error;

} elseif ($action == 'sendIntro') {

	$full = $session->userinfo['fullname'];
	$email = $session->userinfo['email'];
	$made_contact = $_GET['made_contact'];
	$result = $mysqli->query("SELECT * FROM leads WHERE lead_id=" . $lead_id) or die($mysqli->error);
	if ($mysqli->affected_rows > 0) {
		while ($row = mysqli_fetch_array($result)) {
			$first = $row['FIRST_NAME'];
			$to = $row['CLIENT_EMAIL'];
			$from = $full . " <" . $email . ">";

			if ($made_contact == "yes") {
				$title = "Thanks For Your Inquiry!";
				$body = "Thank you for taking the time to talk with me about your property search!
				<br /><br />
				I am preparing a custom search report for you now. You should receive my
				search report within an hour.";
			} else if ($made_contact == "no") {
				$title = "Please Contact Me Immediately!";
				$body = "I appreciate your property inquiry!
				<br /><br />
				I am ready to help with your property search now. I tried to contact you to
				go over your specific search criteria but you were not available at the time I
				called.
				<br /><br />
				Please contact me immediately so I can get your specific requirements to
				prepare an accurate list for your review! This report will have all the
				details of the properties in your search area, including pictures and maps.
				Once you are ready we will set appoints, get quotes, and find you an awesome
				deal!
				<br /><br />
				Please call or email me NOW to ensure you get the best level of service from
				a <strong>licensed professional</strong> in finding your next office space.";
			}

			$message = "<div style='font: normal small Helvetica Neue,Helvetica,Arial,sans-serif; color:#333; width:640px;'>
				<table cellspacing='5' width='100%' style='font: normal small Verdana, Arial, Helvetica, sans-serif;'>
				<tr>
				<td align='left'><img src='http://www.crmds.net/images/simplehousesolutions.png' /></td>
				<td align='right' valign='bottom' style='font-size:16px;font-variant:small-caps;'>" . $title . "</td>
				</tr>
				<tr>
				<td colspan='2' style='font: normal small Helvetica Neue,Helvetica,Arial,sans-serif; color:#333; width:640px;'>
				" . $first . ",<br /><br />
				" . $body . "
				<p />
				Sincerely,<br />
				" . $full . "<br />
				Office: 972-876-3131<br />
				</td>
				</tr>
				</table>
				</div>";

			if ($from != null && $from != "") {
				if ($to != null && $to != "") {
					$headers = "MIME-Version: 1.0\r\n";
					$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
					$headers .= "From: " . $from . "\r\n";
					$subject = "SimpleHouseSolutions - " . $title;
					mail($to, $subject, $message, $headers);
					$mysqli->query("UPDATE leads SET INTRO_SENT=SYSDATE() WHERE LEAD_ID=" . $lead_id) or die($mysqli->error);
					echo "Intro email sent successfully";
				} else {
					echo "Error: Client's email address cannot be blank/null";
				}
			} else {
				echo "Error: From address cannot be blank/null";
			}
		}
	} else {
		echo "Error: Lead " . $lead_id . " does not exist";
	}

} elseif ($action == 'sendSearchReport') {

	$full = $session->userinfo['fullname'];
	$email = $session->userinfo['email'];
	$result = $mysqli->query("SELECT * FROM leads WHERE lead_id=" . $lead_id) or die($mysqli->error);
	if ($mysqli->affected_rows > 0) {
		while ($row = mysqli_fetch_array($result)) {
			$first = $row['FIRST_NAME'];
			$to = $row['CLIENT_EMAIL'];
			$from = $full . " <" . $email . ">";
			$message = "
				<div style='font: normal small Helvetica Neue,Helvetica,Arial,sans-serif; color:#333; width:500px;'>
				<table cellspacing='5' width='100%' style='font: normal small Verdana, Arial, Helvetica, sans-serif;'>
				<tr>
				<td align='left'><img src='http://www.crmds.net/images/simplehousesolutions.png' /></td>
				<td align='right' valign='bottom' style='font-size:16px;font-variant:small-caps;'>Your Search Report is ready!</td>
				</tr>
				<tr>
				<td colspan='2' style='<div style='font: normal small Helvetica Neue,Helvetica,Arial,sans-serif; color:#333; width:500px;'>
				" . $first . ",<br /><br />
				Your Search Report is available at the link below:<br /><br />
				<a href='http://www.crmds.net/searchReport.php?id=" . $lead_id . "'>View My Report</a><br /><br />
				There are some great options available in your search area! Please contact me to arrange the tours.<br /><br />
				If you have any questions, please respond to this email or you can contact me at the phone number below.<br />
				<p />
				Sincerely,<br />"
				. $full . "<br />
				Office: 972-876-3131<br />
				</td></tr>
				</table>
				</div>";
			if ($from != null && $from != "") {
				if ($to != null && $to != "") {
					$headers = "MIME-Version: 1.0\r\n";
					$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
					$headers .= "From: " . $from . "\r\n";
					$subject = "SimpleHouseSolutions - Search Report";
					if ($delay == null) {
						mail($to, $subject, $message, $headers);
						$mysqli->query("UPDATE leads SET SEARCH_REPORT_SENT=SYSDATE() WHERE LEAD_ID=" . $lead_id) or die($mysqli->error);
						echo "Search Report sent successfully";
					} else {
						$date_submitted = time();
						$stmt = $mysqli->prepare("INSERT INTO cron_emails VALUES (NULL, ?, ?, ?, ?, ?)") or die($mysqli->error);
						$stmt->bind_param("ssssi", $to, $subject, $message, $headers, $date_submitted) or die($error = $mysqli->error);
						/* Execute the statement */
						$stmt->execute() or die($error = "Error: Could not execute statement");
						/* close statement */
						$stmt->close() or die($error = "Error: Could not close statement");
						$mysqli->query("UPDATE leads SET SEARCH_REPORT_SENT=SYSDATE() WHERE LEAD_ID=" . $lead_id) or die($mysqli->error);
						echo "Search Report delayed for delivery";
					}
				} else {
					echo "Error: Client's email address cannot be blank/null";
				}
			} else {
				echo "Error: From address cannot be blank/null";
			}
		}
	} else {
		echo "Error: Lead " . $lead_id . " does not exist";
	}

} elseif ($action == 'generateAppointment') {

	$full = $session->userinfo['fullname'];
	$email = $session->userinfo['email'];
	$result = $mysqli->query("SELECT * FROM leads WHERE lead_id=" . $lead_id) or die($mysqli->error);
	if ($mysqli->affected_rows > 0) {
		while ($row = mysqli_fetch_array($result)) {
			$first = $row['FIRST_NAME'];
			$result = $mysqli->query("SELECT * FROM search_report JOIN properties ON search_report.PROPERTY_ID=properties.PROPERTY_ID WHERE LEAD_ID=" . $lead_id .
				" AND search_report.PROPERTY_ID=" . $property_id . " AND TOUR_DATE IS NOT NULL AND TOUR_DATE!='0000-00-00' AND TOUR_TIME IS NOT NULL AND TOUR_TIME!='00:00:00'")
				or die($mysqli->error);
			if ($mysqli->affected_rows > 0) {
				while ($row = mysqli_fetch_array($result)) {
					$message = "
						<div style='font: normal small Helvetica Neue,Helvetica,Arial,sans-serif; color:#333; width:500px;'>
						<table width='100%'>
						<tr>
						<td align='left' valign='bottom' style='font-size:16px;font-variant:small-caps;'>Your Appointment Has been Booked!</td>
						<td align='right'><img src='http://www.crmds.net/images/simplehousesolutions.png' /></td>
						</tr>
						<tr><td colspan='2'><hr></td></tr>
						</table>
						" . $first . ",<br /><br />
						Your appointment has been confirmed with the following property:<br /><br />
						<table style='border-collapse:collapse; font-size:11px; background:#E0E0E0; width:100%;'>
					";

					$address = "";
					if ($row['CENTER_NAME'] != "")
						$address .= $row['CENTER_NAME'] . '<br />';
					if ($row['ADDRESS_1'] != "")
						$address .= $row['ADDRESS_1'] . '<br />';
					if ($row['ADDRESS_2'] != "")
						$address .= $row['ADDRESS_2'] . '<br />';
					if ($row['CITY'] != "")
						$address .= $row['CITY'] . ', ';
					if ($row['STATE'] != "")
						$address .= $row['STATE'] . ' ';
					if ($row['ZIP'] != "")
						$address .= $row['ZIP'] . '<br />';
					if ($row['PRIMARY_PHOTO'] != null) {
						$image = '<img src="http://www.crmds.net/dashboard/getimage.php?id=' . $property_id . '&image=photo_' . $row['PRIMARY_PHOTO'] . '" />';
					}
					$message .= "<tr style='padding-top:5px;'>" .
						"<td valign='top' align='right' style='padding:5px;'><strong>Appointment Date:</strong></td>" .
						"<td valign='top' align='left' style='padding:5px;'>" . date('m/d/Y', strtotime($row['TOUR_DATE'])) . "</td>" .
						"<td valign='top' align='right' style='padding:5px;'><strong>Time:</strong></td>" .
						"<td valign='top' align='left' style='padding:5px;'>" . date('g:i a', strtotime($row['TOUR_TIME'])) . "</td>" .
						"<td rowspan='5'>" . $image . "</td>" .
						"</tr><tr>" .
						"<td valign='top' align='right' valign='top' style='padding:5px;'><strong>Address:</strong></td>" .
						"<td valign='top' align='left' colspan='3' style='padding:5px;'>" . $address . "</td>" .
						"</tr>";

					if ($row['CONTACT_NAME'] != '' && $row['OFFICE_PHONE'] != '') {
						$contact_name .= $row['CONTACT_NAME'];
						$contact_phone .= '<a href="tel:' . $row['OFFICE_PHONE'] . '">' . $row['OFFICE_PHONE'] . '</a>';
					}
					$message .= "<tr>" .
						"<td valign='top' align='right' valign='top' style='padding:5px;'><strong>Contact Name:</strong></td>" .
						"<td valign='top' align='left' colspan='3' style='padding:5px;'>" . $contact_name . "</td>" .
						"</tr><tr>" .
						"<td valign='top' align='right' valign='top' style='padding:5px;'><strong>Contact Phone:</strong></td>" .
						"<td valign='top' align='left' colspan='3' style='padding:5px;'>" . $contact_phone . "</td>" .
						"</tr>";
					if ($row['PROP_LAT'] != '' && $row['PROP_LONG'] != '') {
						$map_link .= '<a href="http://maps.google.com/maps?q=' . $row['PROP_LAT'] . ',' . $row['PROP_LONG'] . '">Map Link</a>';
					}
					$message .= "<tr>" .
						"<td valign='top' align='right' valign='top' style='padding:5px;'><strong>Directions:</strong></td>" .
						"<td valign='top' align='left' colspan='3' style='padding:5px;'>" . $map_link . "</td>" .
						"</tr>";
					$message .= "</table><br />"
						. $full . "<br />"
						. $email . "<br />
						Office: 972-876-3131<br />
						</div>";
					echo $message;
				}
			} else {
				echo "Error: You must first set the Tour Date/Time fields!";
			}
		}
	} else {
		echo "Error: Lead " . $lead_id . " does not exist";
	}

} elseif ($action == 'sendEmail') {

	$subject = stripslashes($_POST['subject']);
	$message = stripslashes($_POST['message']);
	if ($message != null && $message != "") {
		$full = $session->userinfo['fullname'];
		$email = $session->userinfo['email'];
		$from = $full . " <" . $email . ">";
		$result = $mysqli->query("SELECT * FROM leads WHERE LEAD_ID=" . $lead_id)
			or die($mysqli->error);
		if ($mysqli->affected_rows > 0) {
			while ($row = mysqli_fetch_array($result)) {
				$to = $row['CLIENT_EMAIL'];
				$cc = $row['CONTACT_EMAIL'];
				if ($from != null && $from != "") {
					if ($to != null && $to != "") {
						$message .= "
							<p />
							Sincerely,<br />"
							. $full . "<br />
							Office: 972-876-3131<br />
							</td></tr>
							</table>
							</div>";
						$headers = "MIME-Version: 1.0\r\n";
						$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
						$headers .= "From: " . $from . "\r\n";
						$subject = "SimpleHouseSolutions - " . $subject;
						mail($to, $subject, $message, $headers);
						echo "Email sent successfully";
					} else {
						echo "Error: Client's email address cannot be blank/null";
					}
				} else {
					echo "Error: Unable to determine from address";
				}
			}
		} else {
			echo "Error: Lead " . $lead_id . " does not exist";
		}
	} else {
		echo "Error: Message empty or null";
	}

} elseif ($action == 'sendAppointment') {

	$message = stripslashes($_POST['message']);
	//echo $message;
	$full = $session->userinfo['fullname'];
	$email = $session->userinfo['email'];
	$from = $full . " <" . $email . ">";
	$result = $mysqli->query("SELECT * FROM search_report JOIN properties ON search_report.PROPERTY_ID=properties.PROPERTY_ID" .
		" JOIN leads ON search_report.lead_id=leads.lead_id WHERE search_report.LEAD_ID=" . $lead_id .
		" AND search_report.PROPERTY_ID=" . $property_id . " AND TOUR_DATE IS NOT NULL AND TOUR_DATE!='0000-00-00' AND TOUR_TIME IS NOT NULL AND TOUR_TIME!='00:00:00'")
		or die($mysqli->error);
	if ($mysqli->affected_rows > 0) {
		while ($row = mysqli_fetch_array($result)) {
			$to = $row['CLIENT_EMAIL'];
			$cc = $row['CONTACT_EMAIL'];
			if ($from != null && $from != "") {
				if ($to != null && $to != "") {
					$headers = "MIME-Version: 1.0\r\n";
					$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
					$headers .= "From: " . $from . "\r\n";
					$headers .= "Cc: " . $cc . "\r\n";
					$subject = "SimpleHouseSolutions - Appointment Confirmation [" . $row['CENTER_NAME'] . "]";
					mail($to, $subject, $message, $headers);
					header("Location: searchReport.php?lead_id=$lead_id");
				} else {
					echo "Error: Client's email address cannot be blank/null";
				}
			} else {
				echo "Error: Unable to determine from address";
			}
		}
	} else {
		echo "Error: Lead " . $lead_id . " does not exist";
	}

} elseif ($action == 'generateUpdate') {

	$full = $session->userinfo['fullname'];
	$email = $session->userinfo['email'];
	$result = $mysqli->query("SELECT * FROM leads WHERE lead_id=" . $lead_id) or die($mysqli->error);
	if ($mysqli->affected_rows > 0) {
		while ($row = mysqli_fetch_array($result)) {
			$company_name = stripslashes($row['COMPANY_NAME']);
			$first = stripslashes($row['FIRST_NAME']);
			$last = stripslashes($row['LAST_NAME']);
			$client_name = $first . " " . $last;
			$office = $row['OFFICE_PHONE'];
			$cell = $row['CELL_PHONE'];
			$info = nl2br(stripslashes($row['PROVIDER_INFO']));
			$query = "SELECT providers.COMPANY_NAME, INQUIRY_TIMESTAMP FROM search_report " .
				"JOIN properties ON search_report.PROPERTY_ID=properties.PROPERTY_ID " .
				"JOIN providers ON providers.PROVIDER_ID=properties.PROVIDER_ID " .
				"WHERE properties.PROPERTY_ID=" . $property_id . " AND search_report.LEAD_ID=" . $lead_id;
			$result = $mysqli->query($query) or die($mysqli->error);
			if ($mysqli->affected_rows > 0) {
				while ($row = mysqli_fetch_array($result)) {
					$provider_company_name = $row['COMPANY_NAME'];
					$submitted = date("m/d/Y", strtotime($row['INQUIRY_TIMESTAMP']));
					$message = "
						<div style='font: normal small Helvetica Neue,Helvetica,Arial,sans-serif; color:#333; width:600px;'>
						<table cellspacing='5' width='100%' style='font: normal small Verdana, Arial, Helvetica, sans-serif;'>
						<tr>
						<td align='left'><img src='http://www.crmds.net/images/simplehousesolutions.png' /></td>
						<td align='right' valign='bottom' style='font-size:16px;font-variant:small-caps;'>Client Update</td>
						</tr>
						<tr>
						<td colspan='2' style='font: normal small Helvetica Neue,Helvetica,Arial,sans-serif; color:#333; width:500px;'>
						We are happy to provide you with the following client's updated information:<br /><br />
						Date Lead Was Sent - " . $submitted . "<br />
						Company Name - " . $company_name . "<br />
						Client Name - " . $client_name . "<br />
						Office Phone - " . $office . "<br />
						Cell Phone - " . $cell . "<br />"
						. $info . ""
						. $full . "<br />
						Office: 972-876-3131<br />
						</td>
						</tr>
						</table>
						</div>";
					echo $message;
				}
			} else {
				echo "Error: Unable to find property";
			}
		}
	} else {
		echo "Error: Lead " . $lead_id . " does not exist";
	}

} elseif ($action == 'generateUpdateAll') {

	$full = $session->userinfo['fullname'];
	$email = $session->userinfo['email'];
	$result = $mysqli->query("SELECT * FROM leads WHERE lead_id=" . $lead_id) or die($mysqli->error);
	if ($mysqli->affected_rows > 0) {
		while ($row = mysqli_fetch_array($result)) {
			$company_name = stripslashes($row['COMPANY_NAME']);
			$first = stripslashes($row['FIRST_NAME']);
			$last = stripslashes($row['LAST_NAME']);
			$client_name = $first . " " . $last;
			$home = $row['HOME_PHONE'];
			$cell = $row['CELL_PHONE'];
			$client_email = $row['CLIENT_EMAIL'];
			$info = nl2br(stripslashes($row['PROVIDER_INFO']));
			$message = "
				<div style='font: normal small Helvetica Neue,Helvetica,Arial,sans-serif; color:#333; width:640px;'>
				<table cellspacing='5' width='100%' style='font: normal small Verdana, Arial, Helvetica, sans-serif;'>
				<tr>
				<td align='left'><img src='http://www.crmds.net/images/simplehousesolutions.png' /></td>
				<td align='right' valign='bottom' style='font-size:16px;font-variant:small-caps;'>Client Update</td>
				</tr>
				<tr>
				<td colspan='2' style='font: normal small Helvetica Neue,Helvetica,Arial,sans-serif; color:#333; width:640px;'>
				We are happy to provide you with the following client's updated information:<br /><br />
				Client Name - " . $client_name . "<br />
				Home Phone - " . $home . "<br />
				Cell Phone - " . $cell . "<br />
				Email - " . $client_email . "<br />"
				. $info . " "
				. $full . "<br />
				Office: 972-876-3131<br />
				</td>
				</tr>
				</table>
				</div>";
			echo $message;
		}
	} else {
		echo "Error: Lead " . $lead_id . " does not exist";
	}

} elseif ($action == 'sendUpdate') {

	$message = stripslashes($_POST['update_message']);
	$full = $session->userinfo['fullname'];
	$email = $session->userinfo['email'];
	$from = $full . " <" . $email . ">";
	if ($property_id != null && $property_id != "") {
		$query = "SELECT * FROM search_report JOIN properties ON search_report.PROPERTY_ID=properties.PROPERTY_ID" .
			" JOIN leads ON search_report.lead_id=leads.lead_id WHERE search_report.LEAD_ID=" . $lead_id .
			" AND search_report.PROPERTY_ID=" . $property_id;
	} else {
		$query = "SELECT * FROM search_report JOIN properties ON search_report.PROPERTY_ID=properties.PROPERTY_ID" .
			" JOIN leads ON search_report.lead_id=leads.lead_id WHERE search_report.LEAD_ID=" . $lead_id . " AND search_report.REJECTED!=1";
	}
	$result = $mysqli->query($query) or die($mysqli->error);
	if ($mysqli->affected_rows > 0) {
		while ($row = mysqli_fetch_array($result)) {
			if ($row['PROVIDER_ID'] != 1) {
				$to = $row['CONTACT_EMAIL'];
				if ($from != null && $from != "") {
					if ($to != null && $to != "") {
						$headers = "MIME-Version: 1.0\r\n";
						$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
						$headers .= "From: " . $from . "\r\n";
						$subject = "SimpleHouseSolutions - Client Update";
						mail($to, $subject, $message, $headers);
						header("Location: searchReport.php?lead_id=$lead_id");
					} else {
						echo "Error: Provider's email address cannot be blank/null";
					}
				} else {
					echo "Error: Unable to determine from address";
				}
			}
		}
	} else {
		echo "Error: Lead " . $lead_id . " does not exist";
	}

} elseif ($action == 'sendLead') {

	$sent = array();
	$full = $session->userinfo['fullname'];
	$email = $session->userinfo['email'];
	$from = $full . " <" . $email . ">";
	$subject = "SimpleHouseSolutions - Referral";

	$result = $mysqli->query("SELECT * FROM leads WHERE lead_id=" . $lead_id) or die($mysqli->error);
	if ($mysqli->affected_rows > 0) {
		while ($row = mysqli_fetch_array($result)) {
			$title = stripslashes($row['TITLE']);
			$first = stripslashes($row['FIRST_NAME']);
			$last = stripslashes($row['LAST_NAME']);
			$position = stripslashes($row['POSITION']);
			$address1 = stripslashes($row['ADDRESS_1']);
			$address2 = stripslashes($row['ADDRESS_2']);
			$city = stripslashes($row['CITY']);
			$state = $row['STATE'];
			$zip = $row['ZIP'];
			$home = $row['HOME_PHONE'];
			$cell = $row['CELL_PHONE'];
			$client_email = $row['CLIENT_EMAIL'];
			if ($client_email != "") {
				$client_email_link = "<a href='mailto:" . $client_email . "'>" . $client_email . "</a>";
			}
			if ($property_id == null) {
				$result = $mysqli->query("SELECT search_report.SEARCH_REPORT_ID, providers.COMPANY_NAME, properties.CENTER_NAME, properties.PROVIDER_CENTER_ID, properties.CONTACT_EMAIL FROM search_report " .
					"JOIN properties ON search_report.PROPERTY_ID=properties.PROPERTY_ID " .
					"JOIN providers ON providers.PROVIDER_ID=properties.PROVIDER_ID " .
					"WHERE search_report.INQUIRYSENT=0 AND search_report.LEAD_ID=" . $lead_id)
					or die($mysqli->error);
			} else {
				$result = $mysqli->query("SELECT search_report.SEARCH_REPORT_ID, providers.COMPANY_NAME, properties.CENTER_NAME, properties.PROVIDER_CENTER_ID, properties.CONTACT_EMAIL FROM search_report " .
					"JOIN properties ON search_report.PROPERTY_ID=properties.PROPERTY_ID " .
					"JOIN providers ON providers.PROVIDER_ID=properties.PROVIDER_ID " .
					"WHERE properties.PROPERTY_ID=" . $property_id . " AND search_report.LEAD_ID=" . $lead_id)
					or die($mysqli->error);
			}
			if ($mysqli->affected_rows > 0) {

				while ($row = mysqli_fetch_array($result)) {

					$search_report_id = $row['SEARCH_REPORT_ID'];
					$provider_company_name = $row['COMPANY_NAME'];
					$to = strtolower($row['CONTACT_EMAIL']);
					$center_name = $row['CENTER_NAME'];
					$center_id = $row['PROVIDER_CENTER_ID'];

					if (!in_array($to, $sent)) {
						$message = "
							<div style='font: normal small Helvetica Neue,Helvetica,Arial,sans-serif; color:#333; width:600px;'>
							<table cellspacing='5' width='100%' style='font: normal small Verdana, Arial, Helvetica, sans-serif;'>
							<tr>
							<td align='left'><img src='http://www.crmds.net/images/simplehousesolutions.png' /></td>
							<td align='right' valign='bottom' style='font-size:16px;font-variant:small-caps;'>Referral</td>
							</tr>
							<tr>
							<td colspan='2' style='font: normal small Helvetica Neue,Helvetica,Arial,sans-serif; color:#333; width:500px;'>
							The following person has shown an interest in your property. Please contact me for further info.
							<br />
							Title: " . $title . "<br />
							Last Name: " . $first . "<br />
							First Name: " . $last . "<br />
							Address1: " . $address1 . "<br />
							Address 2: " . $address2 . "<br />
							City: " . $city . "<br />
							State: " . $state . "<br />
							Zip: " . $zip . "<br />
							Home: " . $home . "<br />
							Cell: " . $cell . "<br />
							Email: " . $client_email . "<br />
							<br /><br />"
							. $full . "<br />
							Office: 972-876-3131<br />
							</td>
							</tr>
							</table>
							</div>";

						if ($from != null && $from != "") {
							if ($to != null && $to != "") {
								$headers = "MIME-Version: 1.0\r\n";
								$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
								$headers .= "From: " . $from . "\r\n";
								mail($to, $subject, $message, $headers);
								$mysqli->query("UPDATE search_report SET INQUIRYSENT=1, INQUIRY_TIMESTAMP=SYSDATE() WHERE SEARCH_REPORT_ID=" . $search_report_id) or die($mysqli->error);
							} else {
								echo "Error: Provider lead email address cannot be blank/null";
							}
						} else {
							echo "Error: From address cannot be blank/null";
						}
					} else {
						$mysqli->query("UPDATE search_report SET INQUIRYSENT=1, INQUIRY_TIMESTAMP=SYSDATE() WHERE SEARCH_REPORT_ID=" . $search_report_id) or die($mysqli->error);
					}

					array_push($sent, $to);
				}
				//print_r($sent);
				echo "Done processing all inquiry emails";
			} else {
				echo "All properties have already been sent inquiries.  No further emails will be sent.";
			}
		}
	} else {
		echo "Error: Lead " . $lead_id . " does not exist";
	}

} else {
	echo "Error: Action not specified";
}
?>