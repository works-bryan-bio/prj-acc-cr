<?php
require_once("include/checklogin.php");
require_once("include/session.php");
require_once("include/db_connect.php");

// store the lead_id if set
$lead_id = null;
if (isset($_GET['lead_id'])) {
	$lead_id = $_GET['lead_id'];
}

$action = "";
if (isset($_GET['action'])) {
	$action = $_GET['action'];
}

$username = $_SESSION['username'];

$financing_available = "";
if (isset($_POST["financing_available"])) {
	$financing_available = implode(" ,", $_POST["financing_available"]);
}

// check if the form has been submitted. If it has, start to process the form and save it to the database
if (isset($_POST["submit"]) && $lead_id==null) {

	// get form data, making sure it is valid
	$first_name = $mysqli->real_escape_string($_POST["first_name"]);
	$last_name = $mysqli->real_escape_string($_POST["last_name"]);

	// check to make sure that all required fields are available
	if ($first_name != '' || $last_name !='') {

		if ($_POST['add_notes']!="") {
		} else {
			$notes = $_POST['notes'];
		}

		if ($_POST['add_manager_notes']!="") {
		} else {
			$manager_notes = $_POST['manager_notes'];
		}		

		// save the data to the database
		$stmt = $mysqli->prepare("INSERT INTO leads (USERNAME, COMPANY_NAME, TITLE, FIRST_NAME, LAST_NAME, CLIENT_EMAIL, POSITION,
								EXTRA_TITLE, EXTRA_FIRST_NAME, EXTRA_LAST_NAME, EXTRA_CLIENT_EMAIL,
								OFFICE_PHONE, CELL_PHONE, OTHER_PHONE, FAX, WEBSITE, ADDRESS_1, ADDRESS_2, CITY, STATE, ZIP, OWNERS_ON_TITLE, SECOND_CHANCE,
								FUNDS_FOR_PURCHASE, FINANCING_AVAILABLE, NEED_LENDER,
								CLOSER, PRIORITY, TITLE_COMPANY, STATUS, PROPERTY_TYPE, YEAR_BUILT, SQUARE_FEET, GARAGE_TYPE, GARAGES, GARAGE_CONVERTED,
								BEDROOMS, BATHROOMS, STORIES, POOL, RENTED, ARV, ASKING_PRICE, CURRENT_MORTGAGE, CURRENT_PAYMENTS, DEAD_REASON, BACKSIDE_CONTRACT,
								CLOSED_DATE, EXIT_STRATEGY, FOLLOW_UP_DATE, FOLLOW_UP_TIME, PROVIDER_INFO, NOTES, LEAD_TYPE,
								PREDICTED_AMT, FORECAST_CHANCE, EARNEST_RECEIPT, EXECUTED_DATE, END_OF_OPTION, SEARCH_CITY,
								SEARCH_STATE, AREA_OF_INTEREST, AFFILIATE_ID, DATE_ADDED,
								AS_IS_PRICE, OWNER_OCCUPIED, HOW_LONG_OWNED, ROOF_AGE, HVAC_AGE,
								POOL_CONDITION, NEED_FOUNDATION_REPAIR, CABINET_TYPE, COUNTER_TYPE, FLOORING_TYPE,
								MASTER_BATH_AGE, HALF_BATH_AGE, UPGRADES, INSURANCE, RENT_AMT,
								TERM, MOVE_DATE, DEPOSIT, LISTED, HOW_LONG,
								LISTING_PRICE, OFFER_PRICE, MOVING_REASON, TIME_FRAME_SELL, PRICE_FLEXIBLE,
								ASKING_PRICE_REASON, CASH_QUICK_CLOSE, ANY_BETTER, DOESNT_SELL,
								HH_REPAIR_COST, WT_REPAIR_COST, RH_LIPSTICK, RH_RENT_COMP
								)
								VALUES (
								?, ?, ?, ?, ?, ?, ?, ?, ?, ?,
								?, ?, ?, ?, ?, ?, ?, ?, ?, ?,
								?, ?, ?, ?, ?, ?, ?, ?, ?, ?,
								?, ?, ?, ?, ?, ?, ?, ?, ?, ?,
								?, ?, ?, ?, ?, ?, ?, ?, ?, ?,
								?, ?, ?, ?, ?, ?, ?, ?, ?, ?,
								?, ?, ?, ?, ?, ?, ?, ?, ?, ?,
								?, ?, ?, ?, ?, ?, ?, ?, ?, ?,
								?, ?, ?, ?, ?, ?, ?, ?, ?, ?,
								?, ?, ?, ?, ?, ?, ?)
								") or die($mysqli->error);
		$stmt->bind_param("ssssssssssssssssssssssssssssssssisisiiissdddssssssssssdsssssssisisssssssssssssississiisssssssiiii",
			$mysqli->real_escape_string($_POST["username"]),
			stripslashes($mysqli->real_escape_string($_POST["company_name"])),
			stripslashes($mysqli->real_escape_string($_POST["title"])),
			stripslashes($mysqli->real_escape_string($_POST["first_name"])),
			stripslashes($mysqli->real_escape_string($_POST["last_name"])),
			stripslashes($mysqli->real_escape_string($_POST["client_email"])),
			stripslashes($mysqli->real_escape_string($_POST["position"])),
			stripslashes($mysqli->real_escape_string($_POST["extra_title"])),
			stripslashes($mysqli->real_escape_string($_POST["extra_first_name"])),
			stripslashes($mysqli->real_escape_string($_POST["extra_last_name"])),
			stripslashes($mysqli->real_escape_string($_POST["extra_client_email"])),
			stripslashes($mysqli->real_escape_string($_POST["office_phone"])),
			stripslashes($mysqli->real_escape_string($_POST["cell_phone"])),
			stripslashes($mysqli->real_escape_string($_POST["other_phone"])),
			stripslashes($mysqli->real_escape_string($_POST["fax"])),
			stripslashes($mysqli->real_escape_string($_POST["website"])),
			stripslashes($mysqli->real_escape_string($_POST["address_1"])),
			stripslashes($mysqli->real_escape_string($_POST["address_2"])),
			stripslashes($mysqli->real_escape_string($_POST["city"])),
			$mysqli->real_escape_string($_POST["state"]),
			stripslashes($mysqli->real_escape_string($_POST["zip"])),
			stripslashes($mysqli->real_escape_string($_POST["owners_on_title"])),
			$mysqli->real_escape_string($_POST["second_chance"]),
			$mysqli->real_escape_string($_POST["funds_for_purchase"]),
			$financing_available,
			$mysqli->real_escape_string($_POST["need_lender"]),
			stripslashes($mysqli->real_escape_string($_POST["closer"])),
			$mysqli->real_escape_string($_POST["priority"]),
			stripslashes($mysqli->real_escape_string($_POST["title_company"])),
			$mysqli->real_escape_string($_POST["status"]),
			$mysqli->real_escape_string($_POST["property_type"]),
			$mysqli->real_escape_string($_POST["year_built"]),
			$mysqli->real_escape_string($_POST["square_feet"]),
			$mysqli->real_escape_string($_POST["garage_type"]),
			$mysqli->real_escape_string($_POST["garages"]),
			$mysqli->real_escape_string($_POST["garage_converted"]),
			$mysqli->real_escape_string($_POST["bedrooms"]),
			$mysqli->real_escape_string($_POST["bathrooms"]),
			$mysqli->real_escape_string($_POST["stories"]),
			$mysqli->real_escape_string($_POST["pool"]),
			$mysqli->real_escape_string($_POST["rented"]),
			$mysqli->real_escape_string($_POST["arv"]),
			$mysqli->real_escape_string($_POST["asking_price"]),
			$mysqli->real_escape_string($_POST["current_mortgage"]),
			$mysqli->real_escape_string($_POST["current_payments"]),
			$mysqli->real_escape_string($_POST["dead_reason"]),
			$mysqli->real_escape_string($_POST["backside_contract"]),
			$mysqli->real_escape_string($_POST["closed_date"]),
			$mysqli->real_escape_string($_POST["exit_strategy"]),
			$mysqli->real_escape_string($_POST["follow_up_date"]),
			$mysqli->real_escape_string($_POST["follow_up_time"]),
			stripslashes(str_replace('\r\n', '', $mysqli->real_escape_string($_POST["provider_info"]))),
			$notes,
			$mysqli->real_escape_string($_POST["lead_type"]),
			$mysqli->real_escape_string($_POST["predicted_amt"]),
			$mysqli->real_escape_string($_POST["forecast_chance"]),
			$mysqli->real_escape_string($_POST["earnest_receipt"]),
			$mysqli->real_escape_string($_POST["executed_date"]),
			$mysqli->real_escape_string($_POST["end_of_option"]),
			stripslashes($mysqli->real_escape_string($_POST["search_city"])),
			$mysqli->real_escape_string($_POST["search_state"]),
			stripslashes(str_replace('\r\n', '', $mysqli->real_escape_string($_POST["area_of_interest"]))),
			$mysqli->real_escape_string($_POST["affiliate_id"]),
			$mysqli->real_escape_string($_POST["date_added"]),
			$mysqli->real_escape_string($_POST["as_is_price"]),
			$mysqli->real_escape_string($_POST["owner_occupied"]),
			$mysqli->real_escape_string($_POST["how_long_owned"]),
			$mysqli->real_escape_string($_POST["roof_age"]),
			$mysqli->real_escape_string($_POST["hvac_age"]),
			$mysqli->real_escape_string($_POST["pool_condition"]),
			$mysqli->real_escape_string($_POST["need_foundation_repair"]),
			$mysqli->real_escape_string($_POST["cabinet_type"]),
			$mysqli->real_escape_string($_POST["counter_type"]),
			$mysqli->real_escape_string($_POST["flooring_type"]),
			$mysqli->real_escape_string($_POST["master_bath_age"]),
			$mysqli->real_escape_string($_POST["half_bath_age"]),
			$mysqli->real_escape_string($_POST["upgrades"]),
			$mysqli->real_escape_string($_POST["insurance"]),
			$mysqli->real_escape_string($_POST["rent_amt"]),
			$mysqli->real_escape_string($_POST["term"]),
			$mysqli->real_escape_string($_POST["move_date"]),
			$mysqli->real_escape_string($_POST["deposit"]),
			$mysqli->real_escape_string($_POST["listed"]),
			$mysqli->real_escape_string($_POST["how_long"]),
			$mysqli->real_escape_string($_POST["listing_price"]),
			$mysqli->real_escape_string($_POST["offer_price"]),
			$mysqli->real_escape_string($_POST["moving_reason"]),
			$mysqli->real_escape_string($_POST["time_frame_sell"]),
			$mysqli->real_escape_string($_POST["price_flexible"]),
			$mysqli->real_escape_string($_POST["asking_price_reason"]),
			$mysqli->real_escape_string($_POST["cash_quick_close"]),
			$mysqli->real_escape_string($_POST["any_better"]),
			$mysqli->real_escape_string($_POST["doesnt_sell"]),
			$mysqli->real_escape_string($_POST["hh_repair_cost"]),
			$mysqli->real_escape_string($_POST["wt_repair_cost"]),
			$mysqli->real_escape_string($_POST["rh_lipstick"]),
			$mysqli->real_escape_string($_POST["rh_rent_comp"])
		) or die($mysqli->error);

		/* Execute the statement */
		$stmt->execute() or die("Error: Could not execute statement");

		/* close statement */
		$stmt->close() or die("Error: Could not close statement");

		// once saved, redirect back to the lead page
		header("Location: editLead.php?lead_id=" . $mysqli->insert_id);
	}
} else if (isset($_POST["submit"]) || isset($_POST["dsubmit"]) && $lead_id!=null) {

	// check to make sure that all required fields are available
	if ($lead_id != '') {

		if ($_POST['add_notes']!="") {
			$notes = "[" . date("m/d/Y h:i A T") . " - " . $username . "]&#13;" . $mysqli->real_escape_string(stripslashes($_POST['add_notes'])) . "&#13;&#13;" . $_POST['notes'];
		} else {
			$notes = $_POST['notes'];
		}

		if ($_POST['add_manager_notes']!="") {
			$manager_notes = "[" . date("m/d/Y h:i A T") . " - " . $username . "]&#13;" . $mysqli->real_escape_string(stripslashes($_POST['add_manager_notes'])) . "&#13;&#13;" . $_POST['manager_notes'];
		} else {
			$manager_notes = $_POST['manager_notes'];
		}

		//Save the data to the database
		//Master Account only can change
		$manager_arv = $prop['MANAGER_ARV'];
		$manager_as_is_price = $prop['MANAGER_AS_IS_PRICE'];
		if( $session->isMaster() ){
			$manager_arv = $_POST['manager_arv'];
			$manager_as_is_price = $_POST['manager_as_is_price'];
		}

		$stmt = $mysqli->prepare("UPDATE leads SET
								USERNAME=?, COMPANY_NAME=?, TITLE=?, FIRST_NAME=?, LAST_NAME=?, CLIENT_EMAIL=?, POSITION=?,
								EXTRA_TITLE=?, EXTRA_FIRST_NAME=?, EXTRA_LAST_NAME=?, EXTRA_CLIENT_EMAIL=?, OFFICE_PHONE=?, CELL_PHONE=?,
								OTHER_PHONE=?, FAX=?, WEBSITE=?, ADDRESS_1=?, ADDRESS_2=?, CITY=?, STATE=?, ZIP=?, OWNERS_ON_TITLE=?, SECOND_CHANCE=?,
								FUNDS_FOR_PURCHASE=?, FINANCING_AVAILABLE=?, NEED_LENDER=?,
								CLOSER=?, PRIORITY=?, TITLE_COMPANY=?, STATUS=?, PROPERTY_TYPE=?, YEAR_BUILT=?, SQUARE_FEET=?, GARAGE_TYPE=?, GARAGES=?, GARAGE_CONVERTED=?,
								BEDROOMS=?, BATHROOMS=?, STORIES=?, POOL=?, RENTED=?, ARV=?, ASKING_PRICE=?, CURRENT_MORTGAGE=?, CURRENT_PAYMENTS=?, DEAD_REASON=?, BACKSIDE_CONTRACT=?,
								CLOSED_DATE=?, EXIT_STRATEGY=?, FOLLOW_UP_DATE=?, FOLLOW_UP_TIME=?, PROVIDER_INFO=?, NOTES=?, MANAGER_NOTES=?, LEAD_TYPE=?,
								PREDICTED_AMT=?, FORECAST_CHANCE=?, EARNEST_RECEIPT=?, EXECUTED_DATE=?, END_OF_OPTION=?, SEARCH_CITY=?,
								SEARCH_STATE=?, AREA_OF_INTEREST=?, AFFILIATE_ID=?,
								AS_IS_PRICE=?, OWNER_OCCUPIED=?, HOW_LONG_OWNED=?, ROOF_AGE=?, HVAC_AGE=?,
								POOL_CONDITION=?, NEED_FOUNDATION_REPAIR=?, CABINET_TYPE=?, COUNTER_TYPE=?, FLOORING_TYPE=?,
								MASTER_BATH_AGE=?, HALF_BATH_AGE=?, UPGRADES=?, INSURANCE=?, RENT_AMT=?,
								TERM=?, MOVE_DATE=?, DEPOSIT=?, LISTED=?, HOW_LONG=?,
								LISTING_PRICE=?, OFFER_PRICE=?, MOVING_REASON=?, TIME_FRAME_SELL=?, PRICE_FLEXIBLE=?,
								ASKING_PRICE_REASON=?, CASH_QUICK_CLOSE=?, ANY_BETTER=?, DOESNT_SELL=?,
								HH_REPAIR_COST=?, WT_REPAIR_COST=?, RH_LIPSTICK=?, RH_RENT_COMP=?, MANAGER_ARV=?, MANAGER_AS_IS_PRICE=?
								WHERE lead_id=$lead_id") or die($mysqli->error);
		$stmt->bind_param("ssssssssssssssssssssssssssssssssisisiiissdddsssssssssssssssssssiisssssssssssssississiisssssssiiiiii",
		$mysqli->real_escape_string($_POST["username"]),
			stripslashes($mysqli->real_escape_string($_POST["company_name"])),
			stripslashes($mysqli->real_escape_string($_POST["title"])),
			stripslashes($mysqli->real_escape_string($_POST["first_name"])),
			stripslashes($mysqli->real_escape_string($_POST["last_name"])),
			stripslashes($mysqli->real_escape_string($_POST["client_email"])),
			stripslashes($mysqli->real_escape_string($_POST["position"])),
			stripslashes($mysqli->real_escape_string($_POST["extra_title"])),
			stripslashes($mysqli->real_escape_string($_POST["extra_first_name"])),
			stripslashes($mysqli->real_escape_string($_POST["extra_last_name"])),
			stripslashes($mysqli->real_escape_string($_POST["extra_client_email"])),
			stripslashes($mysqli->real_escape_string($_POST["office_phone"])),
			stripslashes($mysqli->real_escape_string($_POST["cell_phone"])),
			stripslashes($mysqli->real_escape_string($_POST["other_phone"])),
			stripslashes($mysqli->real_escape_string($_POST["fax"])),
			stripslashes($mysqli->real_escape_string($_POST["website"])),
			stripslashes($mysqli->real_escape_string($_POST["address_1"])),
			stripslashes($mysqli->real_escape_string($_POST["address_2"])),
			stripslashes($mysqli->real_escape_string($_POST["city"])),
			$mysqli->real_escape_string($_POST["state"]),
			stripslashes($mysqli->real_escape_string($_POST["zip"])),
			stripslashes($mysqli->real_escape_string($_POST["owners_on_title"])),
			$mysqli->real_escape_string($_POST["second_chance"]),
			$mysqli->real_escape_string($_POST["funds_for_purchase"]),
			$financing_available,
			$mysqli->real_escape_string($_POST["need_lender"]),
			stripslashes($mysqli->real_escape_string($_POST["closer"])),
			$mysqli->real_escape_string($_POST["priority"]),
			stripslashes($mysqli->real_escape_string($_POST["title_company"])),
			$mysqli->real_escape_string($_POST["status"]),
			$mysqli->real_escape_string($_POST["property_type"]),
			$mysqli->real_escape_string($_POST["year_built"]),
			$mysqli->real_escape_string($_POST["square_feet"]),
			$mysqli->real_escape_string($_POST["garage_type"]),
			$mysqli->real_escape_string($_POST["garages"]),
			$mysqli->real_escape_string($_POST["garage_converted"]),
			$mysqli->real_escape_string($_POST["bedrooms"]),
			$mysqli->real_escape_string($_POST["bathrooms"]),
			$mysqli->real_escape_string($_POST["stories"]),
			$mysqli->real_escape_string($_POST["pool"]),
			$mysqli->real_escape_string($_POST["rented"]),
			$mysqli->real_escape_string($_POST["arv"]),
			$mysqli->real_escape_string($_POST["asking_price"]),
			$mysqli->real_escape_string($_POST["current_mortgage"]),
			$mysqli->real_escape_string($_POST["current_payments"]),
			$mysqli->real_escape_string($_POST["dead_reason"]),
			$mysqli->real_escape_string($_POST["backside_contract"]),
			$mysqli->real_escape_string($_POST["closed_date"]),
			$mysqli->real_escape_string($_POST["exit_strategy"]),
			$mysqli->real_escape_string($_POST["follow_up_date"]),
			$mysqli->real_escape_string($_POST["follow_up_time"]),
			stripslashes(str_replace('\r\n', ' ', $mysqli->real_escape_string($_POST["provider_info"]))),
			$notes,
			$manager_notes,
			$mysqli->real_escape_string($_POST["lead_type"]),
			$mysqli->real_escape_string($_POST["predicted_amt"]),
			$mysqli->real_escape_string($_POST["forecast_chance"]),
			$mysqli->real_escape_string($_POST["earnest_receipt"]),
			$mysqli->real_escape_string($_POST["executed_date"]),
			$mysqli->real_escape_string($_POST["end_of_option"]),
			stripslashes($mysqli->real_escape_string($_POST["search_city"])),
			$mysqli->real_escape_string($_POST["search_state"]),
			stripslashes(str_replace('\r\n', '', $mysqli->real_escape_string($_POST["area_of_interest"]))),
			$mysqli->real_escape_string($_POST["affiliate_id"]),
			$mysqli->real_escape_string($_POST["as_is_price"]),
			$mysqli->real_escape_string($_POST["owner_occupied"]),
			$mysqli->real_escape_string($_POST["how_long_owned"]),
			$mysqli->real_escape_string($_POST["roof_age"]),
			$mysqli->real_escape_string($_POST["hvac_age"]),
			$mysqli->real_escape_string($_POST["pool_condition"]),
			$mysqli->real_escape_string($_POST["need_foundation_repair"]),
			$mysqli->real_escape_string($_POST["cabinet_type"]),
			$mysqli->real_escape_string($_POST["counter_type"]),
			$mysqli->real_escape_string($_POST["flooring_type"]),
			$mysqli->real_escape_string($_POST["master_bath_age"]),
			$mysqli->real_escape_string($_POST["half_bath_age"]),
			$mysqli->real_escape_string($_POST["upgrades"]),
			$mysqli->real_escape_string($_POST["insurance"]),
			$mysqli->real_escape_string($_POST["rent_amt"]),
			$mysqli->real_escape_string($_POST["term"]),
			$mysqli->real_escape_string($_POST["move_date"]),
			$mysqli->real_escape_string($_POST["deposit"]),
			$mysqli->real_escape_string($_POST["listed"]),
			$mysqli->real_escape_string($_POST["how_long"]),
			$mysqli->real_escape_string($_POST["listing_price"]),
			$mysqli->real_escape_string($_POST["offer_price"]),
			$mysqli->real_escape_string($_POST["moving_reason"]),
			$mysqli->real_escape_string($_POST["time_frame_sell"]),
			$mysqli->real_escape_string($_POST["price_flexible"]),
			$mysqli->real_escape_string($_POST["asking_price_reason"]),
			$mysqli->real_escape_string($_POST["cash_quick_close"]),
			$mysqli->real_escape_string($_POST["any_better"]),
			$mysqli->real_escape_string($_POST["doesnt_sell"]),
			$mysqli->real_escape_string($_POST["hh_repair_cost"]),
			$mysqli->real_escape_string($_POST["wt_repair_cost"]),
			$mysqli->real_escape_string($_POST["rh_lipstick"]),
			$mysqli->real_escape_string($_POST["rh_rent_comp"]),
			$mysqli->real_escape_string($manager_arv),
			$mysqli->real_escape_string($manager_as_is_price)
		) or die($mysqli->error);

		/* Execute the statement */
		$stmt->execute() or die("Error: Could not execute statement");

		/* close statement */
		$stmt->close() or die("Error: Could not close statement");

		// once saved, redirect back to the lead page or dashboard, depending on the button pressed
		if (isset($_POST["dsubmit"])) {
			header("Location: index.php");
		} else {
			header("Location: editLead.php?lead_id=" . $lead_id);
		}
	}
	else	{
		echo "Error: Lead ID is required";
	}

} else if (isset($_POST["rsubmit"]) && $lead_id!=null) {

	$result = $mysqli->query("DELETE FROM leads WHERE LEAD_ID=" . $lead_id);
	$result = $mysqli->query("DELETE FROM search_report WHERE LEAD_ID=" . $lead_id);
	$result = $mysqli->query("DELETE FROM emails WHERE LEAD_ID=" . $lead_id);
	header("Location: index.php");

} else if (isset($_POST['submit_file']) && $lead_id!=null) {

	header('Content-Type: text/plain; charset=utf-8');

	if(isset($_FILES['fileToUpload'])){

		$errors 	 = array();
		$file_name = $_FILES['fileToUpload']['name'];
		$file_size = $_FILES['fileToUpload']['size'];
		$file_tmp  = $_FILES['fileToUpload']['tmp_name'];
		$file_type = $_FILES['fileToUpload']['type'];
		$file_err  = $_FILES['fileToUpload']['error'];
		$file_title = $_POST['file_title'];

		if(isset($file_err) && $file_err != 0) {
			$errors[] = 'Error uploading file..';
		}

		if(!isset($file_title) || $file_title == '') {
			$errors[] = 'File Name must not be null..';
		}

		if(empty($errors)==true) {
			move_uploaded_file($file_tmp,"files/lead_attachments/".strtolower($file_name));
			//After upload save file to database
			global $session, $database, $form;			
			$q = "INSERT INTO lead_attachments (
                                    type, lead_id, title, filename, date_uploaded
                                )VALUES(
                                	1,
                                    " . $lead_id . ",
                                    '" . stripslashes(str_replace('\r\n', ' ', $_POST['file_title'])) . "',
                                    '" . stripslashes(str_replace('\r\n', ' ', strtolower($file_name))) . "',
                                    '" . stripslashes(str_replace('\r\n', ' ', date("Y-m-d H:i:s"))) . "')";  

            $result = $database->query($q); 
            header("Location: editLead.php?lead_id=" . $lead_id);
		}else{
		 print_r($errors);
		 exit;
		}
	}	

} else if( $_GET['del_attachment'] == 1 && $lead_id != null && $_GET['del_attachment'] != '' ) {
	global $session, $database, $form;

	unlink('files/lead_attachments/'. $_GET['file']);

	$del = "DELETE FROM lead_attachments WHERE id = ". $_GET['attach_id'] ." ";  
	$result = $database->query($del);
	header("Location: editLead.php?lead_id=" . $lead_id); 	
} else {
// if the form hasn't been submitted, display the form)
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>SimpleHouseSolutions.com - Dashboard</title>
<link rel="icon" href="/favicon.ico" type="image/x-icon" />
<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
<link rel="stylesheet" type="text/css" href="css/dashboard.css"/>
<link rel="stylesheet" type="text/css" href="css/dashboard_menu.css"/>
<link rel="stylesheet" type="text/css" href="js/tigra_calendar/calendar.css">

<link rel="stylesheet" type="text/css" href="css/balloon.min.css"/>

<script type="text/javascript" src="js/tigra_calendar/calendar_db.js"></script>
<script type="text/javascript" src="js/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript" src="js/site.js"></script>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
<script type="text/javascript">
	tinyMCE.init({
	    mode: "exact",
	    elements: "elm1,message",
	    plugins : "spellchecker",
	    theme: "advanced",
	    theme_advanced_buttons1: "bold,italic,underline,strikethrough,separator,justifyleft,justifycenter,justifyright,justifyfull,bullist,numlist,undo,redo,link,unlink,spellchecker",
	    theme_advanced_buttons2: "",
	    theme_advanced_buttons3: "",
	    theme_advanced_buttons4: "",
	    theme_advanced_toolbar_location: "top",
	    theme_advanced_toolbar_align: "left"
	});

	$(document).ready(function () {

		calculateExitStrategy();

		//When you click on a link with class of poplight and the href starts with a #
		$('a.poplight[href^=#]').click(function () {
			var popID = $(this).attr('rel'); //Get Popup Name
			var popURL = $(this).attr('href'); //Get Popup href to define size

			//Pull Query & Variables from href URL
			var query = popURL.split('?');
			var dim = query[1].split('&');
			var popWidth = dim[0].split('=')[1]; //Gets the first query string value

			//Fade in the Popup and add close button
			$('#' + popID).fadeIn().css({'width': Number(popWidth)}).prepend('<a href="#" style="float:right" class="close">Close [X]</a>');

			//Define margin for center alignment (vertical   horizontal) - we add 80px to the height/width to accomodate for the padding  and border width defined in the css
			var popMargTop = ($('#' + popID).height() + 80) / 2;
			var popMargLeft = ($('#' + popID).width() + 80) / 2;

			//Apply Margin to Popup
			$('#' + popID).css({
				'margin-top': -popMargTop,
				'margin-left': -popMargLeft
			});

			if (popID === "email_popup") {
				document.getElementById("template").value = "";
				document.getElementById("subject").value = "";
				tinyMCE.get("message").setContent("");
			}

			//Fade in Background
			$('body').append('<div id="fade"></div>'); //Add the fade layer to bottom of the body tag.
			$('#fade').css({'filter': 'alpha(opacity=80)'}).fadeIn(); //Fade in the fade layer - .css({'filter' : 'alpha(opacity=80)'}) is used to fix the IE Bug on fading transparencies

			return false;
		});

		//Close Popups and Fade Layer
		$('a.close').live('click', function () { //When clicking on the close or fade layer...
			$('#fade , .popup_block').fadeOut(function () {
				$('#fade, a.close').remove();  //fade them both out
			});
			return false;
		});

	});

	function callHelper(uri) {
		if (uri === "") {
			return;
		}
		if (window.XMLHttpRequest) { // code for IE7+, Firefox, Chrome, Opera, Safari
			xmlhttp = new XMLHttpRequest();
		} else { // code for IE6, IE5
			xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange = function () {
			if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
				var serverResponse = xmlhttp.responseText;
				if (serverResponse !== "") {
					if (uri.indexOf("sendEmail") !== -1) {
						alert(serverResponse);
					}
				}
			}
		};
		var message = tinyMCE.get("message").getContent();
		var subject = document.getElementById("subject").value;

		xmlhttp.open("POST", uri, true);
		xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
		xmlhttp.send("subject=" + subject + "&message=" + encodeURIComponent(message));
	}

	function confirm_remove() {
	  	var ret = confirm("Are you sure you want to remove this lead?");
	  	if (ret == true) {
	        return true;
		} else {
			return false;
		}
	}

	function check_hot_strong() {
		var priority = document.getElementById("priority").value;

		if (priority=="Hot" || priority == "Strong") {
			if (document.getElementById("lead_type").value == "Seller" && document.getElementById("forecast_chance").value == "") {
			alert("Error: You must enter a Forecast Chance and End Of Option Date for Hot/Strong leads");
			return false;
			}

			if (document.getElementById("lead_type").value == "Seller" && (document.getElementById("arv").value == "" || document.getElementById("arv").value == 0.00)) {
			alert("Error: You must enter an ARV for Hot/Strong leads");
			return false;
			}
		}
	}

</script>
</head>
<body onunload="">
<div id="header"><?php require "header.inc.php"; ?></div>
<div id="menu"><?php require "menu.inc.php"; ?></div>
<div id="content">
<!-- Begin Content-->

<?php
require_once("include/db_connect.php");

$prop = null;

if($lead_id!=null) {
	$result = $mysqli->query("SELECT * FROM leads WHERE lead_id=" . $lead_id)
		or die(mysqli_error());
	$prop = mysqli_fetch_array($result);

	//Next and Previous Record
	$result_next_record     = $mysqli->query("SELECT lead_id FROM leads WHERE lead_id >" . $lead_id . " ORDER BY lead_id ASC LIMIT 1")
		or die(mysqli_error());
	$result_previous_record = $mysqli->query("SELECT lead_id FROM leads WHERE lead_id <" . $lead_id . " ORDER BY lead_id DESC LIMIT 1")
		or die(mysqli_error());
	$prop_next     = mysqli_fetch_array($result_next_record);
	$prop_previous = mysqli_fetch_array($result_previous_record);	

}
?>
<form name="form1" method="post" action="<?=$PHP_SELF?>" onsubmit="return check_hot_strong();" enctype="multipart/form-data">

<input type="hidden" name="date_added" value="<?=date("Y-m-d H:i:s")?>">
<input type="hidden" name="affiliate_id" value="<?php if ($prop!=null) { echo $prop['AFFILIATE_ID']; } else { echo "50"; } ?>">
<div align="center">
<?php
	if($lead_id!=null) {
		$client_email =  $prop['CLIENT_EMAIL'];
		if ($client_email!=null && trim($client_email)!="") {
			$cequery = "CLIENT_EMAIL='" . $client_email . "'";
		} else {
			$cequery = "1=2";
		}
		$office_phone = $prop['OFFICE_PHONE'];
			if ($office_phone!=null && trim($office_phone)!="") {
			$ofquery = "OFFICE_PHONE='" . $office_phone . "'";
		} else {
			$ofquery = "1=2";
		}
		$cell_phone = $prop['CELL_PHONE'];
			if ($cell_phone!=null && trim($cell_phone)!="") {
			$cpquery = "CELL_PHONE='" . $cell_phone . "'";
		} else {
			$cpquery = "1=2";
		}
		$dupe_query = "SELECT * FROM leads WHERE LEAD_ID!=$lead_id AND (" . $cequery . " OR " . $ofquery . " OR " . $cpquery . ")";
		$result = $mysqli->query($dupe_query) or die(mysql_error());
		if ($result->num_rows>0) {
?>
<div align="center">
<span style="color:red">Potential Duplicate Lead(s):
<?php
			while($row = mysqli_fetch_array($result)){
  			foreach($row AS $key => $value) {
					$row[$key] = stripslashes($value);
				}
?>
<a style="color:red" href="editLead.php?lead_id=<?=$row['LEAD_ID']?>">Lead <?=$row['LEAD_ID']?></a>&nbsp;
<?php
			}
?>
</span>
</div>
<br />
<?php
		}
	}
?>

<input class="button" type="submit" name="submit" value="Save Changes" />
<input class="button" type="submit" name="dsubmit" value="Save and Go to Dashboard" <?php if ($lead_id==null) echo "disabled='disabled'" ?> />
<?php if ($session->isMaster()) { ?>
<input class="button" type="submit" name="rsubmit" value="Remove Lead" onClick="return confirm_remove();" />
<?php } ?>
<input class="button" type="button" name="dbutton" value="Cancel" onclick="window.location='index.php'" />
<!-- <input class="button" type="button" name="dbutton" value="Duplicate" onclick="window.location='duplicateLead.php'" formtarget="_blank" /> -->
<a target="_blank" class="button" href="duplicateLead.php?duplicate_id=<?php echo $lead_id ?>">Duplicate</a>
<a target="_blank" class="button" href="leadPdfHelper.php?lead_id=<?php echo $lead_id ?>&print=1">Print</a>
<div style="float:right;margin-right:10px;">	
<?php if( !empty($prop_previous) ){ ?>
<a class="button" href="editLead.php?lead_id=<?php echo $prop_previous['lead_id']; ?>" style="text-decoration:none;color:#333;font-weight:normal;margin:0px;">&#xab;</a>
<?php } ?>
<?php if( !empty($prop_next) ){ ?>
<a class="button" href="editLead.php?lead_id=<?php echo $prop_next['lead_id']; ?>" style="text-decoration:none;color:#333;font-weight:normal;margin:0px;">&#xbb;</a>
<?php } ?>
</div>
<table class="input" width="100%">
<tr>
	<th width="85%" valign="bottom" style="width: 85%;">
		<a href="editLead.php?lead_id=<?=$lead_id?>">Client Information</a>&nbsp;|&nbsp;
		<a href="searchReport.php?lead_id=<?=$lead_id?>">Search Report</a>&nbsp;|&nbsp;
		<a class="poplight" href="#?w=700" rel="email_popup">Email Contact</a>&nbsp;|&nbsp;
		<a href="https://mail.google.com/mail/?view=cm&fs=1&to=<?=$prop['CLIENT_EMAIL']?>" target="_blank">Gmail</a>&nbsp;|&nbsp;
		<a href="https://fathom.backagent.net/" target="_new">Backagent</a>&nbsp;|&nbsp;
		<a href="transactionCoordinator.php?lead_id=<?php echo $lead_id; ?>">Transaction Coordinator</a>&nbsp;|&nbsp;
		<a href="Pictures.php?lead_id=<?php echo $lead_id; ?>">Pictures</a>
	</th>
	<th width="15%" valign="bottom" style="text-align:right; width: 15%;">
		<?php if ($lead_id!=null) { ?>
				Date Created: <?=date("m/d/Y h:i A T", strtotime($prop['DATE_ADDED']))?><br />
				Last Update: <?=date("m/d/Y h:i A T", strtotime($prop['LAST_UPDATED']))?>
		<?php } ?>
	</th>
</tr>
<tr><td valign="top">
<table>
<tr><td colspan="2"><strong>Client Information</strong></td></tr>
<tr><td align="right"><div data-balloon-length="medium" data-balloon="Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s." data-balloon-pos="right" >Company Name:</div></td><td align="left">
<input name="company_name" size="30" value="<?=stripslashes($prop['COMPANY_NAME'])?>" /></td></tr>

<tr><td align="right"><div data-balloon-length="medium" data-balloon="Lorem Ipsum is simply dummy text of the printing and typesetting industry." data-balloon-pos="right" >Lead Type:</div></td><td align="left" colspan="2">
<select id="lead_type" name="lead_type" required>
<option value=""></option>
<option value="Buyer" <?php if($prop['LEAD_TYPE']=='Buyer') echo "selected=\"selected\""?>>Buyer</option>
<option value="Cash Buyer" <?php if($prop['LEAD_TYPE']=='Cash Buyer') echo "selected=\"selected\""?>>Cash Buyer</option>
<option value="Fathom Realtor" <?php if($prop['LEAD_TYPE']=='Fathom Realtor') echo "selected=\"selected\""?>>Fathom Realtor</option>
<option value="Inner Circle" <?php if($prop['LEAD_TYPE']=='Inner Circle') echo "selected=\"selected\""?>>Inner Circle</option>
<option value="Institutional Lender" <?php if($prop['LEAD_TYPE']=='Institutional Lender') echo "selected=\"selected\""?>>Institutional Lender</option>
<option value="Mortgage Broker" <?php if($prop['LEAD_TYPE']=='Mortgage Broker') echo "selected=\"selected\""?>>Mortgage Broker</option>
<option value="Private Lender" <?php if($prop['LEAD_TYPE']=='Private Lender') echo "selected=\"selected\""?>>Private Lender</option>
<option value="Property Manager" <?php if($prop['LEAD_TYPE']=='Property Manager') echo "selected=\"selected\""?>>Property Manager</option>
<option value="Realtor" <?php if($prop['LEAD_TYPE']=='Realtor') echo "selected=\"selected\""?>>Realtor</option>
<option value="Seller" <?php if($prop['LEAD_TYPE']=='Seller') echo "selected=\"selected\""?>>Seller</option>
<option value="Wholesaler" <?php if($prop['LEAD_TYPE']=='Wholesaler') echo "selected=\"selected\""?>>Wholesaler</option>
</select>
</td></tr>

<tr><td align="right"><div data-balloon-length="medium" data-balloon="Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s." data-balloon-pos="right" >Client Name:</div></td><td align="left">
<input name="title" size="2" value="<?=stripslashes($prop['TITLE'])?>" />
<input name="first_name" size="20" value="<?=stripslashes($prop['FIRST_NAME'])?>" required />
<input name="last_name" size="20" value="<?=stripslashes($prop['LAST_NAME'])?>" required />
</td></tr>

<tr><td align="right"><div data-balloon-length="medium" data-balloon="Lorem Ipsum is simply dummy text of the printing and typesetting industry." data-balloon-pos="right" >Client Email:</div></td><td align="left">
<input name="client_email" size="45" value="<?=stripslashes($prop['CLIENT_EMAIL'])?>" /></td></tr>

<tr><td align="right"><div data-balloon-length="medium" data-balloon="Lorem Ipsum has been the industry's standard dummy text ever since the 1500s." data-balloon-pos="right" >Position:</div></td><td align="left">
<input name="position" size="45" value="<?=stripslashes($prop['POSITION'])?>" /></td></tr>

<tr><td align="right"><div data-balloon-length="medium" data-balloon="Lorem Ipsum is simply dummy text of the printing and typesetting industry." data-balloon-pos="right" >Extra Client Name:</div></td><td align="left">
<input name="extra_title" size="2" value="<?=stripslashes($prop['EXTRA_TITLE'])?>" />
<input name="extra_first_name" size="20" value="<?=stripslashes($prop['EXTRA_FIRST_NAME'])?>" />
<input name="extra_last_name" size="20" value="<?=stripslashes($prop['EXTRA_LAST_NAME'])?>" />
</td></tr>

<tr><td align="right">Extra Client Email:</td><td align="left">
<input name="extra_client_email" size="45" value="<?=stripslashes($prop['EXTRA_CLIENT_EMAIL'])?>" /></td></tr>

<tr><td align="right">Web Site:</td><td align="left">
<input name="website" size="45" value="<?=stripslashes($prop['WEBSITE'])?>" /></td></tr>

<tr><td align="right">Home/Office Phone:</td><td align="left">
<input type="tel" name="office_phone" size="15" value="<?=stripslashes($prop['OFFICE_PHONE'])?>" onblur="formatPhoneNumber(this);" /></td></tr>

<tr><td align="right">Cell Phone:</td><td align="left">
<input type="tel" name="cell_phone" size="15" value="<?=stripslashes($prop['CELL_PHONE'])?>" onblur="formatPhoneNumber(this);" /></td></tr>

<tr><td align="right">Extra Client Phone:</td><td align="left">
<input type="tel" name="other_phone" size="15" value="<?=stripslashes($prop['OTHER_PHONE'])?>" onblur="formatPhoneNumber(this);" /></td></tr>

<tr><td align="right">Fax:</td><td align="left">
<input type="tel" name="fax" size="15" value="<?=stripslashes($prop['FAX'])?>" onblur="formatPhoneNumber(this);" /></td></tr>

<tr><td align="right">Address1:</td><td align="left">
<input name="address_1" size="30" value="<?=stripslashes($prop['ADDRESS_1'])?>" /></td></tr>

<tr><td align="right">Address2:</td><td align="left">
<input name="address_2" size="30" value="<?=stripslashes($prop['ADDRESS_2'])?>" /></td></tr>

<tr><td align="right">City:</td><td align="left">
<input name="city" size="30" value="<?=stripslashes($prop['CITY'])?>" /></td></tr>

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
<option value="<?=$row['ABBREV']?>" <?php if($row['ABBREV']==$prop['STATE']) echo "selected=\"selected\""?>><?=$row['ABBREV']?></option>
<?php
		}
