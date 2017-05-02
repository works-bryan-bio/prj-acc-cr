<?php
require_once("include/checklogin.php");

// connect to the database
require_once("include/db_connect.php");
require_once("include/simpleimage.php");
require_once("include/convertAddress.php");

// check if the form has been submitted. If it has, start to process the form and save it to the database
if (isset($_POST["submit"]) || isset($_POST["dsubmit"]))
{
	// get form data, making sure it is valid
	$property_id = $mysqli->real_escape_string($_POST["property_id"]);

	// check to make sure that all required fields are available
	if ($property_id != '') {

		$property_type_details = "";
		if (isset($_POST['property_type_details'])) {
			$property_type_details = implode(" ,", $_POST["property_type_details"]);
		}

		// get the photos from FILES array
		$result = $mysqli->query("SELECT * FROM properties WHERE property_id=" . $property_id) or die(mysqli_error());
		$row = mysqli_fetch_array($result);
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
			} else {
				if($_POST["photo_1_action"]=="keep") {
					$photo1_data = $row["PHOTO_1"];
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
			} else {
				if($_POST["photo_2_action"]=="keep") {
					$photo2_data = $row["PHOTO_2"];
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
			} else {
				if($_POST["photo_3_action"]=="keep") {
					$photo3_data = $row["PHOTO_3"];
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
			} else {
				if($_POST["photo_4_action"]=="keep") {
					$photo4_data = $row["PHOTO_4"];
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
			} else {
				if($_POST["photo_5_action"]=="keep") {
					$photo5_data = $row["PHOTO_5"];
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
			} else {
				if($_POST["photo_6_action"]=="keep") {
					$photo6_data = $row["PHOTO_6"];
				}
			}
		} catch (Exception $e) {
			die($e->getMessage());
		}

		$lat = null;
		$long = null;
		$current = $row['ADDRESS_1'].','.$row['CITY'].','.$row['STATE'].','.$row['ZIP'];
		$address = $_POST['address_1'].','.$_POST['city'].','.$_POST['state'].','.$_POST['zip'];
		if($row["PROP_LAT"]==null || $row["PROP_LONG"]==null || $current!=$address) {
			if ($address!="") {
				$geo = convertAddress2Geo($address);
				$lat = $geo[0];
				$long = $geo[1];
			}
		} else {
			$lat = $row['PROP_LAT'];
			$long = $row['PROP_LONG'];
		}

		// increase the maximum packet size to handle photo uploads
		mysqli_options($mysqli,MYSQLI_READ_DEFAULT_GROUP,"max_allowed_packet=5M");
		// save the data to the database
		$query = "UPDATE properties SET PROVIDER_ID=?, CENTER_NAME=?, LEAD_COUNTIES=?, TITLE=?, CONTACT_NAME=?, CONTACT_EMAIL=?,
				OFFICE_PHONE=?, CELL_PHONE=?, OTHER_PHONE=?, FAX=?, WEBSITE=?, ADDRESS_1=?, ADDRESS_2=?, CITY=?, STATE=?, ZIP=?, COUNTRY=?,
				YEAR_BUILT=?, SQUARE_FEET=?, GARAGE_TYPE=?, GARAGES=?, CONVERTED_GARAGE=?, BEDROOMS=?, BATHROOMS=?, STORIES=?, POOL=?,
				PROPERTY_TYPE=?, PROPERTY_TYPE_DETAILS=?, RATING=?, YOUTUBE_ID=?, TERM_END_DATE=?, TERM_TIER=?, ACCEPTED_TERMS=?, STATUS=?, BUYER_DESCRIPTION=?,
				LENDER_DESCRIPTION=?, REPRESENTATIVE_DESCRIPTION=?, BUYER_SALES_PRICE=?, LENDER_SALES_PRICE=?, REPRESENTATIVE_SALES_PRICE=?,
				PHOTO_1=?, PHOTO_2=?, PHOTO_3=?, PHOTO_4=?, PHOTO_5=?, PHOTO_6=?,
				PHOTO_1_MIME=?, PHOTO_2_MIME=?, PHOTO_3_MIME=?, PHOTO_4_MIME=?, PHOTO_5_MIME=?, PHOTO_6_MIME=?, PRIMARY_PHOTO=?,
				LEASED=?, NEEDS_WORK=?, FULLY_RENOVATED=?, RENTAL_GRADE_FINISH=?,
				PROP_LAT=?, PROP_LONG=? WHERE property_id=$property_id";
		$stmt = $mysqli->prepare($query) or die($mysqli->error);
		$stmt->bind_param("ssssssssssssssssssssssssssssisssssssssssbbbbbbssssssiiiiidd",
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
			$mysqli->real_escape_string($_POST["status"]),
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
			$long
		) or die($mysqli->error);

		/* Send large image data */
		$stmt->send_long_data(40, $photo1_data);
		$stmt->send_long_data(41, $photo2_data);
		$stmt->send_long_data(42, $photo3_data);
		$stmt->send_long_data(43, $photo4_data);
		$stmt->send_long_data(44, $photo5_data);
		$stmt->send_long_data(45, $photo6_data);

		/* Execute the statement */
		$stmt->execute() or die("Could not execute statement");

		/* close statement */
		$stmt->close() or die("Could not close statement");

		if (isset($_POST["dsubmit"])) {
			header("Location: listProperties.php");
		} else {
			header("Location: editProperty.php?property_id=" . $property_id);
		}
	}
	else	{
		echo "Error: Property ID is required";
	}
} else if (isset($_POST["delsubmit"])) {

	// get form data, making sure it is valid
	$property_id = $mysqli->real_escape_string($_POST["property_id"]);

	// check to make sure that all required fields are available
	if ($property_id != '') {

		// save the data to the database
		$stmt = $mysqli->prepare("DELETE FROM properties WHERE property_id=$property_id") or die($mysqli->error);

		/* Execute the statement */
		$stmt->execute() or die("Error: Could not execute statement");

		/* close statement */
		$stmt->close() or die("Error: Could not close statement");

		header("Location: listProperties.php");
	}
	else	{
		echo "Error: Property ID is required";
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

<?php
if (!isset($_GET['property_id'])) {
?>
	<div align="center"><h3>Error: Property ID not provided</h3></div>
<?php
} else {
	$prop_id = $_GET['property_id'];
	$result = $mysqli->query("SELECT * FROM properties WHERE property_id=" . $prop_id)
		or die(mysqli_error());
	$prop = mysqli_fetch_array($result);
?>
<div align="center">
<form name="form1" method="post" action="<?=$PHP_SELF?>" enctype="multipart/form-data">
<input type="hidden" name="property_id" value="<?=$prop_id?>" />
<input type="hidden" name="referer" value="<?=$_SERVER['HTTP_REFERER']?>" />
<input class="button" type="submit" name="submit" value="Save Changes">
<input class="button" type="submit" name="dsubmit" value="Save and Go to Properties" />
<input class="button" type="submit" name="delsubmit" value="Delete" onclick="return confirm_delete();" />
<input class="button" type="button" onClick="javascript:history.back()" value="Cancel">
<table class="input">
<tr>
<th>Edit Property</th>
<th>
&nbsp;
</th>
<th style="text-align:right;">Last Update: <?php if($prop['LAST_UPDATED']=="") echo "None"; else echo date("Y-m-d h:i A", strtotime($prop['LAST_UPDATED']))?></th>
</tr>

<tr><td align="left" valign="top" width="33%">

<table>
<tr><td align="right">Provider:</td><td align="left" colspan="2">
<select name="provider_id">
<option></option>
<?php
		$result = $mysqli->query("SELECT PROVIDER_ID,COMPANY_NAME FROM providers ORDER BY company_name ASC") or die(mysql_error());
		while($row = mysqli_fetch_array($result)){
			foreach($row AS $key => $value) {
				$row[$key] = stripslashes($value);
			}
?>
<option value="<?=$row['PROVIDER_ID']?>" <?if($row['PROVIDER_ID']==$prop['PROVIDER_ID']) echo "selected=\"selected\""?>><?=$row['COMPANY_NAME']?></option>
<?php
		}
?>
</select></td></tr>

<tr><td align="right">Center Name:</td><td align="left" colspan="2">
<input name="center_name" size="50" value="<?=$prop['CENTER_NAME']?>" /></td></tr>

<tr><td align="right">Lead Counties:</td><td align="left" colspan="2">
<input name="lead_counties" size="50" value="<?=$prop['LEAD_COUNTIES']?>" /></td></tr>

<tr><td align="right">Title:</td><td align="left" colspan="2">
<input name="title" size="30" value="<?=$prop['TITLE']?>" /></td></tr>

<tr><td align="right">Contact Name:</td><td align="left" colspan="2">
<input name="contact_name" size="50" value="<?=$prop['CONTACT_NAME']?>" /></td></tr>

<tr><td align="right">Contact Email:</td><td align="left" colspan="2">
<input name="contact_email" size="50" maxlength="120" value="<?=$prop['CONTACT_EMAIL']?>" /></td></tr>

<tr><td align="right">Office Phone:</td><td align="left" colspan="2">
<input name="office_phone" size="30" value="<?=$prop['OFFICE_PHONE']?>" onblur="formatPhoneNumber(this);" /></td></tr>

<tr><td align="right">Cell Phone:</td><td align="left" colspan="2">
<input name="cell_phone" size="30" value="<?=$prop['CELL_PHONE']?>" onblur="formatPhoneNumber(this);" /></td></tr>

<tr><td align="right">Other Phone:</td><td align="left" colspan="2">
<input name="other_phone" size="30" value="<?=$prop['OTHER_PHONE']?>" onblur="formatPhoneNumber(this);" /></td></tr>

<tr><td align="right">Fax:</td><td align="left" colspan="2">
<input name="fax" size="30" value="<?=$prop['FAX']?>" onblur="formatPhoneNumber(this);" /></td></tr>

<tr><td align="right">Web Site:</td><td align="left" colspan="2">
<input name="website" size="50" value="<?=$prop['WEBSITE']?>" /></td></tr>

<tr><td align="right">Address1:</td><td align="left" colspan="2">
<input name="address_1" size="50" value="<?=$prop['ADDRESS_1']?>" /></td></tr>

<tr><td align="right">Address2:</td><td align="left" colspan="2">
<input name="address_2" size="50" value="<?=$prop['ADDRESS_2']?>" /></td></tr>

<tr><td align="right">City:</td><td align="left" colspan="2">
<input name="city" size="30" value="<?=$prop['CITY']?>" /></td></tr>

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
<option value="<?=$row['ABBREV']?>" <?if($row['ABBREV']==$prop['STATE']) echo "selected=\"selected\""?>><?=$row['ABBREV']?></option>
<?php
		}
?>
</select>
</td></tr>

<tr><td align="right">Zip Code:</td><td align="left" colspan="2">
<input name="zip" size="10" value="<?=$prop['ZIP']?>" /></td></tr>

<tr><td align="right">Country:</td><td align="left">
<select name="country">
<option value="United States" <?if($prop['COUNTRY']=="United States") echo "selected=\"selected\""?>>United States</option>
<?php
		$result = $mysqli->query("SELECT * FROM countries")	or die(mysql_error());
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

<tr><td align="right">Latitude:</td><td align="left" colspan="2"><?=$prop['PROP_LAT']?></td></tr>

<tr><td align="right">Longitude:</td><td align="left" colspan="2"><?=$prop['PROP_LONG']?></td></tr>

<tr><td colspan="2"><strong>Property Information</strong></td></tr>

<tr><td align="right">Year Built:</td><td align="left">
<input name="year_built" size="10" value="<?=$prop['YEAR_BUILT']?>" /></td></tr>

<tr><td align="right">Square Feet:</td><td align="left">
<input name="square_feet" size="10" value="<?=$prop['SQUARE_FEET']?>" /></td></tr>

<tr><td align="right">Garage Type:</td>
<td align="left">
<select name="garage_type">
	<option value="" <?if($prop['GARAGE_TYPE']=='') echo "selected=\"selected\""?>></option>
	<option value="Attached" <?if($prop['GARAGE_TYPE']=='Attached') echo "selected=\"selected\""?>>Attached</option>
	<option value="Detached" <?if($prop['GARAGE_TYPE']=='Detached') echo "selected=\"selected\""?>>Detached</option>
</select>
</td></tr>

<tr><td align="right">Garages:</td>
<td align="left">
<select name="garages">
	<option value="0" <?if($prop['GARAGES']=='0') echo "selected=\"selected\""?>>0</option>
	<option value="1" <?if($prop['GARAGES']=='1') echo "selected=\"selected\""?>>1</option>
	<option value="2" <?if($prop['GARAGES']=='2') echo "selected=\"selected\""?>>2</option>
	<option value="3" <?if($prop['GARAGES']=='3') echo "selected=\"selected\""?>>3</option>
	<option value="4" <?if($prop['GARAGES']=='4') echo "selected=\"selected\""?>>4</option>
	<option value="5" <?if($prop['GARAGES']=='5') echo "selected=\"selected\""?>>5</option>
</select>
</td></tr>

<tr><td align="right">Converted Garage:</td>
<td align="left">
<select name="converted_garage">
	<option value="No" <?if($prop['CONVERTED_GARAGE']=='No') echo "selected=\"selected\""?>>No</option>
	<option value="Yes" <?if($prop['CONVERTED_GARAGE']=='Yes') echo "selected=\"selected\""?>>Yes</option>
</select>
</td></tr>

<tr><td align="right">Bedrooms:</td>
<td align="left">
<select name="bedrooms">
	<option value="0" <?if($prop['BEDROOMS']=='0') echo "selected=\"selected\""?>>0</option>
	<option value="1" <?if($prop['BEDROOMS']=='1') echo "selected=\"selected\""?>>1</option>
	<option value="2" <?if($prop['BEDROOMS']=='2') echo "selected=\"selected\""?>>2</option>
	<option value="3" <?if($prop['BEDROOMS']=='3') echo "selected=\"selected\""?>>3</option>
	<option value="4" <?if($prop['BEDROOMS']=='4') echo "selected=\"selected\""?>>4</option>
	<option value="5" <?if($prop['BEDROOMS']=='5') echo "selected=\"selected\""?>>5</option>
</select>
</td></tr>

<tr><td align="right">Bathrooms:</td>
<td align="left">
<select name="bathrooms">
	<option value="0" <?if($prop['BATHROOMS']=='0') echo "selected=\"selected\""?>>0</option>
	<option value="1" <?if($prop['BATHROOMS']=='1') echo "selected=\"selected\""?>>1</option>
	<option value="2" <?if($prop['BATHROOMS']=='2') echo "selected=\"selected\""?>>2</option>
	<option value="3" <?if($prop['BATHROOMS']=='3') echo "selected=\"selected\""?>>3</option>
	<option value="4" <?if($prop['BATHROOMS']=='4') echo "selected=\"selected\""?>>4</option>
	<option value="5" <?if($prop['BATHROOMS']=='5') echo "selected=\"selected\""?>>5</option>
</select>
</td></tr>

<tr><td align="right">Stories:</td>
<td align="left">
<select name="stories">
	<option value="1" <?if($prop['STORIES']=='1') echo "selected=\"selected\""?>>1</option>
	<option value="2" <?if($prop['STORIES']=='2') echo "selected=\"selected\""?>>2</option>
</select>
</td></tr>

<tr><td align="right">Pool:</td>
<td align="left">
<select name="pool">
	<option value="No" <?if($prop['POOL']=='No') echo "selected=\"selected\""?>>No</option>
	<option value="Yes" <?if($prop['POOL']=='Yes') echo "selected=\"selected\""?>>Yes</option>
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
<option value="Single Family" <?if($prop['PROPERTY_TYPE']=="Single Family") echo "selected=\"selected\"";?>>Single Family</option>
<option value="Townhome/Condo" <?if($prop['PROPERTY_TYPE']=="Townhome/Condo") echo "selected=\"selected\"";?>>Townhome/Condo</option>
<option value="Duplex" <?if($prop['PROPERTY_TYPE']=="Duplex") echo "selected=\"selected\"";?>>Duplex</option>
<option value="Multi Family" <?if($prop['PROPERTY_TYPE']=="Multi Family") echo "selected=\"selected\"";?>>Multi Family</option>
<option value="Land" <?if($prop['PROPERTY_TYPE']=="Land") echo "selected=\"selected\"";?>>Land</option>
<option value="Agent Lead" <?if($prop['PROPERTY_TYPE']=="Agent Lead") echo "selected=\"selected\"";?>>Agent Lead</option>
<option value="Listing" <?if($prop['PROPERTY_TYPE']=="Listing") echo "selected=\"selected\"";?>>Listing</option>
<option value="TAT Agent" <?if($prop['PROPERTY_TYPE']=="TAT Agent") echo "selected=\"selected\"";?>>TAT Agent</option>
<option value="SHS Agent" <?if($prop['PROPERTY_TYPE']=="SHS Agent") echo "selected=\"selected\"";?>>SHS Agent</option>
<option value="PLV" <?if($prop['PROPERTY_TYPE']=="PLV") echo "selected=\"selected\"";?>>PLV</option>
</select>
</td>
<td align="right" valign="top">Property Type Details:</td>
<td align="left">
<input type="checkbox" name="property_type_details[]" value="Rental" <?if(strpos($prop['PROPERTY_TYPE_DETAILS'], "Rental") !== FALSE) echo "checked";?> />Rental<br />
<input type="checkbox" name="property_type_details[]" value="Flip" <?if(strpos($prop['PROPERTY_TYPE_DETAILS'], "Flip") !== FALSE) echo "checked";?> />Flip<br />
<input type="checkbox" name="property_type_details[]" value="Wholesale" <?if(strpos($prop['PROPERTY_TYPE_DETAILS'], "Wholesale") !== FALSE) echo "checked";?> />Wholesale<br />
<input type="checkbox" name="property_type_details[]" value="Owner Occupied" <?if(strpos($prop['PROPERTY_TYPE_DETAILS'], "Owner Occupied") !== FALSE) echo "checked";?> />Owner Occupied<br />
</td>
</tr>

<tr>
<td align="right">Rating:</td>
<td align="left">
<select name="rating">
<option value="3" <?if($prop['RATING']=="3") echo "selected=\"selected\"";?>>3 Star</option>
<option value="4" <?if($prop['RATING']=="4") echo "selected=\"selected\"";?>>4 Star</option>
<option value="5" <?if($prop['RATING']=="5") echo "selected=\"selected\"";?>>5 Star</option>
</select>
</td>
<td align="right">Term End Date:</td><td align="left" colspan="2">
<input name="term_end_date" id="term_end_date" size="10" value="<?php if ($prop['TERM_END_DATE']=="0000-00-00") echo ""; else echo $prop['TERM_END_DATE'];?>" />
<script type="text/javascript">
	var f_cal = new tcal ({
		'controlname': 'term_end_date'
	});
</script>
</td>
</tr>

<tr>
<td align="right">YouTube ID:</td>
<td align="left">
<input name="youtube_id" size="15" value="<?=$prop['YOUTUBE_ID']?>" />
</td>
<td align="right">Term Tier:</td>
<td align="left">
<select name="term_tier">
<option value="Regular" <?if($prop['TERM_TIER']=="Regular") echo "selected=\"selected\"";?>>Regular</option>
<option value="Preferred" <?if($prop['TERM_TIER']=="Preferred") echo "selected=\"selected\"";?>>Preferred</option>
</select>
</td>
</tr>

<tr>
<td align="right" valign="top">Property Status:</td>
<td align="left">
<select name="status" class="<?=$prop['STATUS']?>" onchange="this.setAttribute('class',this.value);">
<option value="active" <?if($prop['STATUS']=="active") echo "selected=\"selected\"";?>>Active</option>
<option value="pending" <?if($prop['STATUS']=="pending") echo "selected=\"selected\"";?>>Pending</option>
<option value="delisted" <?if($prop['STATUS']=="delisted") echo "selected=\"selected\"";?>>Delisted</option>
</select>
</td>
<td align="right">Accepted Terms:</td>
<td align="left">
<input type="checkbox" name="accepted_terms" value="1" <?if($prop['ACCEPTED_TERMS']==1) echo "checked=\"checked\"";?>/>
</td>
</tr>

<tr>
<td align="right" valign="top">Buyer Description:</td>
<td align="left" colspan="3">
<textarea wrap="virtual" name="buyer_description" cols="75" rows="10"><?=stripslashes($prop['BUYER_DESCRIPTION'])?></textarea>
</td>
</tr>

<tr>
<td align="right">Buyer Sales Price:</td><td align="left">
<input name="buyer_sales_price" size="15" value="<?=$prop['BUYER_SALES_PRICE']?>" /></td>
</tr>

<tr>
<td align="right" valign="top">Lender Description:</td>
<td align="left" colspan="3">
<textarea wrap="virtual" name="lender_description" cols="75" rows="10"><?=stripslashes($prop['LENDER_DESCRIPTION'])?></textarea>
</td>
</tr>

<tr>
<td align="right">Lender Sales Price:</td><td align="left">
<input name="lender_sales_price" size="15" value="<?=$prop['LENDER_SALES_PRICE']?>" /></td>
</tr>

<tr>
<td align="right" valign="top">Representative Description:</td>
<td align="left" colspan="3">
<textarea wrap="virtual" name="representative_description" cols="75" rows="10"><?=stripslashes($prop['REPRESENTATIVE_DESCRIPTION'])?></textarea>
</td>
</tr>

<tr>
<td align="right">Representative Sales Price:</td><td align="left">
<input name="representative_sales_price" size="15" value="<?=$prop['REPRESENTATIVE_SALES_PRICE']?>" /></td>
</tr>

<tr>
<td align="right">Primary Photo:</td>
<td align="left" colspan="3">
<select name="primary_photo">
<option value="1" <?if($prop['PRIMARY_PHOTO']=="1") echo "selected=\"selected\"";?>>1</option>
<option value="2" <?if($prop['PRIMARY_PHOTO']=="2") echo "selected=\"selected\"";?>>2</option>
<option value="3" <?if($prop['PRIMARY_PHOTO']=="3") echo "selected=\"selected\"";?>>3</option>
<option value="4" <?if($prop['PRIMARY_PHOTO']=="4") echo "selected=\"selected\"";?>>4</option>
<option value="5" <?if($prop['PRIMARY_PHOTO']=="5") echo "selected=\"selected\"";?>>5</option>
<option value="6" <?if($prop['PRIMARY_PHOTO']=="6") echo "selected=\"selected\"";?>>6</option>
</select>
</td>
</tr>
</table>

</td>
<td align="left" valign="top" width="16%">

<table>
<tr>
<td align="left" valign="top">
Condition:<br />
<input type="checkbox" name="leased" value="1" <?if($prop['LEASED']==1) echo "checked=\"checked\"";?> />Leased<br />
<input type="checkbox" name="needs_work" value="1" <?if($prop['NEEDS_WORK']==1) echo "checked=\"checked\"";?> />Needs Work<br />
<input type="checkbox" name="fully_renovated" value="1" <?if($prop['FULLY_RENOVATED']==1) echo "checked=\"checked\"";?> />Fully Renovated<br />
<input type="checkbox" name="rental_grade_finish" value="1" <?if($prop['RENTAL_GRADE_FINISH']==1) echo "checked=\"checked\"";?> />Rental Grade Finish<br />
</td></tr>
</table>

<tr><td colspan="3">
<table width="100%">
<tr>
<td align="right" valign="top">Photo 1:</td>
<td align="left">
<img name="photo_1_img" src="getimage.php?id=<?=$prop[PROPERTY_ID]?>&image=photo_1"><br />
<input name="photo_1_action" value="keep" onclick="document.form1.photo_1.disabled=true;document.form1.photo_1_img.style.display='block';" checked="checked" type="radio"> Keep
<input name="photo_1_action" value="delete" onclick="document.form1.photo_1.disabled=true;document.form1.photo_1_img.style.display='none';" type="radio"> Delete
<input name="photo_1_action" value="change" onclick="document.form1.photo_1.disabled=false;document.form1.photo_1_img.style.display='none';" type="radio"> Change <br />
<input type="file" name="photo_1" size="30" value="" disabled="disabled" />
</td>
<td align="right" valign="top">Photo 2:</td>
<td align="left">
<img name="photo_2_img" src="getimage.php?id=<?=$prop[PROPERTY_ID]?>&image=photo_2"><br />
<input name="photo_2_action" value="keep" onclick="document.form1.photo_2.disabled=true;document.form1.photo_2_img.style.display='block';" checked="checked" type="radio"> Keep
<input name="photo_2_action" value="delete" onclick="document.form1.photo_2.disabled=true;document.form1.photo_2_img.style.display='none';" type="radio"> Delete
<input name="photo_2_action" value="change" onclick="document.form1.photo_2.disabled=false;document.form1.photo_2_img.style.display='none';" type="radio"> Change <br />
<input type="file" name="photo_2" size="30" value="" disabled="disabled" />
</td>
<td align="right" valign="top">Photo 3:</td>
<td align="left">
<img name="photo_3_img" src="getimage.php?id=<?=$prop[PROPERTY_ID]?>&image=photo_3"><br />
<input name="photo_3_action" value="keep" onclick="document.form1.photo_3.disabled=true;document.form1.photo_3_img.style.display='block';" checked="checked" type="radio"> Keep
<input name="photo_3_action" value="delete" onclick="document.form1.photo_3.disabled=true;document.form1.photo_3_img.style.display='none';" type="radio"> Delete
<input name="photo_3_action" value="change" onclick="document.form1.photo_3.disabled=false;document.form1.photo_3_img.style.display='none';" type="radio"> Change <br />
<input type="file" name="photo_3" size="30" value="" disabled="disabled" />
</td>
</tr>

<tr>
<td align="right" valign="top">Photo 4:</td>
<td align="left">
<img name="photo_4_img" src="getimage.php?id=<?=$prop[PROPERTY_ID]?>&image=photo_4"><br />
<input name="photo_4_action" value="keep" onclick="document.form1.photo_4.disabled=true;document.form1.photo_4_img.style.display='block';" checked="checked" type="radio"> Keep
<input name="photo_4_action" value="delete" onclick="document.form1.photo_4.disabled=true;document.form1.photo_4_img.style.display='none';" type="radio"> Delete
<input name="photo_4_action" value="change" onclick="document.form1.photo_4.disabled=false;document.form1.photo_4_img.style.display='none';" type="radio"> Change <br />
<input type="file" name="photo_4" size="30" value="" disabled="disabled" />
</td>
<td align="right" valign="top">Photo 5:</td>
<td align="left">
<img name="photo_5_img" src="getimage.php?id=<?=$prop[PROPERTY_ID]?>&image=photo_5"><br />
<input name="photo_5_action" value="keep" onclick="document.form1.photo_5.disabled=true;document.form1.photo_5_img.style.display='block';" checked="checked" type="radio"> Keep
<input name="photo_5_action" value="delete" onclick="document.form1.photo_5.disabled=true;document.form1.photo_5_img.style.display='none';" type="radio"> Delete
<input name="photo_5_action" value="change" onclick="document.form1.photo_5.disabled=false;document.form1.photo_5_img.style.display='none';" type="radio"> Change <br />
<input type="file" name="photo_5" size="30" value="" disabled="disabled" />
</td>
<td align="right" valign="top">Photo 6:</td>
<td align="left">
<img name="photo_6_img" src="getimage.php?id=<?=$prop[PROPERTY_ID]?>&image=photo_6"><br />
<input name="photo_6_action" value="keep" onclick="document.form1.photo_6.disabled=true;document.form1.photo_6_img.style.display='block';" checked="checked" type="radio"> Keep
<input name="photo_6_action" value="delete" onclick="document.form1.photo_6.disabled=true;document.form1.photo_6_img.style.display='none';" type="radio"> Delete
<input name="photo_6_action" value="change" onclick="document.form1.photo_6.disabled=false;document.form1.photo_6_img.style.display='none';" type="radio"> Change <br />
<input type="file" name="photo_6" size="30" value="" disabled="disabled" />
</td>
</tr>
</table>

</td></tr>
</table>
</form>
</div>
<?php
}
?>

<?php
}
?>

<!-- End Content -->
</div>
</body>
</html>