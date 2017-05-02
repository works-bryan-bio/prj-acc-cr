<?php
require_once("dashboard/include/db_connect.php");

//parse URL for variables
$action = $_GET['action'];
$lead_id = $_GET['lead_id'];
$property_id = $_GET['property_id'];

if ($action=='addToFavorites') {
	$stmt = $mysqli->prepare("UPDATE search_report SET FAVORITE=1 WHERE LEAD_ID=? AND PROPERTY_ID=?") or die($mysqli->error);
	$stmt->bind_param("ii",
	$mysqli->real_escape_string($lead_id),
	$mysqli->real_escape_string($property_id)
	) or die($mysqli->error);

	/* Execute the statement */
	$stmt->execute() or die("Error: Could not execute statement");

	/* close statement */
	$stmt->close() or die("Error: Could not close statement");

	$result = $mysqli->query("SELECT * FROM users JOIN leads ON users.username=leads.USERNAME WHERE leads.lead_id=" . $mysqli->real_escape_string($lead_id))
		or die($mysqli->error);
	if ($mysqli->affected_rows>0) {
		while($row = mysqli_fetch_array($result)){
			$to = $row['email'];
			$first_name = stripslashes($row['FIRST_NAME']);
			$last_name = stripslashes($row['LAST_NAME']);
			$client_email = $row['CLIENT_EMAIL'];
			$home_phone = $row['HOME_PHONE'];
			$cell_phone = $row['CELL_PHONE'];
			$client_name = trim($first_name . " " . $last_name);
		}
		$subject = "SimpleHouseSolutions - Favorite List Update [" . $client_name . "]";
		$message =
		"<div style='font: normal small Verdana, Arial, Helvetica, sans-serif; color:#000; width:500px;'>
			<table width='100%'>
			<tr>
				<td align='left' valign='bottom' style='font-size:16px;font-variant:small-caps;'>Favorite List Update</td>
				<td align='right'><img src='http://www.crmds.net/images/simplehousesolutions.png' /></td>
			</tr>
			<tr><td colspan='2'><hr></td></tr>
			</table>
			The client below has updated their favorite properties list</strong>:<br /><br />" .
			$client_name . "<br />" .
			$client_email . "<br />" .
			$home_phone . "<br />" .
			$cell_phone . "<br /><br />" .
			"<a href='http://www.crmds.net/dashboard/searchReport.php?lead_id=" . $lead_id . "'>http://www.crmds.net/dashboard/searchReport.php?lead_id=" . $lead_id . "</a>" .
		"</div>";

		if($to!=null && $to!="") {
			$headers  = "MIME-Version: 1.0" . "\r\n";
			$headers .= "Content-type: text/html; charset=iso-8859-1;" . "\r\n";
			mail($to, $subject, $message, $headers);
		}
	}

	echo "Property added to Favorites";

} elseif ($action=='removeFavorite') {

	$stmt = $mysqli->prepare("UPDATE search_report SET FAVORITE=0 WHERE LEAD_ID=? AND PROPERTY_ID=?") or die($mysqli->error);
	$stmt->bind_param("ii",
	$mysqli->real_escape_string($lead_id),
	$mysqli->real_escape_string($property_id)
	) or die($mysqli->error);

	/* Execute the statement */
	$stmt->execute() or die("Error: Could not execute statement");

	/* close statement */
	$stmt->close() or die("Error: Could not close statement");

	$result = $mysqli->query("SELECT * FROM users JOIN leads ON users.username=leads.USERNAME WHERE leads.lead_id=" . $mysqli->real_escape_string($lead_id))
		or die($mysqli->error);
	if ($mysqli->affected_rows>0) {
		while($row = mysqli_fetch_array($result)){
			$to = $row['email'];
			$first_name = stripslashes($row['FIRST_NAME']);
			$last_name = stripslashes($row['LAST_NAME']);
			$client_email = $row['CLIENT_EMAIL'];
			$home_phone = $row['HOME_PHONE'];
			$cell_phone = $row['CELL_PHONE'];
			$client_name = trim($first_name . " " . $last_name);
		}
		$subject = "SimpleHouseSolutions - Favorite List Update [" . $client_name . "]";
		$message =
		"<div style='font: normal small Verdana, Arial, Helvetica, sans-serif; color:#000; width:500px;'>
			<table width='100%'>
			<tr>
				<td align='left' valign='bottom' style='font-size:16px;font-variant:small-caps;'>Favorite List Update</td>
				<td align='right'><img src='http://www.crmds.net/images/simplehousesolutions.png' /></td>
			</tr>
			<tr><td colspan='2'><hr></td></tr>
			</table>
			The client below has updated their favorite properties list</strong>:<br /><br />" .
			$client_name . "<br />" .
			$client_email . "<br />" .
			$home_phone . "<br />" .
			$cell_phone . "<br /><br />" .
			"<a href='http://www.crmds.net/dashboard/searchReport.php?lead_id=" . $lead_id . "'>http://www.crmds.net/dashboard/searchReport.php?lead_id=" . $lead_id . "</a>" .
		"</div>";

		if($to!=null && $to!="") {
			$headers  = "MIME-Version: 1.0" . "\r\n";
			$headers .= "Content-type: text/html; charset=iso-8859-1;" . "\r\n";
			mail($to, $subject, $message, $headers);
		}
	}

	echo "Property removed from Favorites";

} elseif ($action=='bookTour') {

	if(isset($_POST['tour_date'])) {
		$tour_date = $_POST['tour_date'];
	} elseif(isset($_POST['tour_date_bottom'])) {
		$tour_date = $_POST['tour_date_bottom'];
	}
	$tour_time = $_POST['tour_time'];
	$tour_zone = $_POST['tour_zone'];
	if ($tour_date!=null && $tour_time!=null) {
		$result = $mysqli->query("SELECT * FROM users JOIN leads ON users.username=leads.USERNAME WHERE leads.lead_id=" . $mysqli->real_escape_string($lead_id))
			or die($mysqli->error);
		if ($mysqli->affected_rows>0) {
			while($row = mysqli_fetch_array($result)){
				$email = $row['email'];
				$full = $row['fullname'];
				$first_name = stripslashes($row['FIRST_NAME']);
				$last_name = stripslashes($row['LAST_NAME']);
				$client_email = $row['CLIENT_EMAIL'];
				$home_phone = $row['HOME_PHONE'];
				$cell_phone = $row['CELL_PHONE'];
				$client_name = trim($first_name . " " . $last_name);
			}
			$result = $mysqli->query("SELECT CENTER_NAME,CONTACT_EMAIL FROM properties WHERE property_id=" . $mysqli->real_escape_string($property_id))
				or die($mysqli->error);
			while($row = mysqli_fetch_array($result)){
				$property_name = stripslashes($row['CENTER_NAME']);
				$to = $row['CONTACT_EMAIL'];
			}
			$subject = "SimpleHouseSolutions Tour Request - " . $client_name;
			$message =
			"<div style='font: normal small Verdana, Arial, Helvetica, sans-serif; color:#000; width:500px;'>
				<table width='100%'>
				<tr>
					<td align='left' valign='bottom' style='font-size:16px;font-variant:small-caps;'>Tour Request</td>
					<td align='right'><img src='http://www.crmds.net/images/simplehousesolutions.png' /></td>
				</tr>
				<tr><td colspan='2'><hr></td></tr>
				</table>
				The client below has requested to tour  <strong>" . $property_name . "</strong>:<br /><br />
				Date/Time: " . $tour_date . " " . $tour_time . " " . $tour_zone . "<br /><br />" .
				$client_name . "<br />" .
				$client_email . "<br />" .
				$home_phone . "<br />" .
				$cell_phone . "<br /><br />" .
				"<br />
				Please confirm via email or phone that the date & time requested by the client is acceptable.<br /><br />" .
				$full . "<br />" .
				$email . "<br />" .
				"Office: 972-876-3131<br />" .
			"</div>";

			if($to!=null && $to!="") {
				$headers  = "MIME-Version: 1.0" . "\r\n";
				$headers .= "Content-type: text/html; charset=iso-8859-1;" . "\r\n";
				$headers .= "From: " . $full . " <" . $email . ">\r\n";
				$headers .= "Cc: " . $email . "\r\n";
				mail($to, $subject, $message, $headers);

				echo "Your request to tour this property has been received.  You will receive a tour confirmation email shortly.";

			} else {
				echo "Error: Property email address cannot be blank/null";
			}
		} else {
			echo "Error: Unable to send request.  Please call 972-876-3131 for assistance.";
		}
	} else {
		echo "Error: Tour Date/Time must be filled in.";
	}

} elseif ($action=='requestPrice') {

	$result = $mysqli->query("SELECT * FROM users JOIN leads ON users.username=leads.USERNAME WHERE leads.lead_id=" . $mysqli->real_escape_string($lead_id))
		or die($mysqli->error);
	if ($mysqli->affected_rows>0) {
		while($row = mysqli_fetch_array($result)){
			$email = $row['email'];
			$full = $row['fullname'];
			$first_name = stripslashes($row['FIRST_NAME']);
			$last_name = stripslashes($row['LAST_NAME']);
			$client_email = $row['CLIENT_EMAIL'];
			$home_phone = $row['HOME_PHONE'];
			$cell_phone = $row['CELL_PHONE'];
			$client_name = trim($first_name . " " . $last_name);
		}
		$result = $mysqli->query("SELECT CENTER_NAME,CONTACT_EMAIL FROM properties WHERE property_id=" . $mysqli->real_escape_string($property_id))
			or die($mysqli->error);
		while($row = mysqli_fetch_array($result)){
			$property_name = stripslashes($row['CENTER_NAME']);
			$to = $row['CONTACT_EMAIL'];
		}
		$subject = "SimpleHouseSolutions Price Request - " . $client_name;
		$message =
		"<div style='font: normal small Verdana, Arial, Helvetica, sans-serif; color:#000; width:500px;'>
			<table width='100%'>
			<tr>
				<td align='left' valign='bottom' style='font-size:16px;font-variant:small-caps;'>Price Request</td>
				<td align='right'><img src='http://www.crmds.net/images/simplehousesolutions.png' /></td>
			</tr>
			<tr><td colspan='2'><hr></td></tr>
			</table>
			The client below has requested a price for <strong>" . $property_name . "</strong>:<br /><br />" .
			$client_name . "<br />" .
			$client_email . "<br />" .
			$home_phone . "<br />" .
			$cell_phone . "<br /><br />" .
			"<br />
			If you need additional details about this client's specific needs please contact us via email or phone.
			Please CC us in any correspondence so we are aware of the options you have available.<br /><br />" .
			$full . "<br />" .
			$email . "<br />" .
			"Office: 972-876-3131<br />" .
		"</div>";

		if($to!=null && $to!="") {
			$headers  = "MIME-Version: 1.0" . "\r\n";
			$headers .= "Content-type: text/html; charset=iso-8859-1;" . "\r\n";
			$headers .= "From: " . $full . " <" . $email . ">\r\n";
			$headers .= "Cc: " . $email . "\r\n";
			mail($to, $subject, $message, $headers);
			echo "Your request for pricing has been received. You will receive an email or call shortly with this information.";
		} else {
			echo "Error: Property email address cannot be blank/null";
		}
	} else {
		echo "Error: Unable to send request.  Please call 972-876-3131 for assistance.";
	}

} else {
	echo "Error: Action not specified";
}

?>