?>
</select>
</td></tr>

<tr><td align="right">Zip Code:</td><td align="left">
<input name="zip" size="10" value="<?=stripslashes($prop['ZIP'])?>" /></td></tr>

<tr><td align="right">Owners On Title:</td><td align="left">
<input name="owners_on_title" size="60" value="<?=stripslashes($prop['OWNERS_ON_TITLE'])?>" /></td></tr>

<tr>
<td align="right">2nd Chance Program:</td>
<td align="left">
<select name="second_chance">
<option value=""></option>
<option value="No" <?php if($prop['SECOND_CHANCE']=="No") echo "selected=\"selected\""?>>No</option>
<option value="Yes" <?php if($prop['SECOND_CHANCE']=="Yes") echo "selected=\"selected\""?>>Yes</option>
<option value="Both" <?php if($prop['SECOND_CHANCE']=="Both") echo "selected=\"selected\""?>>Both</option>
</select>
</td>
</tr>

<tr <?php if ($session->isTATAgent() || $session->isSHSAgent()) echo "style=\"display:none\""; ?>>
<td colspan="2"><strong>Finance Information</strong></td>
</tr>

<tr <?php if ($session->isTATAgent() || $session->isSHSAgent()) echo "style=\"display:none\""; ?>>
<td align="right">Funds For Purchase:</td>
<td align="left"><input name="funds_for_purchase" size="15" value="<?=$prop['FUNDS_FOR_PURCHASE']?>" /> (No Commas)</td>
</tr>

