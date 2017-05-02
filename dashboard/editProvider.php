<?php
require_once("include/checklogin.php");

// connect to the database
require_once("include/db_connect.php");

// check if the form has been submitted. If it has, start to process the form and save it to the database
if (isset($_POST["submit"]) || isset($_POST["dsubmit"]))
{
	// get form data, making sure it is valid
	$provider_id = $mysqli->real_escape_string($_POST["provider_id"]);

	// check to make sure that all required fields are available
	if ($provider_id != '') {
			// get the photos from FILES array
		$result = $mysqli->query("SELECT * FROM providers WHERE provider_id=" . $provider_id) or die(mysqli_error());
		$row = mysqli_fetch_array($result);
		try {
			$application_data = null;
			$application_mime = null;
			if (isset($_FILES["application"])) {
				$application = $_FILES["application"];
				$tmpName = $application['tmp_name'];
				$application_data = file_get_contents($tmpName);
				$application_mime = $application['type'];
			} else {
				if($_POST["application_action"]=="keep") {
					$application_data = $row["APPLICATION"];
					$application_mime = $row["APPLICATION_MIME"];
				}
			}
			$terms_data = null;
			$terms_mime = null;
			if (isset($_FILES["terms"])) {
				$terms = $_FILES["terms"];
				$tmpName = $terms['tmp_name'];
				$terms_data = file_get_contents($tmpName);
				$terms_mime = $terms['type'];
			} else {
				if($_POST["terms_action"]=="keep") {
					$terms_data = $row["TERMS"];
					$terms_mime = $row["TERMS_MIME"];
				}
			}
		} catch (Exception $e) {
			die($e->getMessage());
		}

		// save the data to the database
		$stmt = $mysqli->prepare("UPDATE providers SET COMPANY_NAME=?, TITLE=?, CONTACT_NAME=?, CONTACT_EMAIL=?, LEAD_EMAIL=?, INVOICE_EMAIL=?,
															OFFICE_PHONE=?, CELL_PHONE=?, OTHER_PHONE=?, FAX=?, WEBSITE=?, ADDRESS_1=?, ADDRESS_2=?, CITY=?, STATE=?, ZIP=?,
															COUNTRY=?, AGREED_FEE=?, FLAT_RATE=?, TAX_ID=?, AGREED_FROM=?, AGREED_TO=?, BILLING_FREQ=?, PAY_ADVANCE=?,
															PAY_ARREARS=?, PLACEMENT_DETAILS=?, VALID_FROM=?, VALID_TO=?, CREDIT_SCORE=?, USERNAME=?, PROVIDER_NOTES=?, STATUS=?,
															APPLICATION=?, APPLICATION_MIME=?, TERMS=?, TERMS_MIME=?
															WHERE provider_id=$provider_id") or die($mysqli->error);
		$stmt->bind_param("sssssssssssssssssddssssiisssssssbsbs",
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
			stripslashes(str_replace('\r\n', ' ', $mysqli->real_escape_string($_POST["placement_details"]))),
			$mysqli->real_escape_string($_POST["valid_from"]),
			$mysqli->real_escape_string($_POST["valid_to"]),
			$mysqli->real_escape_string($_POST["credit_score"]),
			$mysqli->real_escape_string($_POST["username"]),
			stripslashes(str_replace('\r\n', ' ', $mysqli->real_escape_string($_POST["provider_notes"]))),
			$mysqli->real_escape_string($_POST["status"]),
			$mysqli->real_escape_string(null),
			$mysqli->real_escape_string($application_mime),
			$mysqli->real_escape_string(null),
			$mysqli->real_escape_string($terms_mime)
		) or die($mysqli->error);

		/* Send large document data */
		$stmt->send_long_data(32, $application_data);
		$stmt->send_long_data(34, $terms_data);

		/* Execute the statement */
		$stmt->execute() or die("Error: Could not execute statement");

		/* close statement */
		$stmt->close() or die("Error: Could not close statement");

		if (isset($_POST["dsubmit"])) {
			header("Location: listProviders.php");
		} else {
			header("Location: editProvider.php?provider_id=" . $provider_id);
		}
	}
	else	{
		echo "Error: Provider ID is required";
	}
} else if (isset($_POST["delsubmit"])) {
	
	// get form data, making sure it is valid
	$provider_id = $mysqli->real_escape_string($_POST["provider_id"]);

	// check to make sure that all required fields are available
	if ($provider_id != '') {

		// save the data to the database
		$stmt = $mysqli->prepare("DELETE FROM providers WHERE provider_id=$provider_id") or die($mysqli->error);

		/* Execute the statement */
		$stmt->execute() or die("Error: Could not execute statement");

		/* close statement */
		$stmt->close() or die("Error: Could not close statement");

		header("Location: listProviders.php");
	}
	else	{
		echo "Error: Provider ID is required";
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

<?php
if (!isset($_GET['provider_id'])) {
?>
	<div align="center"><h3>Error: Property ID not provided</h3></div>
<?php
} else {
	$provider_id = $_GET['provider_id'];
	$result = $mysqli->query("SELECT * FROM providers WHERE provider_id=" . $provider_id)
		or die(mysqli_error());
	while($prop = mysqli_fetch_array($result)){
	  foreach($prop AS $key => $value) {
			$prop[$key] = stripslashes($value);
		}
?>
<p />
<div align="center">
<form name="form1" method="post" action="<?=$PHP_SELF?>" enctype="multipart/form-data">
<input type="hidden" name="provider_id" value="<?=$provider_id?>" />
<input type="hidden" name="referer" value="<?=$_SERVER['HTTP_REFERER']?>" />
<input class="button" type="submit" name="submit" value="Save Changes">
<input class="button" type="submit" name="dsubmit" value="Save and Go to Providers" />
<input class="button" type="submit" name="delsubmit" value="Delete" onclick="return confirm_delete();" />
<input class="button" type="button" onClick="javascript:history.back()" value="Cancel">
<table class="input">
<tr>
<th>Edit Provider</th>
<th style="text-align:right;">Last Update: <?php if($prop['LAST_UPDATED']=="") echo "None"; else echo date("Y-m-d h:i A", strtotime($prop['LAST_UPDATED']))?></th>
</tr>
<tr>
<td align="left" valign="top" width="50%">

<table>
<tr><td align="right">Company Name:</td><td align="left">
<input name="company_name" size="60" value="<?=$prop['COMPANY_NAME']?>" /></td></tr>

<tr><td align="right">Title:</td><td align="left">
<input name="title" size="10" value="<?=$prop['TITLE']?>" /></td></tr>

<tr><td align="right">Contact Name:</td><td align="left">
<input name="contact_name" size="60" value="<?=$prop['CONTACT_NAME']?>" /></td></tr>

<tr><td align="right">Contact Email:</td><td align="left">
<input name="contact_email" size="60" value="<?=$prop['CONTACT_EMAIL']?>" /></td></tr>

<tr><td align="right">Lead Email:</td><td align="left">
<input name="lead_email" size="60" value="<?=$prop['LEAD_EMAIL']?>" /></td></tr>

<tr><td align="right">Invoice Email:</td><td align="left">
<input name="invoice_email" size="60" value="<?=$prop['INVOICE_EMAIL']?>" /></td></tr>

<tr><td align="right">Office Phone:</td><td align="left">
<input name="office_phone" size="30" value="<?=$prop['OFFICE_PHONE']?>" onblur="formatPhoneNumber(this);" /></td></tr>

<tr><td align="right">Cell Phone:</td><td align="left">
<input name="cell_phone" size="30" value="<?=$prop['CELL_PHONE']?>" onblur="formatPhoneNumber(this);" /></td></tr>

<tr><td align="right">Other Phone:</td><td align="left">
<input name="other_phone" size="30" value="<?=$prop['OTHER_PHONE']?>" onblur="formatPhoneNumber(this);" /></td></tr>

<tr><td align="right">Fax:</td><td align="left">
<input name="fax" size="30" value="<?=$prop['FAX']?>" onblur="formatPhoneNumber(this);" /></td></tr>

<tr><td align="right">Web Site:</td><td align="left">
<input name="website" size="60" value="<?=$prop['WEBSITE']?>" /></td></tr>

<tr><td align="right">Address1:</td><td align="left">
<input name="address_1" size="60" value="<?=$prop['ADDRESS_1']?>" /></td></tr>

<tr><td align="right">Address2:</td><td align="left">
<input name="address_2" size="60" value="<?=$prop['ADDRESS_2']?>" /></td></tr>

<tr><td align="right">City:</td><td align="left">
<input name="city" size="30" value="<?=$prop['CITY']?>" /></td></tr>

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
<option value="<?=$row['ABBREV']?>" <?if($row['ABBREV']==$prop['STATE']) echo "selected=\"selected\""?>><?=$row['ABBREV']?></option>
<?php
		}
?>
</select>
</td></tr>

<tr><td align="right">Zip Code:</td><td align="left">
<input name="zip" size="10" value="<?=$prop['ZIP']?>" /></td></tr>

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
<option value="<?=$row['NAME']?>" <?if($row['NAME']==$prop['COUNTRY']) echo "selected=\"selected\""?>><?=$row['NAME']?></option>
<?php
		}
?>
</select>
</td></tr>

<tr>
<td align="right" valign="top">Application:</td>
<td align="left">
<?php if ($prop['APPLICATION_MIME']=="" || $prop['APPLICATION_MIME']==null) { echo "No Application Available"; } else { ?>
<a id="application_link" href="/getdocument.php?id=<?=$prop[PROVIDER_ID]?>&doc=application">Download Application</a>
<?php } ?>
<br />
<input name="application_action" value="keep" onclick="document.form1.application.disabled=true;document.form1.application_link.style.display='block';" checked="checked" type="radio"> Keep
<input name="application_action" value="delete" onclick="document.form1.application.disabled=true;document.form1.application_link.style.display='none';" type="radio"> Delete
<input name="application_action" value="change" onclick="document.form1.application.disabled=false;document.form1.application_link.style.display='none';" type="radio"> Change <br />
<input type="file" name="application" size="45" value="" disabled="disabled" />
</td>
</tr>
<tr>
<td align="right" valign="top">Terms:</td>
<td align="left">
<?php if ($prop['TERMS_MIME']=="" || $prop['TERMS_MIME']==null) { echo "No Terms Available"; } else { ?>
<a id="terms_link" href="/getdocument.php?id=<?=$prop[PROVIDER_ID]?>&doc=terms">Download Terms</a>
<?php } ?>
<br />
<input name="terms_action" value="keep" onclick="document.form1.terms.disabled=true;document.form1.terms_link.style.display='block';" checked="checked" type="radio"> Keep
<input name="terms_action" value="delete" onclick="document.form1.terms.disabled=true;document.form1.terms_link.style.display='none';" type="radio"> Delete
<input name="terms_action" value="change" onclick="document.form1.terms.disabled=false;document.form1.terms_link.style.display='none';" type="radio"> Change <br />
<input type="file" name="terms" size="45" value="" disabled="disabled" />
</td>
</tr>
</table>

</td>
<td align="left" valign="top" width="50%">

<table>
<tr>
<td align="right" valign="top">Provider Status:</td>
<td align="left">
<select name="status" class="<?=$prop['STATUS']?>" onchange="this.setAttribute('class',this.value);">
<option value="active" <?if($prop['STATUS']=="active") echo "selected=\"selected\"";?>>Active</option>
<option value="pending" <?if($prop['STATUS']=="pending") echo "selected=\"selected\"";?>>Pending</option>
<option value="delisted" <?if($prop['STATUS']=="delisted") echo "selected=\"selected\"";?>>Delisted</option>
</select>
</td>
</tr>

<tr><td align="right">Agreed Fee (%):</td><td align="left">
<input name="agreed_fee" size="15" value="<?=$prop['AGREED_FEE']?>" /></td></tr>

<tr><td align="right">Flat Rate ($):</td><td align="left">
<input name="flat_rate" size="15" value="<?=$prop['FLAT_RATE']?>" /></td></tr>

<tr><td align="right">Tax ID:</td><td align="left">
<input name="tax_id" size="30" value="<?=$prop['TAX_ID']?>" /></td></tr>

<tr><td align="right">Agreed From:</td>
<td align="left" valign="top">
<input name="agreed_from" id="agreed_from" size="15" value="<?php if ($prop['AGREED_FROM']=="0000-00-00") echo ""; else echo $prop['AGREED_FROM'];?>" />
<script type="text/javascript">
	var t_cal = new tcal ({
		'controlname': 'agreed_from'
	});
</script>
</td></tr>

<tr><td align="right">Agreed To:</td>
<td align="left" valign="top">
<input name="agreed_to" id="agreed_to" size="15" value="<?php if ($prop['AGREED_TO']=="0000-00-00") echo ""; else echo $prop['AGREED_TO'];?>" />
<script type="text/javascript">
	var t_cal = new tcal ({
		'controlname': 'agreed_to'
	});
</script>
</td></tr>

<tr><td align="right">Billing Frequency:</td><td align="left">
<select name="billing_freq">
<option value="Upfront" <?if($prop['BILLING_FREQ']=="Upfront") echo "selected=\"selected\"";?>>Upfront</option>
<option value="Quarterly" <?if($prop['BILLING_FREQ']=="Quarterly") echo "selected=\"selected\"";?>>Quarterly</option>
<option value="Bi-Annually" <?if($prop['BILLING_FREQ']=="Bi-Annually") echo "selected=\"selected\"";?>>Bi-Annually</option>
</select></td></tr>

<tr><td align="right" valign="top">Payment Terms:</td><td align="left">
<input type="checkbox" name="pay_advance" value="1" <?if($prop['PAY_ADVANCE']==1) echo "checked=\"checked\"";?> />Pay In Advance<br />
<input type="checkbox" name="pay_arrears" value="1" <?if($prop['PAY_ARREARS']==1) echo "checked=\"checked\"";?> />Pay In Arrears<br />
</td></tr>

<tr><td align="right" valign="top">Placement Details:</td><td align="left">
<textarea wrap="virtual" name="placement_details" cols="45" rows="8"><?=stripslashes($prop['PLACEMENT_DETAILS'])?></textarea></td></tr>

<tr><td align="right">Valid From:</td>
<td align="left" valign="top">
<input name="valid_from" id="valid_from" size="15" value="<?php if ($prop['VALID_FROM']=="0000-00-00") echo ""; else echo $prop['VALID_FROM'];?>" />
<script type="text/javascript">
	var t_cal = new tcal ({
		'controlname': 'valid_from'
	});
</script>
</td></tr>

<tr><td align="right">Valid To:</td>
<td align="left" valign="top">
<input name="valid_to" id="valid_to" size="15" value="<?php if ($prop['VALID_TO']=="0000-00-00") echo ""; else echo $prop['VALID_TO'];?>" />
<script type="text/javascript">
	var t_cal = new tcal ({
		'controlname': 'valid_to'
	});
</script>
</td></tr>

<tr><td align="right">Credit Score:</td><td align="left">
<input name="credit_score" size="10" value="<?=$prop['CREDIT_SCORE']?>" /></td></tr>

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
<option value="<?=$row['username']?>" <?if($row['username']==$prop['USERNAME']) echo "selected=\"selected\""?>><?=$row['fullname']?></option>
<?php
		}
?>
</select>
</td>
</tr>

<tr><td align="right" valign="top">Provider Notes:</td><td align="left">
<textarea wrap="virtual" name="provider_notes" cols="45" rows="8"><?=stripslashes($prop['PROVIDER_NOTES'])?></textarea>
</td></tr>

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
}
?>

<?php
}
?>