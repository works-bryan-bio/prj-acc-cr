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
		$stmt = $mysqli->prepare("INSERT INTO affiliates (COMPANY_NAME, TITLE, CONTACT_NAME, CONTACT_EMAIL, OFFICE_PHONE, CELL_PHONE,
															OTHER_PHONE, FAX, WEBSITE, LOGO, ADDRESS_1, ADDRESS_2, CITY, STATE, ZIP,
															COUNTRY, NOTES, TAX_ID, COMMISSION_ES, COMMISSION_CONV, USERNAME, DATE_ADDED)
															VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
															") or die($mysqli->error);
		$stmt->bind_param("ssssssssssssssssssssss",
			$mysqli->real_escape_string($_POST["company_name"]),
			$mysqli->real_escape_string($_POST["title"]),
			$mysqli->real_escape_string($_POST["contact_name"]),
			$mysqli->real_escape_string($_POST["contact_email"]),
			$mysqli->real_escape_string($_POST["office_phone"]),
			$mysqli->real_escape_string($_POST["cell_phone"]),
			$mysqli->real_escape_string($_POST["other_phone"]),
			$mysqli->real_escape_string($_POST["fax"]),
			$mysqli->real_escape_string($_POST["website"]),
			$mysqli->real_escape_string($_POST["logo"]),
			$mysqli->real_escape_string($_POST["address_1"]),
			$mysqli->real_escape_string($_POST["address_2"]),
			$mysqli->real_escape_string($_POST["city"]),
			$mysqli->real_escape_string($_POST["state"]),
			$mysqli->real_escape_string($_POST["zip"]),
			$mysqli->real_escape_string($_POST["country"]),
			stripslashes(str_replace('\r\n', ' ', $mysqli->real_escape_string($_POST["notes"]))),
			$mysqli->real_escape_string($_POST["tax_id"]),
			$mysqli->real_escape_string($_POST["commission_es"]),
			$mysqli->real_escape_string($_POST["commission_conv"]),
			$mysqli->real_escape_string($_POST["username"]),
			$mysqli->real_escape_string($_POST["date_added"])
		) or die($mysqli->error);

		/* Execute the statement */
		$stmt->execute() or die("Error: Could not execute statement");

		/* close statement */
		$stmt->close() or die("Error: Could not close statement");

		// once saved, redirect back to the view page
		header("Location: listAffiliates.php");
	}
	else	{
		echo "Error: Company name is required";
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
<table class="input">
<tr>
<th>Add Lead Source</th>
<th style="text-align: center;">
<input class="button" type="submit" name="submit" value="Add Lead Source">
<input class="button" type="button" onClick="javascript:history.back()" value="Cancel">
</th>
<th>&nbsp;</th>
</tr>

<tr><td align="right">Company Name:</td><td align="left" colspan="2">
<input name="company_name" size="60" value="" /></td></tr>

<tr><td align="right">Title:</td><td align="left" colspan="2">
<input name="title" size="10" value="" /></td></tr>

<tr><td align="right">Contact Name:</td><td align="left" colspan="2">
<input name="contact_name" size="60" value="" /></td></tr>

<tr><td align="right">Contact Email:</td><td align="left" colspan="2">
<input name="contact_email" size="60" value="" /></td></tr>

<tr><td align="right">Office Phone:</td><td align="left" colspan="2">
<input name="office_phone" size="30" value="" onblur="formatPhoneNumber(this);" /></td></tr>

<tr><td align="right">Cell Phone:</td><td align="left" colspan="2">
<input name="cell_phone" size="30" value="" onblur="formatPhoneNumber(this);" /></td></tr>

<tr><td align="right">Customer Phone:</td><td align="left" colspan="2">
<input name="other_phone" size="30" value="" onblur="formatPhoneNumber(this);" /></td></tr>

<tr><td align="right">Fax:</td><td align="left" colspan="2">
<input name="fax" size="30" value="" onblur="formatPhoneNumber(this);" /></td></tr>

<tr><td align="right">Web Site:</td><td align="left" colspan="2">
<input name="website" size="60" value="" /></td></tr>

<tr><td align="right">Logo URL:</td><td align="left" colspan="2">
<input name="logo" size="120" value="" /></td></tr>

<tr><td align="right">Address1:</td><td align="left" colspan="2">
<input name="address_1" size="60" value="" /></td></tr>

<tr><td align="right">Address2:</td><td align="left" colspan="2">
<input name="address_2" size="60" value="" /></td></tr>

<tr><td align="right">City:</td><td align="left" colspan="2">
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

<tr><td align="right">Zip Code:</td><td align="left" colspan="2">
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

<tr><td align="right">Tax ID:</td><td align="left" colspan="2">
<input name="tax_id" size="30" value="" /></td></tr>

<tr><td align="right">Commission ES (%):</td><td align="left" colspan="2">
<input name="commission_es" size="10" value="" /></td></tr>

<tr><td align="right">Commission Conv (%):</td><td align="left" colspan="2">
<input name="commission_conv" size="10" value="" /></td></tr>

<tr><td align="right">Username:</td><td align="left" colspan="2">
<input name="username" size="15" value="" /></td></tr>

<tr><td align="right" valign="top">Notes:</td><td align="left" colspan="2">
<textarea wrap="virtual" name="notes" cols="45" rows="8"></textarea></td></tr>

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