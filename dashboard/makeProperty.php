<?php
require_once("include/checklogin.php");

// connect to the database
require_once("include/db_connect.php");
require_once("include/simpleimage.php");
require_once("include/convertAddress.php");

// check if the form has been submitted. If it has, start to process the form and save it to the database
if (isset($_POST["submit"]))
{
	// get form data, making sure it is valid
	$center_name = $mysqli->real_escape_string($_POST["center_name"]);

	// check to make sure that all required fields are available
	if ($center_name != '') {

		$property_type_details = "";
		if (isset($_POST['property_type_details'])) {
			$property_type_details = implode(" ,", $_POST["property_type_details"]);
		}

		// get the photos from FILES array
		try {
			$photo1_data = null;
			if (isset($_FILES["photo_1"])) {
				$photo1 = $_FILES["photo_1"];
				if ($photo1['type']=="image/jpeg" || $photo1['type']=="image/pjpeg" ||
						$photo1['type']=="image/gif" || $photo1['type']=="image/png") {
					$tmpName = $photo1['tmp_name'];
					$image = new SimpleImage();
					$image->load($tmpName);
					$image->resizeToWidth(320);
					$image->save($tmpName);
					$photo1_data = file_get_contents($tmpName);
					$photo1_mime = $photo1['type'];
				}
			}
			$photo2_data = null;
			if (isset($_FILES["photo_2"])) {
				$photo2 = $_FILES["photo_2"];
				if ($photo2['type']=="image/jpeg" || $photo2['type']=="image/pjpeg" ||
						$photo2['type']=="image/gif" || $photo2['type']=="image/png") {
					$tmpName = $photo2['tmp_name'];
					$image = new SimpleImage();
					$image->load($tmpName);
					$image->resizeToWidth(320);
					$image->save($tmpName);
					$photo2_data = file_get_contents($tmpName);
					$photo2_mime = $photo2['type'];
				}
			}
			$photo3_data = null;
			if (isset($_FILES["photo_3"])) {
				$photo3 = $_FILES["photo_3"];
				if ($photo3['type']=="image/jpeg" || $photo3['type']=="image/pjpeg" ||
						$photo3['type']=="image/gif" || $photo3['type']=="image/png") {
					$tmpName = $photo3['tmp_name'];
					$image = new SimpleImage();
					$image->load($tmpName);
					$image->resizeToWidth(320);
					$image->save($tmpName);
					$photo3_data = file_get_contents($tmpName);
					$photo3_mime = $photo3['type'];
				}
			}
			$photo4_data = null;
			if (isset($_FILES["photo_4"])) {
				$photo4 = $_FILES["photo_4"];
				if ($photo4['type']=="image/jpeg" || $photo4['type']=="image/pjpeg" ||
						$photo4['type']=="image/gif" || $photo4['type']=="image/png") {
					$tmpName = $photo4['tmp_name'];
					$image = new SimpleImage();
					$image->load($tmpName);
					$image->resizeToWidth(320);
					$image->save($tmpName);
					$photo4_data = file_get_contents($tmpName);
					$photo4_mime = $photo4['type'];
				}
			}
			$photo5_data = null;
			if (isset($_FILES["photo_5"])) {
				$photo5 = $_FILES["photo_5"];
				if ($photo5['type']=="image/jpeg" || $photo5['type']=="image/pjpeg" ||
						$photo5['type']=="image/gif" || $photo5['type']=="image/png") {
					$tmpName = $photo5['tmp_name'];
					$image = new SimpleImage();
					$image->load($tmpName);
					$image->resizeToWidth(320);
					$image->save($tmpName);
					$photo5_data = file_get_contents($tmpName);
					$photo5_mime = $photo5['type'];
				}
			}
			$photo6_data = null;
			if (isset($_FILES["photo_6"])) {
				$photo6 = $_FILES["photo_6"];
				if ($photo6['type']=="image/jpeg" || $photo6['type']=="image/pjpeg" ||
				 		$photo6['type']=="image/gif" || $photo6['type']=="image/png") {
					$tmpName = $photo6['tmp_name'];
					$image = new SimpleImage();
					$image->load($tmpName);
					$image->resizeToWidth(320);
					$image->save($tmpName);
					$photo6_data = file_get_contents($tmpName);
					$photo6_mime = $photo6['type'];
				}
			}
		} catch (Exception $e) {
			die($e->getMessage());
		}

		$lat = null;
		$long = null;
		$address = $_POST['address_1'].','.$_POST['city'].','.$_POST['state'].','.$_POST['zip'];
		if ($address!="") {
			$geo = convertAddress2Geo($address);
			$lat = $geo[0];
			$long = $geo[1];
		}

		// increase the maximum packet size to handle photo uploads
		mysqli_options($mysqli,MYSQLI_READ_DEFAULT_GROUP,"max_allowed_packet=5M");
		// save the data to the database
		$stmt = $mysqli->prepare("INSERT INTO properties (
								PROVIDER_ID, CENTER_NAME, LEAD_COUNTIES, TITLE, CONTACT_NAME, CONTACT_EMAIL,
								OFFICE_PHONE, CELL_PHONE, OTHER_PHONE, FAX, WEBSITE, ADDRESS_1, ADDRESS_2, CITY, STATE, ZIP, COUNTRY,
								YEAR_BUILT, SQUARE_FEET, GARAGE_TYPE, GARAGES, CONVERTED_GARAGE, BEDROOMS, BATHROOMS, STORIES, POOL,
								PROPERTY_TYPE, PROPERTY_TYPE_DETAILS, RATING, YOUTUBE_ID, TERM_END_DATE, TERM_TIER, ACCEPTED_TERMS, BUYER_DESCRIPTION,
								LENDER_DESCRIPTION, REPRESENTATIVE_DESCRIPTION, BUYER_SALES_PRICE, LENDER_SALES_PRICE, REPRESENTATIVE_SALES_PRICE,
								PHOTO_1, PHOTO_2, PHOTO_3, PHOTO_4, PHOTO_5, PHOTO_6,
								PHOTO_1_MIME, PHOTO_2_MIME, PHOTO_3_MIME, PHOTO_4_MIME, PHOTO_5_MIME, PHOTO_6_MIME, PRIMARY_PHOTO,
								LEASED, NEEDS_WORK, FULLY_RENOVATED, RENTAL_GRADE_FINISH,
								PROP_LAT, PROP_LONG, DATE_ADDED)
								VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?,
										?, ?, ?, ?, ?, ?, ?, ?, ?, ?,
										?, ?, ?, ?, ?, ?, ?, ?, ?, ?,
										?, ?, ?, ?, ?, ?, ?, ?, ?, ?,
										?, ?, ?, ?, ?, ?, ?, ?, ?, ?,
										?, ?, ?, ?, ?, ?, ?, ?, ?)
								") or die($mysqli->error);
		$stmt->bind_param("ssssssssssssssssssssssssssssissssssssssbbbbbbssssssiiiiidds",
			$mysqli->real_escape_string($_POST["provider_id"]),
			$mysqli->real_escape_string($_POST["center_name"]),
			$mysqli->real_escape_string($_POST["lead_counties"]),
			$mysqli->real_escape_string($_POST["title"]),
			$mysqli->real_escape_string($_POST["contact_name"]),
			$mysqli->real_escape_string($_POST["contact_email"]),
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
			$mysqli->real_escape_string($_POST["year_built"]),
			$mysqli->real_escape_string($_POST["square_feet"]),
			$mysqli->real_escape_string($_POST["garage_type"]),
			$mysqli->real_escape_string($_POST["garages"]),
			$mysqli->real_escape_string($_POST["converted_garage"]),
			$mysqli->real_escape_string($_POST["bedrooms"]),
			$mysqli->real_escape_string($_POST["bathrooms"]),
			$mysqli->real_escape_string($_POST["stories"]),
			$mysqli->real_escape_string($_POST["pool"]),
			$mysqli->real_escape_string($_POST["property_type"]),
			$property_type_details,
			$mysqli->real_escape_string($_POST["rating"]),
			$mysqli->real_escape_string($_POST["youtube_id"]),
			$mysqli->real_escape_string($_POST["term_end_date"]),
			$mysqli->real_escape_string($_POST["term_tier"]),
			$mysqli->real_escape_string($_POST["accepted_terms"]),
			stripslashes(str_replace('\r\n', ' ', $mysqli->real_escape_string($_POST["buyer_description"]))),
			stripslashes(str_replace('\r\n', ' ', $mysqli->real_escape_string($_POST["lender_description"]))),
			stripslashes(str_replace('\r\n', ' ', $mysqli->real_escape_string($_POST["representative_description"]))),
			$mysqli->real_escape_string($_POST["buyer_sales_price"]),
			$mysqli->real_escape_string($_POST["lender_sales_price"]),
			$mysqli->real_escape_string($_POST["representative_sales_price"]),
			$mysqli->real_escape_string(null),
			$mysqli->real_escape_string(null),
			$mysqli->real_escape_string(null),
			$mysqli->real_escape_string(null),
			$mysqli->real_escape_string(null),
			$mysqli->real_escape_string(null),
			$mysqli->real_escape_string($photo1_mime),
			$mysqli->real_escape_string($photo2_mime),
			$mysqli->real_escape_string($photo3_mime),
			$mysqli->real_escape_string($photo4_mime),
			$mysqli->real_escape_string($photo5_mime),
			$mysqli->real_escape_string($photo6_mime),
			$mysqli->real_escape_string($_POST["primary_photo"]),
			$mysqli->real_escape_string($_POST["leased"]),
			$mysqli->real_escape_string($_POST["needs_work"]),
			$mysqli->real_escape_string($_POST["fully_renovated"]),
			$mysqli->real_escape_string($_POST["rental_grade_finish"]),
			$lat,
			$long,
			$mysqli->real_escape_string($_POST["date_added"])
		) or die($mysqli->error);

		/* Send large image data */
		$stmt->send_long_data(39, $photo1_data);
		$stmt->send_long_data(40, $photo2_data);
		$stmt->send_long_data(41, $photo3_data);
		$stmt->send_long_data(42, $photo4_data);
		$stmt->send_long_data(43, $photo5_data);
		$stmt->send_long_data(44, $photo6_data);

		/* Execute the statement */
		$stmt->execute() or die("Error: Could not execute statement");

		/* close statement */
		$stmt->close() or die("Error: Could not close statement");

		// once saved, redirect back to the view page
		header("Location: listProperties.php");
	}
	else	{
		echo "Error: Center name is required";
	}
}
else {
// if the form hasn't been submitted, display the form
?>

<?php 
	$lead_id = null;
	if (isset($_GET['lead_id'])) {
		$lead_id = $_GET['lead_id'];
	}

	$prop = null;
	if($lead_id!=null) {
		$result = $mysqli->query("SELECT * FROM leads WHERE lead_id=" . $lead_id) or die(mysqli_error());
		$prop = mysqli_fetch_array($result);
	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>SimpleHouseSolutions.com - Make Property</title>
<link rel="shortcut icon" href="/favicon.ico" />
<link rel="stylesheet" type="text/css" href="css/dashboard.css"/>
<link rel="stylesheet" type="text/css" href="css/dashboard_menu.css"/>
<link rel="stylesheet" type="text/css" href="js/tigra_calendar/calendar.css">
<script type="text/javascript" src="js/tigra_calendar/calendar_db.js"></script>
<script type="text/javascript" src="js/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript" src="js/site.js"></script>
<script type="text/javascript">
	tinyMCE.init({
		mode: "textareas",
    plugins : "spellchecker",
		theme: "advanced",
		theme_advanced_buttons1: "bold,italic,underline,strikethrough,separator,justifyleft,justifycenter,justifyright,justifyfull,bullist,numlist,undo,redo,link,unlink,spellchecker",
		theme_advanced_buttons2: "",
		theme_advanced_buttons3: "",
		theme_advanced_buttons4: "",
	  theme_advanced_toolbar_location: "top",
	  theme_advanced_toolbar_align: "left"
	});
</script>
</head>
<body>
<div id="header"><?php require "header.inc.php"; ?></div>
<div id="menu"><?php require "menu.inc.php"; ?></div>
<div id="content">
<!-- Begin Content-->

<div align="center">
<form name="form1" method="post" action="<?=$PHP_SELF?>" enctype="multipart/form-data">
<input type="hidden" name="date_added" value="<?=date("Y-m-d")?>">
<input type="hidden" name="addMakeProperty" value="1">
<table class="input">
<tr>
<th>Make Property</th>
<th style="text-align: center;">
<input class="button" type="submit" name="submit" value="Make Property">
<!-- <input class="button" type="button" onClick="javascript:history.back()" value="Cancel"> -->
<input class="button" type="button" onclick="javascript:location.href='index.php'" value="Cancel">
</th>
<th>&nbsp;</th>
</tr>

<tr><td align="left" valign="top" width="33%">

<table>
<tr><td align="right">Provider:</td><td align="left">
<select name="provider_id">
<option></option>
<?php
		$result = $mysqli->query("SELECT PROVIDER_ID,COMPANY_NAME FROM providers ORDER BY company_name ASC") or die(mysql_error());
		while($row = mysqli_fetch_array($result)){
			foreach($row AS $key => $value) {
				$row[$key] = stripslashes($value);
			}
?>
<option <?php echo $row['PROVIDER_ID'] == 1062 ? 'selected' : ''; ?> value="<?=$row['PROVIDER_ID']?>"><?=$row['COMPANY_NAME']?></option>
<?php
		}
?>
</select></td></tr>

<tr><td align="right">Center Name:</td><td align="left">
<input name="center_name" size="50" value="" required /></td></tr>

<tr><td align="right">Lead Counties:</td><td align="left">
<input name="lead_counties" size="50" value="" /></td></tr>

<tr><td align="right">Title:</td><td align="left">
<input name="title" size="30" value="" /></td></tr>

<tr><td align="right">Contact Name:</td><td align="left">
<input name="contact_name" size="50" value="" /></td></tr>

<tr><td align="right">Contact Email:</td><td align="left">
<input name="contact_email" size="50" maxlength="120" value="<?php echo $prop['CLIENT_EMAIL']; ?>" /></td></tr>

<tr><td align="right">Office Phone:</td><td align="left">
<input name="office_phone" size="30" value="<?php echo $prop['OFFICE_PHONE']; ?>" onblur="formatPhoneNumber(this);" /></td></tr>

<tr><td align="right">Cell Phone:</td><td align="left">
<input name="cell_phone" size="30" value="<?php echo $prop['CELL_PHONE']; ?>" onblur="formatPhoneNumber(this);" /></td></tr>

<tr><td align="right">Other Phone:</td><td align="left">
<input name="other_phone" size="30" value="<?php echo $prop['OTHER_PHONE']; ?>" onblur="formatPhoneNumber(this);" /></td></tr>

<tr><td align="right">Fax:</td><td align="left">
<input name="fax" size="30" value="<?php echo $prop['FAX']; ?>" onblur="formatPhoneNumber(this);" /></td></tr>

<tr><td align="right">Web Site:</td><td align="left">
<input name="website" size="50" value="<?php echo $prop['WEBSITE']; ?>" /></td></tr>

<tr><td align="right">Address1:</td><td align="left">
<input name="address_1" size="50" value="<?php echo $prop['ADDRESS_1']; ?>" /></td></tr>

<tr><td align="right">Address2:</td><td align="left">
<input name="address_2" size="50" value="<?php echo $prop['ADDRESS_2']; ?> " /></td></tr>

<tr><td align="right">City:</td><td align="left">
<input name="city" size="30" value="<?php echo $prop['CITY']; ?>" /></td></tr>

<tr><td align="right">State:</td><td align="left">
<select name="state">
<option></option>
<?php
		$result = $mysqli->query("SELECT * FROM states") or die(mysql_error());
		while($row = mysqli_fetch_array($result)){
			foreach($row AS $key => $value) {
				$row[$key] = stripslashes($value);
			}
?>
<option <?php echo $prop['STATE'] == $row['ABBREV'] ? 'selected' : ''; ?> value="<?=$row['ABBREV']?>"><?=$row['ABBREV']?></option>
<?php
		}
?>
</select>
</td></tr>

<tr><td align="right">Zip Code:</td><td align="left">
<input name="zip" size="10" value="<?php echo $prop['ZIP']; ?>" /></td></tr>

<tr><td align="right">Country:</td><td align="left">
<select name="country">
<option value="United States" <?php if($prop['COUNTRY']=="United States") echo "selected=\"selected\""; ?>>United States</option>
<?php
		$result = $mysqli->query("SELECT * FROM countries")	or die(mysql_error());
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

<tr><td colspan="2"><strong>Property Information</strong></td></tr>

<tr><td align="right">Year Built:</td><td align="left">
<input name="year_built" size="10" value="<?php echo $prop['YEAR_BUILT']; ?>" /></td></tr>

<tr><td align="right">Square Feet:</td><td align="left">
<input name="square_feet" size="10" value="<?php echo $prop['SQUARE_FEET']; ?>" /></td></tr>

<tr><td align="right">Garage Type:</td>
<td align="left">
<select name="garage_type">
	<option value=""></option>
	<option <?php echo $prop['GARAGE_TYPE'] == 'Attached' ? 'selected' : ''; ?> value="Attached">Attached</option>
	<option <?php echo $prop['GARAGE_TYPE'] == 'Attached' ? 'Detached' : ''; ?> value="Detached">Detached</option>
</select>
</td></tr>

<tr><td align="right">Garages:</td>
<td align="left">
<select name="garages">
	<option <?php echo $prop['GARAGES'] == 0 ? 'selected' : ''; ?> value="0">0</option>
	<option <?php echo $prop['GARAGES'] == 1 ? 'selected' : ''; ?> value="1">1</option>
	<option <?php echo $prop['GARAGES'] == 2 ? 'selected' : ''; ?> value="2">2</option>
	<option <?php echo $prop['GARAGES'] == 3 ? 'selected' : ''; ?> value="3">3</option>
	<option <?php echo $prop['GARAGES'] == 4 ? 'selected' : ''; ?> value="4">4</option>
	<option <?php echo $prop['GARAGES'] == 5 ? 'selected' : ''; ?> value="5">5</option>
</select>
</td></tr>

<tr><td align="right">Converted Garage:</td>
<td align="left">
<select name="converted_garage">
	<option <?php echo $prop['GARAGE_CONVERTED'] == "No" ? 'selected' : ''; ?> value="No">No</option>
	<option <?php echo $prop['GARAGE_CONVERTED'] == "Yes" ? 'selected' : ''; ?> value="Yes">Yes</option>
</select>
</td></tr>

<tr><td align="right">Bedrooms:</td>
<td align="left">
<select name="bedrooms">
	<option <?php echo $prop['BEDROOMS'] == 0 ? 'selected' : ''; ?> value="0">0</option>
	<option <?php echo $prop['BEDROOMS'] == 1 ? 'selected' : ''; ?> value="1">1</option>
	<option <?php echo $prop['BEDROOMS'] == 2 ? 'selected' : ''; ?> value="2">2</option>
	<option <?php echo $prop['BEDROOMS'] == 3 ? 'selected' : ''; ?> value="3">3</option>
	<option <?php echo $prop['BEDROOMS'] == 4 ? 'selected' : ''; ?> value="4">4</option>
	<option <?php echo $prop['BEDROOMS'] == 5 ? 'selected' : ''; ?> value="5">5</option>
</select>
</td></tr>

<tr><td align="right">Bathrooms:</td>
<td align="left">
<select name="bathrooms">
	<option <?php echo $prop['BATHROOMS'] == 0 ? 'selected' : ''; ?> value="0">0</option>
	<option <?php echo $prop['BATHROOMS'] == 1 ? 'selected' : ''; ?> value="1">1</option>
	<option <?php echo $prop['BATHROOMS'] == 2 ? 'selected' : ''; ?> value="2">2</option>
	<option <?php echo $prop['BATHROOMS'] == 3 ? 'selected' : ''; ?> value="3">3</option>
	<option <?php echo $prop['BATHROOMS'] == 4 ? 'selected' : ''; ?> value="4">4</option>
	<option <?php echo $prop['BATHROOMS'] == 5 ? 'selected' : ''; ?> value="5">5</option>
</select>
</td></tr>

<tr><td align="right">Stories:</td>
<td align="left">
<select name="stories">
	<option <?php echo $prop['STORIES'] == 1 ? 'selected' : ''; ?> value="1">1</option>
	<option <?php echo $prop['STORIES'] == 2 ? 'selected' : ''; ?> value="2">2</option>
</select>
</td></tr>

<tr><td align="right">Pool:</td>
<td align="left">
<select name="pool">
	<option <?php echo $prop['POOL'] == 'No' ? 'selected' : ''; ?> value="No">No</option>
	<option <?php echo $prop['POOL'] == 'Yes' ? 'selected' : ''; ?> value="Yes">Yes</option>
</select>
</td></tr>
</table>

</td>
<td align="left" valign="top" width="50%">

<table>
<tr>
<td align="right" valign="top">Property Type:</td>
<td align="left" valign="top">
<select name="property_type">
<option <?php echo $prop['PROPERTY_TYPE'] == 'Single Family' ? 'selected' : ''; ?> value="Single Family">Single Family</option>
<option <?php echo $prop['PROPERTY_TYPE'] == 'Townhome/Condo' ? 'selected' : ''; ?> value="Townhome/Condo">Townhome/Condo</option>
<option <?php echo $prop['PROPERTY_TYPE'] == 'Duplex' ? 'selected' : ''; ?> value="Duplex">Duplex</option>
<option <?php echo $prop['PROPERTY_TYPE'] == 'Multi Family' ? 'selected' : ''; ?> value="Multi Family">Multi Family</option>
<option <?php echo $prop['PROPERTY_TYPE'] == 'Land' ? 'selected' : ''; ?> value="Land">Land</option>
<option <?php echo $prop['PROPERTY_TYPE'] == 'Agent Lead' ? 'selected' : ''; ?> value="Agent Lead">Agent Lead</option>
<option <?php echo $prop['PROPERTY_TYPE'] == 'Listing' ? 'selected' : ''; ?> value="Listing">Listing</option>
<option <?php echo $prop['PROPERTY_TYPE'] == 'TAT Agent' ? 'selected' : ''; ?> value="TAT Agent">TAT Agent</option>
<option <?php echo $prop['PROPERTY_TYPE'] == 'SHS Agent' ? 'selected' : ''; ?> value="SHS Agent">SHS Agent</option>
<option <?php echo $prop['PROPERTY_TYPE'] == 'PLV' ? 'selected' : ''; ?> value="PLV">PLV</option>
</select>
</td>
<td align="right" valign="top">Property Type Details:</td>
<td align="left">
<input type="checkbox" name="property_type_details[]" value="Rental" />Rental<br />
<input type="checkbox" name="property_type_details[]" value="Flip" />Flip<br />
<input type="checkbox" name="property_type_details[]" value="Wholesale" />Wholesale<br />
<input type="checkbox" name="property_type_details[]" value="Owner Occupied" />Owner Occupied<br />
</td>
</tr>

<tr>
<td align="right">Rating:</td>
<td align="left">
<select name="rating">
<option value="3">3 Star</option>
<option value="4">4 Star</option>
<option value="5">5 Star</option>
</select>
</td>
<td align="right">Term End Date:</td>
<td align="left" valign="top">
<input name="term_end_date" id="term_end_date" size="10" value="" />
<script type="text/javascript">
	var t_cal = new tcal ({
		'controlname': 'term_end_date'
	});
</script>
</td>
</tr>

<tr>
<td align="right">YouTube ID:</td>
<td align="left">
<input name="youtube_id" size="15" value="" />
</td>
<td align="right">Term Tier:</td>
<td align="left">
<select name="term_tier">
<option value="Regular">Regular</option>
<option value="Preferred">Preferred</option>
</select>
</td>
</tr>

<tr>
<td align="right"></td>
<td align="left">
</td>
<td align="right">Accepted Terms:</td>
<td align="left" colspan="3"><input type="checkbox" name="accepted_terms" /></td>
</tr>

<tr>
<td align="right" valign="top">Buyer Description:</td>
<td align="left" colspan="3">
<textarea wrap="virtual" name="buyer_description" cols="75" rows="10"></textarea>
</td>
</tr>

<tr>
<td align="right">Buyer Sales Price:</td><td align="left">
<input name="buyer_sales_price" size="15" value="" /></td>
</tr>

<tr>
<td align="right" valign="top">Lender Description:</td>
<td align="left" colspan="3">
<textarea wrap="virtual" name="lender_description" cols="75" rows="10"></textarea>
</td>
</tr>

<tr>
<td align="right">Lender Sales Price:</td><td align="left">
<input name="lender_sales_price" size="15" value="" /></td>
</tr>

<tr>
<td align="right" valign="top">Representative Description:</td>
<td align="left" colspan="3">
<textarea wrap="virtual" name="representative_description" cols="75" rows="10"></textarea>
</td>
</tr>

<tr>
<td align="right">Representative Sales Price:</td><td align="left">
<input name="representative_sales_price" size="15" value="" /></td>
</tr>

<tr>
<td align="right">Primary Photo:</td>
<td align="left" colspan="3">
<select name="primary_photo">
<option value="1">1</option>
<option value="2">2</option>
<option value="3">3</option>
<option value="4">4</option>
<option value="5">5</option>
<option value="6">6</option>
</select>
</td>
</tr>
</table>

</td>
<td align="left" valign="top" width="16%">

<table>
<td align="left" valign="top">
Condition:<br />
<input type="checkbox" name="leased" value="1" />Leased<br />
<input type="checkbox" name="needs_work" value="1" />Needs Work<br />
<input type="checkbox" name="fully_renovated" value="1" />Fully Renovated<br />
<input type="checkbox" name="rental_grade_finish" value="1" />Rental Grade Finish<br />
</td></tr>
<!-- <td align="left" valign="top">
Condition:<br />
<input type="checkbox" name="leased" value="1" />Leased<br />
<input type="checkbox" name="needs_work" value="1" />Needs Work<br />
<input type="checkbox" name="fully_renovated" value="1" />Fully Renovated<br />
<input type="checkbox" name="rental_grade_finish" value="1" />Rental Grade Finish<br />
</td></tr> -->
</table>

<tr><td colspan="3">
<table width="100%">
<tr>
<td align="right" valign="top">Photo 1:</td>
<td align="left">
<input type="file" name="photo_1" size="30" value="" />
</td>
<td align="right" valign="top">Photo 2:</td>
<td align="left">
<input type="file" name="photo_2" size="30" value="" />
</td>
<td align="right" valign="top">Photo 3:</td>
<td align="left">
<input type="file" name="photo_3" size="30" value="" />
</td>
</tr>

<tr>
<td align="right" valign="top">Photo 4:</td>
<td align="left">
<input type="file" name="photo_4" size="30" value="" />
</td>
<td align="right" valign="top">Photo 5:</td>
<td align="left">
<input type="file" name="photo_5" size="30" value="" />
</td>
<td align="right" valign="top">Photo 6:</td>
<td align="left">
<input type="file" name="photo_6" size="30" value="" />
</td>
</tr>
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