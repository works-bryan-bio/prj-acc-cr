<?php
require_once("include/checklogin.php");

// connect to the database
require_once("include/db_connect.php");

// check if the form has been submitted. If it has, start to process the form and save it to the database
if (isset($_POST["submit"]))
{
	// get form data, making sure it is valid
	$company_name = $mysqli->real_escape_string($_POST["company_name"]);

	// check to make sure that all required fields are available
	if ($company_name != '') {

		// save the data to the database
		$stmt = $mysqli->prepare("INSERT INTO providers (COMPANY_NAME, TITLE, CONTACT_NAME, CONTACT_EMAIL, LEAD_EMAIL, INVOICE_EMAIL,
															OFFICE_PHONE, CELL_PHONE, OTHER_PHONE, FAX, WEBSITE, ADDRESS_1, ADDRESS_2, CITY, STATE, ZIP,
															COUNTRY, AGREED_FEE, FLAT_RATE, TAX_ID, AGREED_FROM, AGREED_TO, BILLING_FREQ, PAY_ADVANCE,
															PAY_ARREARS, PLACEMENT_DETAILS, VALID_FROM, VALID_TO, CREDIT_SCORE, USERNAME, PROVIDER_NOTES, DATE_ADDED)
															VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?,
																			?, ?, ?, ?, ?, ?, ?, ?, ?, ?,
																			?, ?, ?, ?, ?, ?, ?, ?, ?, ?,
																			?, ?)
															") or die($mysqli->error);
		$stmt->bind_param("sssssssssssssssssddssssiisssssss",
			$mysqli->real_escape_string($_POST["company_name"]),
			$mysqli->real_escape_string($_POST["title"]),
			$mysqli->real_escape_string($_POST["contact_name"]),
			$mysqli->real_escape_string($_POST["contact_email"]),
			$mysqli->real_escape_string($_POST["lead_email"]),
			$mysqli->real_escape_string($_POST["invoice_email"]),
			$mysqli->real_escape_string($_POST["office_phone"]),
			$mysqli->real_escape_string($_POST["cell_phone"]),
			$mysqli->real_escape_string($_POST["other_phone"]),
			$mysqli->real_escape_string($_POST["fax"]),
			$mysqli->real_escape_string($_POST["website"]),
			$mysqli->real_escape_string($_POST["address_1"]),
			$mysqli->real_escape_string($_POST["address_2"]),
			$mysqli->real_escape_string($_POST["city"]),
			$mysqli->real_escape_string($_POST["state"]),
			$mysqli->real_escape_string($_POST["zip"]),
			$mysqli->real_escape_string($_POST["country"]),
			$mysqli->real_escape_string($_POST["agreed_fee"]),
			$mysqli->real_escape_string($_POST["flat_rate"]),
			$mysqli->real_escape_string($_POST["tax_id"]),
			$mysqli->real_escape_string($_POST["agreed_from"]),
			$mysqli->real_escape_string($_POST["agreed_to"]),
			$mysqli->real_escape_string($_POST["billing_freq"]),
			$mysqli->real_escape_string($_POST["pay_advance"]),
			$mysqli->real_escape_string($_POST["pay_arrears"]),
			$mysqli->real_escape_string($_POST["placement_details"]),
			$mysqli->real_escape_string($_POST["valid_from"]),
			$mysqli->real_escape_string($_POST["valid_to"]),
			$mysqli->real_escape_string($_POST["credit_score"]),
			$mysqli->real_escape_string($_POST["username"]),
			stripslashes(str_replace('\r\n', ' ', $mysqli->real_escape_string($_POST["provider_notes"]))),
			$mysqli->real_escape_string($_POST["date_added"])
		) or die($mysqli->error);

		/* Execute the statement */
		$stmt->execute() or die("Error: Could not execute statement");

		/* close statement */
		$stmt->close() or die("Error: Could not close statement");

		// once saved, redirect back to the view page
		header("Location: listProviders.php");
	}
	else	{
		echo "Error: Center name is required";
	}
}
else {
// if the form hasn't been submitted, display the form
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>SimpleHouseSolutions.com - Dashboard</title>
<link rel="shortcut icon" href="/favicon.ico" />
<link rel="stylesheet" type="text/css" href="css/dashboard.css"/>
<link rel="stylesheet" type="text/css" href="css/dashboard_menu.css"/>
<link rel="stylesheet" type="text/css" href="js/tigra_calendar/calendar.css">
<script type="text/javascript" src="js/tigra_calendar/calendar_db.js"></script>
<script type="text/javascript" src="js/site.js"></script>
</head>
<body>
<div id="header"><?php require "header.inc.php"; ?></div>
<div id="menu"><?php require "menu.inc.php"; ?></div>
<div id="content">
<!-- Begin Content-->

<p />
<div align="center">
<form name="form1" method="post" action="<?=$PHP_SELF?>" enctype="multipart/form-data">
<input type="hidden" name="date_added" value="<?=date("Y-m-d")?>">
<input class="button" type="submit" name="submit" value="Add Provider">
<input class="button" type="button" onClick="javascript:history.back()" value="Cancel">
<table class="input">
<tr>
<th>Add Provider</th>
<th>&nbsp;</th>
</tr>

<tr>
<td align="left" valign="top" width="50%">

<table>
<tr><td align="right">Company Name:</td><td align="left">
<input name="company_name" size="60" value="" /></td></tr>

<tr><td align="right">Title:</td><td align="left">
<input name="title" size="10" value="" /></td></tr>

<tr><td align="right">Contact Name:</td><td align="left">
<input name="contact_name" size="60" value="" /></td></tr>

<tr><td align="right">Contact Email:</td><td align="left">
<input name="contact_email" size="60" value="" /></td></tr>

<tr><td align="right">Lead Email:</td><td align="left">
<input name="lead_email" size="60" value="" /></td></tr>

<tr><td align="right">Invoice Email:</td><td align="left">
<input name="invoice_email" size="60" value="" /></td></tr>

<tr><td align="right">Office Phone:</td><td align="left">
<input name="office_phone" size="30" value="" onblur="formatPhoneNumber(this);" /></td></tr>

<tr><td align="right">Cell Phone:</td><td align="left">
<input name="cell_phone" size="30" value="" onblur="formatPhoneNumber(this);" /></td></tr>

<tr><td align="right">Other Phone:</td><td align="left">
<input name="other_phone" size="30" value="" onblur="formatPhoneNumber(this);" /></td></tr>

<tr><td align="right">Fax:</td><td align="left">
<input name="fax" size="30" value="" onblur="formatPhoneNumber(this);" /></td></tr>

<tr><td align="right">Web Site:</td><td align="left">
<input name="website" size="60" value="" /></td></tr>

<tr><td align="right">Address1:</td><td align="left">
<input name="address_1" size="60" value="" /></td></tr>

<tr><td align="right">Address2:</td><td align="left">
<input name="address_2" size="60" value="" /></td></tr>

<tr><td align="right">City:</td><td align="left">
<input name="city" size="30" value="" /></td></tr>

<tr><td align="right">State:</td><td align="left">
<select name="state">
<option></option>
<?php
		$result = $mysqli->query("SELECT * FROM states")
			or die(mysql_error());
		while($row = mysqli_fetch_array($result)){
  		foreach($row AS $key => $value) {
				$row[$key] = stripslashes($value);
			}
?>
<option value="<?=$row['ABBREV']?>"><?=$row['ABBREV']?></option>
<?php
		}
?>
</select>
</td></tr>

<tr><td align="right">Zip Code:</td><td align="left">
<input name="zip" size="10" value="" /></td></tr>

<tr><td align="right">Country:</td><td align="left">
<select name="country">
<option value="United States" <?if($prop['COUNTRY']=="United States") echo "selected=\"selected\""?>>United States</option>
<?php
		$result = $mysqli->query("SELECT * FROM countries")
			or die(mysql_error());
		while($row = mysqli_fetch_array($result)){
  		foreach($row AS $key => $value) {
				$row[$key] = stripslashes($value);
			}
?>
<option value="<?=$row['NAME']?>"><?=$row['NAME']?></option>
<?php
		}
?>
</select>
</td></tr>
</table>

</td>
<td align="left" valign="top" width="50%">

<table>
<tr><td align="right">Agreed Fee (%):</td><td align="left">
<input name="agreed_fee" size="15" value="" /></td></tr>

<tr><td align="right">Flat Rate ($):</td><td align="left">
<input name="flat_rate" size="15" value="" /></td></tr>

<tr><td align="right">Tax ID:</td><td align="left">
<input name="tax_id" size="30" value="" /></td></tr>

<tr><td align="right">Agreed From:</td>
<td align="left" valign="top">
<input name="agreed_from" id="agreed_from" size="15" value="" />
<script type="text/javascript">
	var t_cal = new tcal ({
		'controlname': 'agreed_from'
	});
</script>
</td></tr>

<tr><td align="right">Agreed To:</td>
<td align="left" valign="top">
<input name="agreed_to" id="agreed_to" size="15" value="" />
<script type="text/javascript">
	var t_cal = new tcal ({
		'controlname': 'agreed_to'
	});
</script>
</td></tr>

<tr><td align="right">Billing Frequency:</td><td align="left">
<select name="billing_freq">
<option value="Upfront">Upfront</option>
<option value="Quarterly">Quarterly</option>
<option value="Bi-Annually">Bi-Annually</option>
</select></td></tr>

<tr><td align="right" valign="top">Payment Terms:</td><td align="left">
<input type="checkbox" name="pay_advance" value="1" />Pay In Advance<br />
<input type="checkbox" name="pay_arrears" value="1" />Pay In Arrears<br />
</td></tr>

<tr><td align="right" valign="top">Placement Details:</td><td align="left">
<textarea wrap="virtual" name="placement_details" cols="45" rows="8"></textarea></td></tr>

<tr><td align="right">Valid From:</td>
<td align="left" valign="top">
<input name="valid_from" id="valid_from" size="15" value="" />
<script type="text/javascript">
	var t_cal = new tcal ({
		'controlname': 'valid_from'
	});
</script>
</td></tr>

<tr><td align="right">Valid To:</td>
<td align="left" valign="top">
<input name="valid_to" id="valid_to" size="15" value="" />
<script type="text/javascript">
	var t_cal = new tcal ({
		'controlname': 'valid_to'
	});
</script>
</td></tr>

<tr><td align="right">Credit Score:</td><td align="left">
<input name="credit_score" size="10" value="" /></td></tr>

<tr>
<td align="right">User Source:</td><td align="left">
<select name="username">
<option value="Not Assigned">Not Assigned</option>
<?php
		$result = $mysqli->query("SELECT * FROM users WHERE fullname!='' AND userlevel > 0 ORDER BY fullname ASC")
			or die(mysql_error());
		while($row = mysqli_fetch_array($result)){
  		foreach($row AS $key => $value) {
				$row[$key] = stripslashes($value);
			}
?>
<option value="<?=$row['username']?>"><?=$row['fullname']?></option>
<?php
		}
?>
</select>
</td>
</tr>

<tr><td align="right" valign="top">Provider Notes:</td><td align="left">
<textarea wrap="virtual" name="provider_notes" cols="45" rows="8"></textarea></td></tr>
</table>

</td></tr>
</table>
</form>
</div>

<!-- End Content -->
</div>
</body>
</html>
<?php
}
?>