<tr <?php if ($session->isTATAgent() || $session->isSHSAgent()) echo "style=\"display:none\""; ?>>
<td align="right" valign="top">Financing Available:</td>
<td align="left">
<input type="checkbox" name="financing_available[]" value="Hard Money" <?php if(strpos($prop['FINANCING_AVAILABLE'], "Hard Money") !== FALSE) echo "checked";?> />Hard Money<br />
<input type="checkbox" name="financing_available[]" value="Soft Money" <?php if(strpos($prop['FINANCING_AVAILABLE'], "Soft Money") !== FALSE) echo "checked";?> />Soft Money<br />
<input type="checkbox" name="financing_available[]" value="Cash" <?php if(strpos($prop['FINANCING_AVAILABLE'], "Cash") !== FALSE) echo "checked";?> />Cash<br />
<input type="checkbox" name="financing_available[]" value="Traditional" <?php if(strpos($prop['FINANCING_AVAILABLE'], "Traditional") !== FALSE) echo "checked";?> />Traditional<br />
<input type="checkbox" name="financing_available[]" value="LOC" <?php if(strpos($prop['FINANCING_AVAILABLE'], "LOC") !== FALSE) echo "checked";?> />LOC<br />
</td>
</tr>

<tr><td colspan="2"><strong>Property Information</strong></td></tr>
<tr>
<td align="right">Property Type:</td>
<td align="left">
<select name="property_type" required>
<option value=""></option>
<option value="Single Family" <?php if($prop['PROPERTY_TYPE']=="Single Family" || $lead_id==null) echo "selected=\"selected\"";?>>Single Family</option>
<option value="Townhome/Condo" <?php if($prop['PROPERTY_TYPE']=="Townhome/Condo") echo "selected=\"selected\"";?>>Townhome/Condo</option>
<option value="Duplex" <?php if($prop['PROPERTY_TYPE']=="Duplex") echo "selected=\"selected\"";?>>Duplex</option>
<option value="Multi Family" <?php if($prop['PROPERTY_TYPE']=="Multi Family") echo "selected=\"selected\"";?>>Multi Family</option>
<option value="Land" <?php if($prop['PROPERTY_TYPE']=="Land") echo "selected=\"selected\"";?>>Land</option>
<option value="Listing" <?php if($prop['PROPERTY_TYPE']=="Listing") echo "selected=\"selected\"";?>>Listing</option>
<option value="TAT Agent" <?php if($prop['PROPERTY_TYPE']=="TAT Agent") echo "selected=\"selected\"";?>>TAT Agent</option>
<option value="SHS Agent" <?php if($prop['PROPERTY_TYPE']=="SHS Agent") echo "selected=\"selected\"";?>>SHS Agent</option>
<option value="PLV" <?php if($prop['PROPERTY_TYPE']=="PLV") echo "selected=\"selected\"";?>>PLV</option>
</select>
</td>
</tr>

<tr><td align="right">Year Built:</td><td align="left">
<input id="year_built" name="year_built" size="5" value="<?=stripslashes($prop['YEAR_BUILT'])?>" /></td></tr>

<tr><td align="right">Square Feet:</td><td align="left">
<input id="square_feet" name="square_feet" pattern="\d*" size="10" value="<?=stripslashes($prop['SQUARE_FEET'])?>" /></td></tr>

<tr>
<td align="right">Garage Type:</td>
<td align="left">
<select name="garage_type">
<option value=""></option>
<option value="Attached" <?php if($prop['GARAGE_TYPE']=="Attached") echo "selected=\"selected\""?>>Attached</option>
<option value="Detatched" <?php if($prop['GARAGE_TYPE']=="Detatched") echo "selected=\"selected\""?>>Detatched</option>
</select>
</td>
</tr>

<tr>
<td align="right">Garages:</td>
<td align="left">
<select name="garages">
<option value="0" <?php if($prop['GARAGES']==0) echo "selected=\"selected\""?>>0</option>
<option value="1" <?php if($prop['GARAGES']==1) echo "selected=\"selected\""?>>1</option>
<option value="2" <?php if($prop['GARAGES']==2) echo "selected=\"selected\""?>>2</option>
<option value="3" <?php if($prop['GARAGES']==3) echo "selected=\"selected\""?>>3</option>
</select>
</td>
</tr>

<tr>
<td align="right">Converted Garage:</td>
<td align="left">
<select name="garage_converted">
<option value=""></option>
<option value="No" <?php if($prop['GARAGE_CONVERTED']=="No") echo "selected=\"selected\""?>>No</option>
<option value="Yes" <?php if($prop['GARAGE_CONVERTED']=="Yes") echo "selected=\"selected\""?>>Yes</option>
</select>
</td>
</tr>

<tr>
<td align="right">Bedrooms:</td>
<td align="left">
<select name="bedrooms">
<option value="0" <?php if($prop['BEDROOMS']==0) echo "selected=\"selected\""?>>0</option>
<option value="1" <?php if($prop['BEDROOMS']==1) echo "selected=\"selected\""?>>1</option>
<option value="2" <?php if($prop['BEDROOMS']==2) echo "selected=\"selected\""?>>2</option>
<option value="3" <?php if($prop['BEDROOMS']==3) echo "selected=\"selected\""?>>3</option>
<option value="4" <?php if($prop['BEDROOMS']==4) echo "selected=\"selected\""?>>4</option>
<option value="5" <?php if($prop['BEDROOMS']==5) echo "selected=\"selected\""?>>5</option>
</select>
</td>
</tr>

<tr>
<td align="right">Bathrooms:</td>
<td align="left">
<select id="bathrooms" name="bathrooms">
<option value="0" <?php if($prop['BATHROOMS']==0) echo "selected=\"selected\""?>>0</option>
<option value="1" <?php if($prop['BATHROOMS']==1) echo "selected=\"selected\""?>>1</option>
<option value="2" <?php if($prop['BATHROOMS']==2) echo "selected=\"selected\""?>>2</option>
<option value="3" <?php if($prop['BATHROOMS']==3) echo "selected=\"selected\""?>>3</option>
<option value="4" <?php if($prop['BATHROOMS']==4) echo "selected=\"selected\""?>>4</option>
<option value="5" <?php if($prop['BATHROOMS']==5) echo "selected=\"selected\""?>>5</option>
</select>
</td>
</tr>

<tr>
<td align="right">Stories:</td>
<td align="left">
<select name="stories">
<option value="0" <?php if($prop['STORIES']==0) echo "selected=\"selected\""?>>0</option>
<option value="1" <?php if($prop['STORIES']==1) echo "selected=\"selected\""?>>1</option>
<option value="2" <?php if($prop['STORIES']==2) echo "selected=\"selected\""?>>2</option>
</select>
</td>
</tr>

<tr>
<td align="right">Pool:</td>
<td align="left">
<select name="pool">
<option value="No" <?php if($prop['POOL']=="No") echo "selected=\"selected\""?>>No</option>
<option value="Yes" <?php if($prop['POOL']=="Yes") echo "selected=\"selected\""?>>Yes</option>
</select>
</td>
</tr>

<tr>
<td align="right">Pool Condition:</td>
<td align="left">
<select id="pool_condition" name="pool_condition">
<option value=""></option>
<option value="Good" <?php if($prop['POOL_CONDITION']=="Good") echo "selected=\"selected\""?>>Good</option>
<option value="Bad" <?php if($prop['POOL_CONDITION']=="Bad") echo "selected=\"selected\""?>>Bad</option>
</select>
</td>
</tr>

<tr>
<td align="right">Roof Age:</td>
<td align="left">
<select id="roof_age" name="roof_age">
<option value="1" <?php if($prop['ROOF_AGE']=='1') echo "selected=\"selected\""?>>1</option>
<option value="2" <?php if($prop['ROOF_AGE']=='2') echo "selected=\"selected\""?>>2</option>
<option value="3" <?php if($prop['ROOF_AGE']=='3') echo "selected=\"selected\""?>>3</option>
<option value="4+" <?php if($prop['ROOF_AGE']=='4+') echo "selected=\"selected\""?>>4+</option>
</select>
</td>
</tr>

<tr>
<td align="right">HVAC Age:</td>
<td align="left">
<select id="hvac_age" name="hvac_age">
<option value="0-4" <?php if($prop['HVAC_AGE']=='0-4') echo "selected=\"selected\""?>>0-4</option>
<option value="5-9" <?php if($prop['HVAC_AGE']=='5-9') echo "selected=\"selected\""?>>5-9</option>
<option value="10+" <?php if($prop['HVAC_AGE']=='10+') echo "selected=\"selected\""?>>10+</option>
</select>
</td>
</tr>

<tr>
<td align="right">Need Foundation Repair:</td>
<td align="left">
<select id="need_foundation_repair" name="need_foundation_repair">
<option value="No" <?php if($prop['NEED_FOUNDATION_REPAIR']=="No") echo "selected=\"selected\""?>>No</option>
<option value="Yes" <?php if($prop['NEED_FOUNDATION_REPAIR']=="Yes") echo "selected=\"selected\""?>>Yes</option>
</select>
</td>
</tr>

<tr><td align="right">Kitchen Cabinet Age/Type:</td><td align="left">
<textarea name="cabinet_type" style="height:80px;width:310px;"><?=stripslashes($prop['CABINET_TYPE'])?></textarea></td></tr>

<tr><td align="right">Counter Top Age/Type:</td><td align="left">
<textarea name="counter_type" style="height:80px;width:310px;"><?=stripslashes($prop['COUNTER_TYPE'])?></textarea></td></tr>

<tr><td align="right">Flooring Age/Type:</td><td align="left">
<textarea name="flooring_type" style="height:80px;width:310px;"><?=stripslashes($prop['FLOORING_TYPE'])?></textarea></td></tr>

<tr><td align="right">Master Bath Age:</td><td align="left">
<input name="master_bath_age" size="30" value="<?=stripslashes($prop['MASTER_BATH_AGE'])?>" /></td></tr>

<tr><td align="right">Half Bath Age:</td><td align="left">
<input name="half_bath_age" size="30" value="<?=stripslashes($prop['HALF_BATH_AGE'])?>" /></td></tr>

<tr><td align="right">Upgrades:</td><td align="left">
<input name="upgrades" size="60" value="<?=stripslashes($prop['UPGRADES'])?>" /></td></tr>

<tr>
<td align="right">Insurance:</td>
<td align="left">
<select id="insurance" name="insurance">
<option value="No" <?php if($prop['INSURANCE']=="No") echo "selected=\"selected\""?>>No</option>
<option value="Yes" <?php if($prop['INSURANCE']=="Yes") echo "selected=\"selected\""?>>Yes</option>
</select>
</td>
</tr>

<tr><td align="right">How Long Have You Owned:</td><td align="left">
<input name="how_long_owned" size="30" value="<?=stripslashes($prop['HOW_LONG_OWNED'])?>" /></td></tr>

<tr>
<td align="right">Owner Occupied:</td>
<td align="left">
<select name="owner_occupied">
<option value=""></option>
<option value="Yes" <?php if($prop['OWNER_OCCUPIED']=="No") echo "selected=\"selected\""?>>No</option>
<option value="No" <?php if($prop['OWNER_OCCUPIED']=="Yes") echo "selected=\"selected\""?>>Yes</option>
</select>
</td>
</tr>

<tr>
<td align="right">Rented:</td>
<td align="left">
<select name="rented">
<option value=""></option>
<option value="Yes" <?php if($prop['RENTED']=="No") echo "selected=\"selected\""?>>No</option>
<option value="No" <?php if($prop['RENTED']=="Yes") echo "selected=\"selected\""?>>Yes</option>
</select>
</td>
</tr>

<tr>
<td align="right">Rent Amount:</td>
<td align="left"><input name="rent_amt" size="15" value="<?=$prop['RENT_AMT']?>" /> (No Commas)</td>
</tr>

<tr><td align="right">Term:</td><td align="left">
<input name="term" size="30" value="<?=stripslashes($prop['TERM'])?>" /></td></tr>

<tr>
<td align="right">Deposit:</td>
<td align="left"><input name="deposit" size="15" value="<?=$prop['DEPOSIT']?>" /> (No Commas)</td>
</tr>

<tr>
<td align="right">Move Date:</td>
<td align="left">
<input name="move_date" id="move_date" size="10" value="<?php if ($prop['MOVE_DATE']=="0000-00-00") echo ""; else echo $prop['MOVE_DATE'];?>" />
<script type="text/javascript">
	var mv_cal = new tcal ({
		'controlname': 'move_date'
	});
</script>
</td>

<tr>
<td align="right">Listed:</td>
<td align="left">
<select name="listed">
<option value=""></option>
<option value="Yes" <?php if($prop['LISTED']=="No") echo "selected=\"selected\""?>>No</option>
<option value="No" <?php if($prop['LISTED']=="Yes") echo "selected=\"selected\""?>>Yes</option>
</select>
</td>
</tr>

<tr><td align="right">How Long Listed:</td><td align="left">
<input name="how_long" size="30" value="<?=stripslashes($prop['HOW_LONG'])?>" /></td></tr>

<tr>
<td align="right">Listing Price:</td>
<td align="left"><input name="listing_price" size="15" value="<?=$prop['LISTING_PRICE']?>" /> (No Commas)</td>
</tr>

<tr>
<td align="right">Offer Price:</td>
<td align="left"><input name="offer_price" size="15" value="<?=$prop['OFFER_PRICE']?>" /> (No Commas)</td>
</tr>

<tr>
<td align="right">ARV:</td>
<td align="left"><input id="arv" name="arv" size="15" value="<?=$prop['ARV']?>" /> (No Commas)</td>
</tr>

<tr>
<td align="right">MANAGER ARV:</td>
<td align="left">
	<?php 
		$field_disabled = "";
		if( !$session->isMaster() ){
			$field_disabled = 'readonly="readonly" disabled="disabled"';
		}
	?>
	<input id="manager_arv" name="manager_arv" <?php echo $field_disabled; ?> size="15" value="<?=$prop['MANAGER_ARV']?>" /> (No Commas)
</td>
</tr>

<tr>
<td align="right">As-Is Price:</td>
<td align="left"><input id="as_is_price" name="as_is_price" size="15" value="<?=$prop['AS_IS_PRICE']?>" /> (No Commas)</td>
</tr>

<tr>
<td align="right">Manager As-Is Price:</td>
<td align="left">
	<?php 
		$field_disabled = "";
		if( !$session->isMaster() ){
			$field_disabled = 'readonly="readonly" disabled="disabled"';
		}
	?>
	<input id="manager_as_is_price" name="manager_as_is_price" <?php echo $field_disabled; ?> size="15" value="<?=$prop['MANAGER_AS_IS_PRICE']?>" /> (No Commas)
</td>
</tr>

<tr>
<td align="right">Current Mortgage:</td>
<td align="left"><input name="current_mortgage" size="15" value="<?=$prop['CURRENT_MORTGAGE']?>" /> (No Commas)</td>
</tr>

<tr>
<td align="right">Current Payments:</td>
<td align="left">
<select name="current_payments">
<option value=""></option>
<option value="Yes" <?php if($prop['CURRENT_PAYMENTS']=="No") echo "selected=\"selected\""?>>No</option>
<option value="No" <?php if($prop['CURRENT_PAYMENTS']=="Yes") echo "selected=\"selected\""?>>Yes</option>
</select>
</td>
</tr>

<tr><td colspan="2"><strong>Motivation & Price</strong></td></tr>
<tr><td align="right">Reason For Moving:</td><td align="left">
<input name="moving_reason" size="30" value="<?=stripslashes($prop['MOVING_REASON'])?>" /></td></tr>

<tr><td align="right">Time Frame To Sell:</td><td align="left">
<input name="time_frame_sell" size="30" value="<?=stripslashes($prop['TIME_FRAME_SELL'])?>" /></td></tr>

<tr>
<td align="right">Asking Price:</td>
<td align="left"><input id="asking_price" name="asking_price" size="15" value="<?=$prop['ASKING_PRICE']?>" /> (No Commas)</td>
</tr>

<tr><td align="right">Price Flexible:</td><td align="left">
<input name="price_flexible" size="30" value="<?=stripslashes($prop['PRICE_FLEXIBLE'])?>" /></td></tr>

<tr><td align="right">How Did You Establish That Number:</td><td align="left">
<input name="asking_price_reason" size="30" value="<?=stripslashes($prop['ASKING_PRICE_REASON'])?>" /></td></tr>

<tr><td align="right">Cash and Quick Close<br />What’s the best you can do:</td><td align="left">
<input name="cash_quick_close" size="30" value="<?=stripslashes($prop['CASH_QUICK_CLOSE'])?>" /></td></tr>

<tr><td align="right">Any Better than that:</td><td align="left">
<input name="any_better" size="30" value="<?=stripslashes($prop['ANY_BETTER'])?>" /></td></tr>

<tr><td align="right">Doesn't sell, now what:</td><td align="left">
<input name="doesnt_sell" size="30" value="<?=stripslashes($prop['DOESNT_SELL'])?>" /></td></tr>

<tr><td colspan="2"><strong>Other Information</strong></td></tr>
<tr>
<td align="right">Backside Contract:</td>
<td align="left">
<select name="backside_contract">
<option value="No" <?php if($prop['BACKSIDE_CONTRACT']=="No") echo "selected=\"selected\""?>>No</option>
<option value="Yes" <?php if($prop['BACKSIDE_CONTRACT']=="Yes") echo "selected=\"selected\""?>>Yes</option>
</select>
</td>
</tr>
<input type="hidden" name="exit_strategy" id="exit_strategy" value="">
<!-- <tr>
<td align="right">Exit Strategy:</td>
<td align="left">
<select name="exit_strategy">
<option value=""></option>
<option value="Flip" <?php if($prop['EXIT_STRATEGY']=="Flip") echo "selected=\"selected\"";?>>Flip</option>
<option value="Wholesale" <?php if($prop['EXIT_STRATEGY']=="Wholesale") echo "selected=\"selected\"";?>>Wholesale</option>
<option value="Assignment" <?php if($prop['EXIT_STRATEGY']=="Assignment") echo "selected=\"selected\"";?>>Assignment</option>
<option value="Hedge Fund" <?php if($prop['EXIT_STRATEGY']=="Hedge Fund") echo "selected=\"selected\"";?>>Hedge Fund</option>
<option value="Rental" <?php if($prop['EXIT_STRATEGY']=="Rental") echo "selected=\"selected\"";?>>Rental</option>
<option value="Listing" <?php if($prop['EXIT_STRATEGY']=="Listing") echo "selected=\"selected\"";?>>Listing</option>
</select>
</td>
</tr> -->

<tr><td align="right">Need Lender:</td><td align="left">
<select name="need_lender">
<option value="No" <?php if($prop['NEED_LENDER']=="No") echo "selected=\"selected\""?>>No</option>
<option value="Yes" <?php if($prop['NEED_LENDER']=="Yes") echo "selected=\"selected\""?>>Yes</option>
</select>
</td></tr>

<tr>
<td align="right">Closer:</td>
<td align="left"><input name="closer" size="45" value="<?=stripslashes($prop['CLOSER'])?>" /></td>
</tr>

<tr>
<td align="right">Title Company:</td>
<td align="left"><input name="title_company" size="45" value="<?=stripslashes($prop['TITLE_COMPANY'])?>" /></td>
</tr>

<tr><td colspan="2"><strong>Search Information</strong></td></tr>
<tr>
<td align="right">Search City:</td>
<td align="left">
<input name="search_city" size="30" value="<?=stripslashes($prop['SEARCH_CITY'])?>" />
<select name="search_state">
<option></option>
<?php
		$result = $mysqli->query("SELECT * FROM states") or die(mysql_error());
		while($row = mysqli_fetch_array($result)){
			foreach($row AS $key => $value) {
				$row[$key] = stripslashes($value);
			}
?>
<option value="<?=$row['ABBREV']?>" <?php if($row['ABBREV']==$prop['SEARCH_STATE']) echo "selected=\"selected\""?>><?=$row['ABBREV']?></option>
<?php
		}
?>
</select>
</td>
</tr>

<tr>
<td align="right" valign="top">Area Of Interest:</td>
<td><textarea wrap="virtual" name="area_of_interest" cols="50" rows="3"><?=stripslashes($prop['AREA_OF_INTEREST'])?></textarea></td>
</tr>

</table>
</td>
<td valign="top" colspan="2">
<table>

<tr>
<td align="right">Lead Source:</td>
<td align="left" colspan="3">
<select name="affiliate_id" required>
<option value=""></option>
<?php
		$result = $mysqli->query("SELECT * FROM affiliates ORDER BY COMPANY_NAME ASC")	or die(mysql_error());
		while($row = mysqli_fetch_array($result)){
			foreach($row AS $key => $value) {
				$row[$key] = stripslashes($value);
			}
?>
<option value="<?=$row['AFFILIATE_ID']?>" <?php if($row['AFFILIATE_ID']==$prop['AFFILIATE_ID'] || ($prop==null && $row['AFFILIATE_ID']==50)) echo "selected=\"selected\""?>><?=$row['COMPANY_NAME']?></option>
<?php
		}
?>
</select>
</td>
</tr>
<tr>
<td align="right">Predicted Sales Price:</td>
<td align="left"><input name="predicted_amt" size="15" value="<?=$prop['PREDICTED_AMT']?>" /></td>
<td align="right">Lead Owner:</td>
<td align="left" colspan="2">
<select name="username">
<option value="Not Assigned">Not Assigned</option>
<?php
		$result = $mysqli->query("SELECT * FROM users WHERE fullname!='' AND userlevel > 0 ORDER BY fullname ASC") or die(mysql_error());
		while($row = mysqli_fetch_array($result)){
  		foreach($row AS $key => $value) {
				$row[$key] = stripslashes($value);
			}
?>
<option value="<?=$row['username']?>" <?php if($row['username'] == $prop['USERNAME'] || ($lead_id == null && $row['username'] == $session->username)) echo "selected=\"selected\""?>><?=$row['fullname']?></option>
<?php
		}
?>
</select>
</td>
</tr>

<tr>
<td align="right">Forecast Chance:</td>
<td align="left">
<select name="forecast_chance" id="forecast_chance">
<option value=""></option>
<?php
if ($session->isAdmin() || $session->isMaster()) {
?>
<option value="Hot Lead (0%)" <?php if($prop['FORECAST_CHANCE']=="Hot Lead (0%)") echo "selected=\"selected\"";?>>Hot Lead (0%)</option>
<option value="Listing Appointment (30%)" <?php if($prop['FORECAST_CHANCE']=="Listing Appointment (30%)") echo "selected=\"selected\"";?>>Listing Appointment (30%)</option>
<option value="Showing (30%)" <?php if($prop['FORECAST_CHANCE']=="Showing (30%)") echo "selected=\"selected\"";?>>Showing (30%)</option>
<option value="Docusign Sent (60%)" <?php if($prop['FORECAST_CHANCE']=="Docusign Sent (60%)") echo "selected=\"selected\"";?>>Docusign Sent (60%)</option>
<option value="Active Listing (70%)" <?php if($prop['FORECAST_CHANCE']=="Active Listing (70%)") echo "selected=\"selected\"";?>>Active Listing (70%)</option>
<option value="Offer (75%)" <?php if($prop['FORECAST_CHANCE']=="Offer (75%)") echo "selected=\"selected\"";?>>Offer (75%)</option>
<option value="Executed (75%)" <?php if($prop['FORECAST_CHANCE']=="Executed (75%)") echo "selected=\"selected\"";?>>Executed (75%)</option>
<option value="Pending (75%)" <?php if($prop['FORECAST_CHANCE']=="Pending (75%)") echo "selected=\"selected\"";?>>Pending (75%)</option>
<option value="Exit Confirmed (95%)" <?php if($prop['FORECAST_CHANCE']=="Exit Confirmed (95%)") echo "selected=\"selected\"";?>>Exit Confirmed (95%)</option>
<option value="Closed (100%)" <?php if($prop['FORECAST_CHANCE']=="Closed (100%)") echo "selected=\"selected\"";?>>Closed (100%)</option>
<?php
}
if ($session->isAssetManager()) {
?>
<option value="Hot Lead (0%)" <?php if($prop['FORECAST_CHANCE']=="Hot Lead (0%)") echo "selected=\"selected\"";?>>Hot Lead (0%)</option>
<option value="Showing (30%)" <?php if($prop['FORECAST_CHANCE']=="Showing (30%)") echo "selected=\"selected\"";?>>Showing (30%)</option>
<option value="Docusign Sent (60%)" <?php if($prop['FORECAST_CHANCE']=="Docusign Sent (60%)") echo "selected=\"selected\"";?>>Docusign Sent (60%)</option>
<option value="Offer (75%)" <?php if($prop['FORECAST_CHANCE']=="Offer (75%)") echo "selected=\"selected\"";?>>Offer (75%)</option>
<option value="Executed (75%)" <?php if($prop['FORECAST_CHANCE']=="Executed (75%)") echo "selected=\"selected\"";?>>Executed (75%)</option>
<option value="Pending (75%)" <?php if($prop['FORECAST_CHANCE']=="Pending (75%)") echo "selected=\"selected\"";?>>Pending (75%)</option>
<option value="Closed (100%)" <?php if($prop['FORECAST_CHANCE']=="Closed (100%)") echo "selected=\"selected\"";?>>Closed (100%)</option>
<?php
}
if ($session->isTATAgent()) {
?>
<option value="Hot Lead (0%)" <?php if($prop['FORECAST_CHANCE']=="Hot Lead (0%)") echo "selected=\"selected\"";?>>Hot Lead (0%)</option>
<option value="Listing Appointment (30%)" <?php if($prop['FORECAST_CHANCE']=="Listing Appointment (30%)") echo "selected=\"selected\"";?>>Listing Appointment (30%)</option>
<option value="Showing (30%)" <?php if($prop['FORECAST_CHANCE']=="Showing (30%)") echo "selected=\"selected\"";?>>Showing (30%)</option>
<option value="Active Listing (70%)" <?php if($prop['FORECAST_CHANCE']=="Active Listing (70%)") echo "selected=\"selected\"";?>>Active Listing (70%)</option>
<option value="Offer (75%)" <?php if($prop['FORECAST_CHANCE']=="Offer (75%)") echo "selected=\"selected\"";?>>Offer (75%)</option>
<option value="Executed (75%)" <?php if($prop['FORECAST_CHANCE']=="Executed (75%)") echo "selected=\"selected\"";?>>Executed (75%)</option>
<option value="Pending (75%)" <?php if($prop['FORECAST_CHANCE']=="Pending (75%)") echo "selected=\"selected\"";?>>Pending (75%)</option>
<option value="Closed (100%)" <?php if($prop['FORECAST_CHANCE']=="Closed (100%)") echo "selected=\"selected\"";?>>Closed (100%)</option>
<?php
}
if ($session->isSHSAgent()) {
?>
<option value="Hot Lead (0%)" <?php if($prop['FORECAST_CHANCE']=="Hot Lead (0%)") echo "selected=\"selected\"";?>>Hot Lead (0%)</option>
<option value="Docusign Sent (60%)" <?php if($prop['FORECAST_CHANCE']=="Docusign Sent (60%)") echo "selected=\"selected\"";?>>Docusign Sent (60%)</option>
<option value="Executed (75%)" <?php if($prop['FORECAST_CHANCE']=="Executed (75%)") echo "selected=\"selected\"";?>>Executed (75%)</option>
<option value="Pending (75%)" <?php if($prop['FORECAST_CHANCE']=="Pending (75%)") echo "selected=\"selected\"";?>>Pending (75%)</option>
<option value="Exit Confirmed (95%)" <?php if($prop['FORECAST_CHANCE']=="Exit Confirmed (95%)") echo "selected=\"selected\"";?>>Exit Confirmed (95%)</option>
<option value="Closed (100%)" <?php if($prop['FORECAST_CHANCE']=="Closed (100%)") echo "selected=\"selected\"";?>>Closed (100%)</option
<?php } ?>
</select>
</td>
<td align="right">Lead Strength:</td>
<td align="left">
<select name="priority" id="priority" class="<?=$prop['PRIORITY']?>" onchange="this.setAttribute('class', this.value)" required>
<option value=""></option>
<option value="NoContact" <?php if($prop['PRIORITY']=="NoContact") echo "selected=\"selected\"";?>>No Contact</option>
<option value="Weak" <?php if($prop['PRIORITY']=="Weak") echo "selected=\"selected\"";?>>Weak</option>
<option value="Mild" <?php if($prop['PRIORITY']=="Mild") echo "selected=\"selected\"";?>>Mild</option>
<option value="Strong" <?php if($prop['PRIORITY']=="Strong") echo "selected=\"selected\"";?>>Strong</option>
<option value="Hot" <?php if($prop['PRIORITY']=="Hot") echo "selected=\"selected\"";?>>Hot</option>
</select>
</td>
</tr>

<tr>
<td align="right">Forecast Value:</td>
<td align="left">
<?
if ($prop['PREDICTED_AMT']!="") {
 $amt = (float) $prop['PREDICTED_AMT'];
 if ($prop['FORECAST_CHANCE']!="") {
    $chance = $prop['FORECAST_CHANCE'];
    $percent = 0;
    if ($chance=="Hot Lead (0%)") $percent = .00;
    if ($chance=="Listing Appointment (30%)") $percent = .30;
    if ($chance=="Showing (30%)") $percent = .30;
    if ($chance=="Docusign Sent (60%)") $percent = .60;
    if ($chance=="Active Listing (70%)") $percent = .70;
    if ($chance=="Offer (75%)") $percent = .75;
    if ($chance=="Executed (75%)") $percent = .75;
    if ($chance=="Pending (75%)") $percent = .75;
    if ($chance=="Exit Confirmed (95%)") $percent = .95;
    if ($chance=="Closed (100%)") $percent = 1;
 }
 echo "$ " . number_format($amt * $percent, 2);
} else {
	echo "N/A";
}
?>
</td>
<td align="right">Status:</td>
<td align="left">
<select name="status" required>
<option value=""></option>
<option value="New" <?php if($prop['STATUS']=="New") echo "selected=\"selected\"";?>>New</option>
<option value="Live" <?php if($prop['STATUS']=="Live") echo "selected=\"selected\"";?>>Live</option>
<option value="Dead" <?php if($prop['STATUS']=="Dead") echo "selected=\"selected\"";?>>Dead</option>
<option value="On Hold" <?php if($prop['STATUS']=="On Hold") echo "selected=\"selected\"";?>>On Hold</option>
<option value="Unqualified" <?php if($prop['STATUS']=="Unqualified") echo "selected=\"selected\"";?>>Unqualified</option>
<option value="Terminated" <?php if($prop['STATUS']=="Terminated") echo "selected=\"selected\"";?>>Terminated</option>
<option value="Closed" <?php if($prop['STATUS']=="Closed") echo "selected=\"selected\"";?>>Closed</option>
</select>
</td>
</tr>

<tr>
<td align="right">Earnest Receipt:</td>
<td align="left">
<select name="earnest_receipt">
<option value="No" <?php if($prop['EARNEST_RECEIPT']=="No") echo "selected=\"selected\""?>>No</option>
<option value="Yes" <?php if($prop['EARNEST_RECEIPT']=="Yes") echo "selected=\"selected\""?>>Yes</option>
</select>
</td>
<td align="right">Dead Reason:</td>
<td align="left">
<select name="dead_reason">
<option value="N/A" <?php if($prop['DEAD_REASON']=="N/A") echo "selected=\"selected\"";?>>N/A</option>
<option value="No Contact Information" <?php if($prop['DEAD_REASON']=="No Contact Information") echo "selected=\"selected\"";?>>No Contact Information</option>
<option value="Unable To Reach Client" <?php if($prop['DEAD_REASON']=="Unable To Reach Client") echo "selected=\"selected\"";?>>Unable To Reach Client</option>
<option value="Not Moving from Current Office" <?php if($prop['DEAD_REASON']=="Not Moving from Current Office") echo "selected=\"selected\"";?>>Not Moving from Current Office</option>
<option value="Had to Dead - Not Known" <?php if($prop['DEAD_REASON']=="Had to Dead - Not Known") echo "selected=\"selected\"";?>>Had to Dead - Not Known</option>
<option value="Never connected" <?php if($prop['DEAD_REASON']=="Never connected") echo "selected=\"selected\"";?>>Never connected</option>
<option value="Went Sublet" <?php if($prop['DEAD_REASON']=="Went Sublet") echo "selected=\"selected\"";?>>Went Sublet</option>
<option value="Went Serviced - We don't Deal" <?php if(stripslashes($prop['DEAD_REASON'])=="Went Serviced - We don\'t Deal") echo "selected=\"selected\"";?>>Went Serviced - We don't Deal</option>
<option value="Went to a Rejected Provider" <?php if($prop['DEAD_REASON']=="Went to a Rejected Provider") echo "selected=\"selected\"";?>>Went to a Rejected Provider</option>
<option value="Already working with Broker" <?php if($prop['DEAD_REASON']=="Already working with Broker") echo "selected=\"selected\"";?>>Already working with Broker</option>
<option value="Took Space - We didn't Deal" <?php if(stripslashes($prop['DEAD_REASON'])=="Took Space - We didn\'t Deal") echo "selected=\"selected\"";?>>Took Space - We didn't Deal</option>
<option value="Storage Space Requirement" <?php if($prop['DEAD_REASON']=="Storage Space Requirement") echo "selected=\"selected\"";?>>Storage Space Requirement</option>
<option value="No Match - Budget" <?php if($prop['DEAD_REASON']=="No Match - Budget") echo "selected=\"selected\"";?>>No Match - Budget</option>
<option value="No Match - Too Small" <?php if($prop['DEAD_REASON']=="No Match - Too Small") echo "selected=\"selected\"";?>>No Match - Too Small</option>
<option value="Nothing in the Search Area" <?php if($prop['DEAD_REASON']=="Nothing in the Search Area") echo "selected=\"selected\"";?>>Nothing in the Search Area</option>
<option value="Project Cancelled" <?php if($prop['DEAD_REASON']=="Project Cancelled") echo "selected=\"selected\"";?>>Project Cancelled</option>
<option value="Did not win Contract" <?php if($prop['DEAD_REASON']=="Did not win Contract") echo "selected=\"selected\"";?>>Did not win Contract</option>
<option value="Duplicate - To Be Removed" <?php if($prop['DEAD_REASON']=="Duplicate - To Be Removed") echo "selected=\"selected\"";?>>Duplicate - To Be Removed</option>
<option value="Student" <?php if($prop['DEAD_REASON']=="Student") echo "selected=\"selected\"";?>>Student</option>
<option value="Affiliate Test" <?php if($prop['DEAD_REASON']=="Affiliate Test") echo "selected=\"selected\"";?>>Affiliate Test</option>
<option value="On Hold" <?php if($prop['DEAD_REASON']=="On Hold") echo "selected=\"selected\"";?>>On Hold</option>
<option value="Spam" <?php if($prop['DEAD_REASON']=="Spam") echo "selected=\"selected\"";?>>Spam</option>
<option value="Received From Another Source" <?php if($prop['DEAD_REASON']=="Received From Another Source") echo "selected=\"selected\"";?>>Received From Another Source</option>
<option value="Proposed Usage Not Allowed" <?php if($prop['DEAD_REASON']=="Proposed Usage Not Allowed") echo "selected=\"selected\"";?>>Proposed Usage Not Allowed</option>
<option value="Removed" <?php if($prop['DEAD_REASON']=="Removed") echo "selected=\"selected\"";?>>Removed</option>
</select>
</td>
</tr>

<tr>
<td align="right">Executed Date:</td>
<td align="left">
<input name="executed_date" id="executed_date" size="10" value="<?php if ($prop['EXECUTED_DATE']=="0000-00-00") echo ""; else echo $prop['EXECUTED_DATE'];?>" />
<script type="text/javascript">
	var pm_cal = new tcal ({
		'controlname': 'executed_date'
	});
</script>
</td>
<td align="right">Follow-Up Date:</td>
<td align="left" valign="top">
<input name="follow_up_date" id="follow_up_date" size="10" value="<?php if ($prop['FOLLOW_UP_DATE']=="0000-00-00") echo ""; else echo $prop['FOLLOW_UP_DATE'];?>" required />
<script type="text/javascript">
	var f_cal = new tcal ({
		'controlname': 'follow_up_date'
	});
</script>
</td>
</tr>

<tr>
<td align="right">End Of Option Date:</td>
<td align="left">
<input name="end_of_option" id="end_of_option" size="10" value="<?php if ($prop['END_OF_OPTION']=="0000-00-00") echo ""; else echo $prop['END_OF_OPTION'];?>" />
<script type="text/javascript">
	var pc_cal = new tcal ({
		'controlname': 'end_of_option'
	});
</script>
</td>
<td align="right">Follow-Up Time:</td>
<td alight="left">
<select name="follow_up_time" id="follow_up_time">
<option value=""></option>
<option value="06:00" <?php if ($prop['FOLLOW_UP_TIME'] == '06:00:00') echo 'selected=\'selected\'' ?>>6:00 AM</option>
<option value="06:15" <?php if ($prop['FOLLOW_UP_TIME'] == '06:15:00') echo 'selected=\'selected\'' ?>>6:15 AM</option>
<option value="06:30" <?php if ($prop['FOLLOW_UP_TIME'] == '06:30:00') echo 'selected=\'selected\'' ?>>6:30 AM</option>
<option value="06:45" <?php if ($prop['FOLLOW_UP_TIME'] == '06:45:00') echo 'selected=\'selected\'' ?>>6:45 AM</option>

<option value="07:00" <?php if ($prop['FOLLOW_UP_TIME'] == '07:00:00') echo 'selected=\'selected\'' ?>>7:00 AM</option>
<option value="07:15" <?php if ($prop['FOLLOW_UP_TIME'] == '07:15:00') echo 'selected=\'selected\'' ?>>7:15 AM</option>
<option value="07:30" <?php if ($prop['FOLLOW_UP_TIME'] == '07:30:00') echo 'selected=\'selected\'' ?>>7:30 AM</option>
<option value="07:45" <?php if ($prop['FOLLOW_UP_TIME'] == '07:45:00') echo 'selected=\'selected\'' ?>>7:45 AM</option>

<option value="08:00" <?php if ($prop['FOLLOW_UP_TIME'] == '08:00:00') echo 'selected=\'selected\'' ?>>8:00 AM</option>
<option value="08:15" <?php if ($prop['FOLLOW_UP_TIME'] == '08:15:00') echo 'selected=\'selected\'' ?>>8:15 AM</option>
<option value="08:30" <?php if ($prop['FOLLOW_UP_TIME'] == '08:30:00') echo 'selected=\'selected\'' ?>>8:30 AM</option>
<option value="08:45" <?php if ($prop['FOLLOW_UP_TIME'] == '08:45:00') echo 'selected=\'selected\'' ?>>8:45 AM</option>

<option value="09:00" <?php if ($prop['FOLLOW_UP_TIME'] == '09:00:00') echo 'selected=\'selected\'' ?>>9:00 AM</option>
<option value="09:15" <?php if ($prop['FOLLOW_UP_TIME'] == '09:15:00') echo 'selected=\'selected\'' ?>>9:15 AM</option>
<option value="09:30" <?php if ($prop['FOLLOW_UP_TIME'] == '09:30:00') echo 'selected=\'selected\'' ?>>9:30 AM</option>
<option value="09:45" <?php if ($prop['FOLLOW_UP_TIME'] == '09:45:00') echo 'selected=\'selected\'' ?>>9:45 AM</option>

<option value="10:00" <?php if ($prop['FOLLOW_UP_TIME'] == '10:00:00') echo 'selected=\'selected\'' ?>>10:00 AM</option>
<option value="10:15" <?php if ($prop['FOLLOW_UP_TIME'] == '10:15:00') echo 'selected=\'selected\'' ?>>10:15 AM</option>
<option value="10:30" <?php if ($prop['FOLLOW_UP_TIME'] == '10:30:00') echo 'selected=\'selected\'' ?>>10:30 AM</option>
<option value="10:45" <?php if ($prop['FOLLOW_UP_TIME'] == '10:45:00') echo 'selected=\'selected\'' ?>>10:45 AM</option>

<option value="11:00" <?php if ($prop['FOLLOW_UP_TIME'] == '11:00:00') echo 'selected=\'selected\'' ?>>11:00 AM</option>
<option value="11:15" <?php if ($prop['FOLLOW_UP_TIME'] == '11:15:00') echo 'selected=\'selected\'' ?>>11:15 AM</option>
<option value="11:30" <?php if ($prop['FOLLOW_UP_TIME'] == '11:30:00') echo 'selected=\'selected\'' ?>>11:30 AM</option>
<option value="11:45" <?php if ($prop['FOLLOW_UP_TIME'] == '11:45:00') echo 'selected=\'selected\'' ?>>11:45 AM</option>

<option value="12:00" <?php if ($prop['FOLLOW_UP_TIME'] == '12:00:00') echo 'selected=\'selected\'' ?>>12:00 PM</option>
<option value="12:15" <?php if ($prop['FOLLOW_UP_TIME'] == '12:15:00') echo 'selected=\'selected\'' ?>>12:15 PM</option>
<option value="12:30" <?php if ($prop['FOLLOW_UP_TIME'] == '12:30:00') echo 'selected=\'selected\'' ?>>12:30 PM</option>
<option value="12:45" <?php if ($prop['FOLLOW_UP_TIME'] == '12:45:00') echo 'selected=\'selected\'' ?>>12:45 PM</option>

<option value="13:00" <?php if ($prop['FOLLOW_UP_TIME'] == '13:00:00') echo 'selected=\'selected\'' ?>>1:00 PM</option>
<option value="13:15" <?php if ($prop['FOLLOW_UP_TIME'] == '13:15:00') echo 'selected=\'selected\'' ?>>1:15 PM</option>
<option value="13:30" <?php if ($prop['FOLLOW_UP_TIME'] == '13:30:00') echo 'selected=\'selected\'' ?>>1:30 PM</option>
<option value="13:45" <?php if ($prop['FOLLOW_UP_TIME'] == '13:45:00') echo 'selected=\'selected\'' ?>>1:45 PM</option>

<option value="14:00" <?php if ($prop['FOLLOW_UP_TIME'] == '14:00:00') echo 'selected=\'selected\'' ?>>2:00 PM</option>
<option value="14:15" <?php if ($prop['FOLLOW_UP_TIME'] == '14:15:00') echo 'selected=\'selected\'' ?>>2:15 PM</option>
<option value="14:30" <?php if ($prop['FOLLOW_UP_TIME'] == '14:30:00') echo 'selected=\'selected\'' ?>>2:30 PM</option>
<option value="14:45" <?php if ($prop['FOLLOW_UP_TIME'] == '14:45:00') echo 'selected=\'selected\'' ?>>2:45 PM</option>

<option value="15:00" <?php if ($prop['FOLLOW_UP_TIME'] == '15:00:00') echo 'selected=\'selected\'' ?>>3:00 PM</option>
<option value="15:15" <?php if ($prop['FOLLOW_UP_TIME'] == '15:15:00') echo 'selected=\'selected\'' ?>>3:15 PM</option>
<option value="15:30" <?php if ($prop['FOLLOW_UP_TIME'] == '15:30:00') echo 'selected=\'selected\'' ?>>3:30 PM</option>
<option value="15:45" <?php if ($prop['FOLLOW_UP_TIME'] == '15:45:00') echo 'selected=\'selected\'' ?>>3:45 PM</option>

<option value="16:00" <?php if ($prop['FOLLOW_UP_TIME'] == '16:00:00') echo 'selected=\'selected\'' ?>>4:00 PM</option>
<option value="16:15" <?php if ($prop['FOLLOW_UP_TIME'] == '16:15:00') echo 'selected=\'selected\'' ?>>4:15 PM</option>
<option value="16:30" <?php if ($prop['FOLLOW_UP_TIME'] == '16:30:00') echo 'selected=\'selected\'' ?>>4:30 PM</option>
<option value="16:45" <?php if ($prop['FOLLOW_UP_TIME'] == '16:45:00') echo 'selected=\'selected\'' ?>>4:45 PM</option>

<option value="17:00" <?php if ($prop['FOLLOW_UP_TIME'] == '17:00:00') echo 'selected=\'selected\'' ?>>5:00 PM</option>
<option value="17:15" <?php if ($prop['FOLLOW_UP_TIME'] == '17:15:00') echo 'selected=\'selected\'' ?>>5:15 PM</option>
<option value="17:30" <?php if ($prop['FOLLOW_UP_TIME'] == '17:30:00') echo 'selected=\'selected\'' ?>>5:30 PM</option>
<option value="17:45" <?php if ($prop['FOLLOW_UP_TIME'] == '17:45:00') echo 'selected=\'selected\'' ?>>5:45 PM</option>

<option value="18:00" <?php if ($prop['FOLLOW_UP_TIME'] == '18:00:00') echo 'selected=\'selected\'' ?>>6:00 PM</option>
<option value="18:15" <?php if ($prop['FOLLOW_UP_TIME'] == '18:15:00') echo 'selected=\'selected\'' ?>>6:15 PM</option>
<option value="18:30" <?php if ($prop['FOLLOW_UP_TIME'] == '18:30:00') echo 'selected=\'selected\'' ?>>6:30 PM</option>
<option value="18:45" <?php if ($prop['FOLLOW_UP_TIME'] == '18:45:00') echo 'selected=\'selected\'' ?>>6:45 PM</option>

<option value="19:00" <?php if ($prop['FOLLOW_UP_TIME'] == '19:00:00') echo 'selected=\'selected\'' ?>>7:00 PM</option>
<option value="19:15" <?php if ($prop['FOLLOW_UP_TIME'] == '19:15:00') echo 'selected=\'selected\'' ?>>7:15 PM</option>
<option value="19:30" <?php if ($prop['FOLLOW_UP_TIME'] == '19:30:00') echo 'selected=\'selected\'' ?>>7:30 PM</option>
<option value="19:45" <?php if ($prop['FOLLOW_UP_TIME'] == '19:45:00') echo 'selected=\'selected\'' ?>>7:45 PM</option>

<option value="20:00" <?php if ($prop['FOLLOW_UP_TIME'] == '20:00:00') echo 'selected=\'selected\'' ?>>8:00 PM</option>
<option value="20:15" <?php if ($prop['FOLLOW_UP_TIME'] == '20:15:00') echo 'selected=\'selected\'' ?>>8:15 PM</option>
<option value="20:30" <?php if ($prop['FOLLOW_UP_TIME'] == '20:30:00') echo 'selected=\'selected\'' ?>>8:30 PM</option>
<option value="20:45" <?php if ($prop['FOLLOW_UP_TIME'] == '20:45:00') echo 'selected=\'selected\'' ?>>8:45 PM</option>

<option value="21:00" <?php if ($prop['FOLLOW_UP_TIME'] == '21:00:00') echo 'selected=\'selected\'' ?>>9:00 PM</option>
<option value="21:15" <?php if ($prop['FOLLOW_UP_TIME'] == '21:15:00') echo 'selected=\'selected\'' ?>>9:15 PM</option>
<option value="21:30" <?php if ($prop['FOLLOW_UP_TIME'] == '21:30:00') echo 'selected=\'selected\'' ?>>9:30 PM</option>
<option value="21:45" <?php if ($prop['FOLLOW_UP_TIME'] == '21:45:00') echo 'selected=\'selected\'' ?>>9:45 PM</option>

<option value="22:00" <?php if ($prop['FOLLOW_UP_TIME'] == '22:00:00') echo 'selected=\'selected\'' ?>>10:00 PM</option>
<option value="22:15" <?php if ($prop['FOLLOW_UP_TIME'] == '22:15:00') echo 'selected=\'selected\'' ?>>10:15 PM</option>
<option value="22:30" <?php if ($prop['FOLLOW_UP_TIME'] == '22:30:00') echo 'selected=\'selected\'' ?>>10:30 PM</option>
<option value="22:45" <?php if ($prop['FOLLOW_UP_TIME'] == '22:45:00') echo 'selected=\'selected\'' ?>>10:45 PM</option>
</select>
</td>
</tr>

<tr>
<td align="right">Closed Date:</td>
<td align="left" valign="top">
<input name="closed_date" id="closed_date" size="10" value="<?php if ($prop['CLOSED_DATE']=="0000-00-00") echo ""; else echo $prop['CLOSED_DATE'];?>" />
<script type="text/javascript">
	var p_cal = new tcal ({
		'controlname': 'closed_date'
	});
</script>
</td>

</tr>

<tr><td align="left" colspan="4">Provider Information:</td></tr>
<tr><td align="left" colspan="4">
<textarea style="width:99%;height:200px" id="elm1" name="provider_info"><?=stripslashes($prop['PROVIDER_INFO'])?></textarea></td>
</tr>

<tr><td align="left" colspan="4">Add Note:</td></tr>
<tr>
<td align="left" colspan="4">
<textarea style="width:98%;height:60px" wrap="virtual" name="add_notes" <?php if($lead_id==null) echo "readonly" ?>></textarea><br />
<input class="button" style="float:right" type="submit" name="submit" value="Submit" <?php if ($lead_id==null) echo "disabled='disabled'" ?> />
</td>
</tr>

<tr><td align="left" colspan="4">Note History:</td></tr>
<tr><td align="left" colspan="4">
<textarea style="width:98%;height:200px" wrap="virtual" name="notes" required <?php if(!$session->isAdmin() || $lead_id==null) echo "readonly" ?>><?=stripslashes($prop['NOTES'])?></textarea></td>
</tr>
<?php 
	$field_disabled = "";
	if( !$session->isMaster() ){
		$field_disabled = 'readonly="readonly" disabled="disabled"';
		$disable_button = "disabled";
	}
?>
<tr><td align="left" colspan="4">Manager Notes:</td></tr>
<tr>
<td align="left" colspan="4">
<textarea style="width:98%;height:60px" wrap="virtual" <?php echo $field_disabled; ?> name="add_manager_notes" <?php if($lead_id==null) echo "readonly" ?>></textarea><br />
<input class="button" style="float:right" type="submit" <?php echo $disable_button; ?> name="submit" value="Submit" <?php if ($lead_id==null) echo "disabled='disabled'" ?> />
</td>
</tr>

<tr><td align="left" colspan="4">Manager Notes History:</td></tr>
<tr><td align="left" colspan="4">
<textarea style="width:98%;height:200px" wrap="virtual" name="manager_notes" required <?php if(!$session->isAdmin() || $lead_id==null) echo "readonly" ?>><?=stripslashes($prop['MANAGER_NOTES'])?></textarea></td>
</tr>

<tr>
<td align="right" colspan="1">Intro Mail Sent:</td>
<td align="left" colspan="3"><?php if($prop['INTRO_SENT']!=null) echo date("m/d/Y h:i A T", strtotime($prop['INTRO_SENT'])); else echo "N/A" ?></td>
</tr>

<tr>
<td align="right" colspan="1">Search Report Sent:</td>
<td align="left" colspan="3"><?php if($prop['SEARCH_REPORT_SENT']!=null) echo date("m/d/Y h:i A T", strtotime($prop['SEARCH_REPORT_SENT'])); else echo "N/A" ?></td>
</tr>

<tr>
<td align="right" colspan="1">Search Report Read:</td>
<td align="left" colspan="3"><?php if($prop['SEARCH_REPORT_READ']!=null) echo date("m/d/Y h:i A T", strtotime($prop['SEARCH_REPORT_READ'])); else echo "N/A" ?></td>
</tr>

<tr>
<td align="left" colspan="3"><strong>Exit Strategy</strong></td>
<td><input class="button" type="button" value="Re-Calculate" onclick="calculateExitStrategy();" /></td>
</tr>

<tr><td align="right" colspan="1"><strong>Unlimited Access:</strong></td><td colspan="3"></td></tr>
<tr><td align="right" colspan="1">ARV:</td><td align="left" colspan="3"><input id="ua_arv" type="text" size="15" value="" /></td></tr>
<tr><td align="right" colspan="1">ARV (70%):</td><td align="left" colspan="3"><input id="ua_arv_seventy" type="text" size="15" value="" /></td></tr>
<tr><td align="right" colspan="1">Bath:</td><td align="left" colspan="3"><input id="ua_bath" type="text" size="15" value="" /></td></tr>
<tr><td align="right" colspan="1">Roof:</td><td align="left" colspan="3"><input id="ua_roof" type="text" size="15" value="" /></td></tr>
<tr><td align="right" colspan="1">Foundation:</td><td align="left" colspan="3"><input id="ua_foundation" type="text" size="15" value="" /></td></tr>
<tr><td align="right" colspan="1">HVAC:</td><td align="left" colspan="3"><input id="ua_hvac" type="text" size="15" value="" /></td></tr>
<tr><td align="right" colspan="1">Pool:</td><td align="left" colspan="3"><input id="ua_pool" type="text" size="15" value="" /></td></tr>
<tr><td align="right" colspan="1">Square Feet:</td><td align="left" colspan="3"><input id="ua_sqft" type="text" size="15" value="" /></td></tr>
<tr><td align="right" colspan="1">Repair Cost:</td><td align="left" colspan="3"><input id="ua_repair_cost" type="text" size="15" value="" /></td></tr>
<tr><td align="right" colspan="1">MAO:</td><td align="left" colspan="3"><input id="ua_mao" type="text" size="15" value="" /></td></tr>
<td align="left" colspan="4"><br /></td>

<tr><td align="right" colspan="1"><strong>Half-Hab / Makeready / As-Is on MLS:</strong></td><td colspan="3"></td></tr>
<tr><td align="right" colspan="1">As-Is Price:</td><td align="left" colspan="3"><input id="hh_asis" type="text" size="15" value="" /></td></tr>
<tr><td align="right" colspan="1">Repair Cost:</td><td align="left" colspan="3">
<select id="hh_repair_cost" name="hh_repair_cost">
<option value="5000" <?php if ($prop['HH_REPAIR_COST'] == 5000) echo "selected='selected'" ?>>5,000</option>
<option value="10000" <?php if ($prop['HH_REPAIR_COST'] == 10000) echo "selected='selected'" ?>>10,000</option>
<option value="10000" <?php if ($prop['HH_REPAIR_COST'] == 15000) echo "selected='selected'" ?>>15,000</option>
<option value="10000" <?php if ($prop['HH_REPAIR_COST'] == 20000) echo "selected='selected'" ?>>20,000</option>
</select>
</td></tr>
<tr><td align="right" colspan="1">Asking Price:</td><td align="left" colspan="3"><input id="hh_asking" type="text" size="15" value="" /></td></tr>
<tr><td align="right" colspan="1">Potential Profit:</td><td align="left" colspan="3"><input id="hh_profit" type="text" size="15" value="" /></td></tr>
<td align="left" colspan="4"><br /></td>

<tr><td align="right" colspan="1"><strong>Wholesale or Terminate:</strong></td><td colspan="3"></td></tr>
<tr><td align="right" colspan="1">ARV:</td><td align="left" colspan="3"><input id="wt_arv" type="text" size="15" value="" /></td></tr>
<tr><td align="right" colspan="1">ARV (80%):</td><td align="left" colspan="3"><input id="wt_arv_eighty" type="text" size="15" value="" /></td></tr>
<tr><td align="right" colspan="1">Repair Cost:</td><td align="left" colspan="3">
<select id="wt_repair_cost" name="wt_repair_cost">
<option value="20000" <?php if ($prop['WT_REPAIR_COST'] == 20000) echo "selected='selected'" ?>>20,000</option>
<option value="25000" <?php if ($prop['WT_REPAIR_COST'] == 25000) echo "selected='selected'" ?>>25,000</option>
<option value="30000" <?php if ($prop['WT_REPAIR_COST'] == 30000) echo "selected='selected'" ?>>30,000</option>
</select>
</td></tr>
<tr><td align="right" colspan="1">Fee:</td><td align="left" colspan="3"><input id="wt_fee" type="text" size="15" value="5000" disabled /></td></tr>
<tr><td align="right" colspan="1">Asking Price:</td><td align="left" colspan="3"><input id="wt_asking" type="text" size="15" value="" /></td></tr>
<tr><td align="right" colspan="1">Potential Profit:</td><td align="left" colspan="3"><input id="wt_profit" type="text" size="15" value="" /></td></tr>
<td align="left" colspan="4"><br /></td>

<tr><td align="right" colspan="1"><strong>Rental or Hedge:</strong></td><td colspan="3"></td></tr>
<tr><td align="right" colspan="1">Built after 1985+:</td><td align="left" colspan="3"><input id="rh_build" type="checkbox" /></td></tr>
<tr><td align="right" colspan="1">Lipstick:</td><td align="left" colspan="3"><input id="rh_lipstick" name="rh_lipstick" type="checkbox" value="1" <?php if ($prop['RH_LIPSTICK'] == 1) echo "checked='checked'" ?> /></td></tr>
<tr><td align="right" colspan="1">Purchase under 170k:</td><td align="left" colspan="3"><input id="rh_purchase" type="checkbox" /></td></tr>
<tr><td align="right" colspan="1">ARV:</td><td align="left" colspan="3"><input id="rh_arv" type="text" size="15" value="" /></td></tr>
<tr><td align="right" colspan="1">ARV (80%):</td><td align="left" colspan="3"><input id="rh_arv_eighty" type="text" size="15" value="" /></td></tr>
<tr><td align="right" colspan="1">Rent Comp:</td><td align="left" colspan="3"><input id="rh_rent_comp" name="rh_rent_comp" type="text" size="15" value="<?=stripslashes($prop['RH_RENT_COMP'])?>" /></td></tr>
<td align="left" colspan="4">
	<br />
	<?php
		$attachments_result = $mysqli->query("SELECT * FROM lead_attachments WHERE lead_id= ".$lead_id." AND type = 1 ORDER BY title ASC") or die(mysql_error());
	?>
	<table width="100%">
		<tr>
			<td colspan="2"><h2><strong>Attachments</strong></h2></td>
		</tr>
		<tr>
			<td colspan="2"><input type="file" name="fileToUpload" id="fileToUpload"> <input type="text" name="file_title" id="file_title"> <input class="button" type="submit" name="submit_file" value="Attached File" /></td>
		</tr>
		<tr><td colspan="2"><hr /></td></tr>
		<tr>
			<td><strong>Title</strong></td>
			<td><strong>Actions</strong></td>
		</tr>
		<?php
			while ($row_attach = mysqli_fetch_array($attachments_result)) {
			foreach ($row_attach AS $key => $value) {
				$row_attach[$key] = stripslashes($value);
			}				
		?>
		<tr>
			<td><a target="_blank" href="files/lead_attachments/<?php echo $row_attach['filename']; ?>"><?php echo $row_attach['title']; ?></a></td>
			<td><a target="_blank" href="files/lead_attachments/<?php echo $row_attach['filename']; ?>"><img src='images/k-view-icon.png' alt='View Attachment' title='View Attachment' /></a> <a href="editLead.php?lead_id=<?php echo $lead_id; ?>&del_attachment=1&attach_id=<?php echo $row_attach['id']; ?>&file=<?php echo $row_attach['filename']; ?>" onclick="return confirm('Are you sure you want to remove this file?')"><img src='images/delete.png' alt='Delete Attachment' title='Delete Attachment' /></a></td>
		</tr>
		<?php
			}
		?>
	</table>
</td>
<td align="left" colspan="4">
	<br />
</td>
</table>
</td></tr>

</table>
</form>
</div>

<div id="email_popup" class="popup_block">
	<p />
	<h3>Email Lead Contact</h3>
	<form id="emailForm" name="emailForm">
		<p />
		Template: <select id="template" name="template" onchange="tinymce.get('message').setContent(this.value);">
		<option value="">None</option>
		<?php
			$result = $mysqli->query("SELECT name,content FROM email_templates") or die(mysql_error());
			while($row = mysqli_fetch_array($result)){
				foreach($row AS $key => $value) {
					$row[$key] = stripslashes($value);
				}
		?>
		<option value="<?=str_replace('"', "'", $row['content'])?>"><?=$row['name']?></option>
		<?php
			}
		?>
		</select>
		<p />
		<input id="subject" name="subject" type="text" style="width:96%" placeholder="Subject" />
		<p />
		<textarea id="message" name="message" style="width:98%; height:480px"></textarea>
		<p />
		<input class="button" type="button" id="sendEmail" name="sendEmail" value="Send Email"
		onClick="callHelper('searchReportHelper.php?action=sendEmail&lead_id=<?= $lead_id ?>'); $('.popup_block').hide(); $('#fade, a.close').remove();" />
	</form>
</div>

<!-- End Content -->
</div>
</body>
</html>
<?php
}
?>