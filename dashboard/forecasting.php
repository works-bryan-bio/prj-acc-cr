<?php
require_once("include/checklogin.php");
require_once('include/db_connect.php');
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
<script type="text/javascript" src="js/tigra_calendar/calendar_us.js"></script>
<script type="text/javascript">

function changeCriteria() {
	var forecast_chance = document.getElementById('forecast_chance').value;
	var start_date = document.getElementById('start_date').value;
	var end_date = document.getElementById('end_date').value;
	var search = document.getElementById('search').value;
	var owner = document.getElementById('owner').value;
	if (owner!=null) {
		window.location.href='forecasting.php?forecast_chance=' + forecast_chance + '&start_date=' + start_date + '&end_date=' + end_date + "&search=" + search + "&owner=" + owner;
	} else {
		window.location.href='forecasting.php?forecast_chance=' + forecast_chance + '&start_date=' + start_date + '&end_date=' + end_date + "&search=" + search;
	}
}

</script>
</head>
<body>
<div id="header"><?php require "header.inc.php"; ?></div>
<div id="menu"><?php require "menu.inc.php"; ?></div>
<div id="content">
<!-- Begin Content-->

<?php
$username = $_SESSION['username'];
?>

<table cellpadding="3">
<tr><td valign="top">

<?php
$start_date = date("Y-m-d", strtotime(date('m').'/01/'.date('Y').' 00:00:00'));
$end_date = date("Y-m-d", strtotime('-1 second',strtotime('+1 month',strtotime(date('m').'/01/'.date('Y').' 00:00:00'))));

if(isset($_GET["owner"]) && $_GET["owner"]!=null) {
	$owner = $_GET["owner"];
	if ($owner=="All") {
		$uquery = "";
	} else {
		$uquery = "USERNAME='" . $owner . "' AND";
	}
} else {
	$owner = $_SESSION['username'];
	$uquery = "USERNAME='" . $owner . "' AND";
}
if(isset($_GET["forecast_chance"]) && $_GET["forecast_chance"]!=null) {
	$forecast_chance = urldecode($_GET["forecast_chance"]);
	$fcquery = "FORECAST_CHANCE='" . $forecast_chance . "' AND";
} else {
	$forecast_chance = "All";
	$fcquery = "FORECAST_CHANCE IS NOT NULL AND FORECAST_CHANCE!='' AND";
}
if(isset($_GET["start_date"])) {
	$start_date = $_GET["start_date"];
	if($start_date=="") {
		$stquery = "";
	} else {
		$stquery = "CLOSED_DATE>='" . date("Y-m-d", strtotime($start_date)) . " 00:00:00' AND";
	}
}
if(isset($_GET["end_date"])) {
	$end_date = $_GET["end_date"];
	if($end_date=="") {
		$edquery = "";
	} else {
		$edquery = "CLOSED_DATE<='" . date("Y-m-d", strtotime($end_date)) . " 23:59:59' AND";
	}
}
if(isset($_GET["search"])) {
	$search = $_GET["search"];
	if($search=="") {
		$sequery = "";
	} else {
		$sequery = "(COMPANY_NAME LIKE '%" . $search . "%'
					OR FIRST_NAME LIKE '%" . $search . "%'
					OR LAST_NAME LIKE '%" . $search . "%'
					OR CLIENT_EMAIL LIKE '%" . $search . "%'
					OR OFFICE_PHONE LIKE '%" . $search . "%'
					OR CELL_PHONE LIKE '%" . $search . "%'
					OR PROPERTY_TYPE LIKE '%" . $search . "%'
					OR PREDICTED_CENTER LIKE '%" . $search . "%'
					OR SEARCH_CITY LIKE '%" . $search . "%') AND";
	}
}
$query = "SELECT * FROM leads WHERE " . $fcquery . " " . $stquery . " " . $edquery . " " . $sequery . " " . $uquery . " (STATUS='Live' OR STATUS='Placed' OR STATUS='Field Agent') ORDER BY CLOSED_DATE";
$result = $mysqli->query($query) or die($mysqli->error);
?>
<table class="grid" style="width:270px;">
<tr>
<td colspan="2"><h3>Totals</h3></td>
</tr>
<tr>
<td align="left" width="50%"><label>Total Leads:</label></td>
<td width="50%"><?=$result->num_rows?></td>
</tr>
<tr>
<td align="left" width="50%"><label>Deal Value:</label></td>
<td class="orange" width="50%">
<?php
$deal_total = 0;
if ($result->num_rows>0) {
	while($row = mysqli_fetch_array($result)){
	  foreach($row AS $key => $value) {
			$row[$key] = stripslashes($value);
		}
		$predicted = (float) $row['PREDICTED_AMT'];
		$chance = $row['FORECAST_CHANCE'];
		if ($chance=="Hot Lead (0%)") $deal_total += $predicted * 0;
		if ($chance=="Listing Appointment (30%)") $deal_total += $predicted * .30;
		if ($chance=="Showing (30%)") $deal_total += $predicted * .30;
		if ($chance=="Docusign Sent (60%)") $deal_total += $predicted * .60;
		if ($chance=="Active Listing (70%)") $deal_total += $predicted * .70;
		if ($chance=="Offer (75%)") $deal_total += $predicted * .75;
		if ($chance=="Executed (75%)") $deal_total += $predicted * .75;
		if ($chance=="Pending (75%)") $deal_total += $predicted * .75;
		if ($chance=="Exit Confirmed (95%)") $deal_total += $predicted * .95;
		if ($chance=="Closed (100%)") $deal_total += $predicted * 1;
	}
}
?>
$ <?=number_format($deal_total, 2)?>
</td>
</tr>
<?php
if ($session->isAdmin() || $session->isMaster()) {
?>
<tr>
<td align="left" width="50%"><label for="owner">Lead Owner:</label></td>
<td align="left" width="50%">
<select id="owner" name="owner" onChange="changeCriteria()">
<option value="All" <?if($owner=="All") echo "selected=\"selected\""?>>All</option>
<?php
	$result = $mysqli->query("SELECT * FROM users WHERE fullname!='' AND userlevel>0 ORDER BY fullname ASC") or die(mysql_error());
	while($row = mysqli_fetch_array($result)){
  	foreach($row AS $key => $value) {
			$row[$key] = stripslashes($value);
		}
?>
<option value="<?=$row['username']?>" <?if($row['username']==$owner) echo "selected=\"selected\""?>><?=$row['fullname']?></option>
<?php
	}
?>
</select>
</td>
</tr>
<?php
} else {
?>
<tr><td style="display:none"><input type="hidden" id="owner" name="owner" value="<?=$username?>" /></td></tr>
<?php
}
?>
</table>

</td><td valign="top">

<table class="grid">
<tr>
<td align="center" padding-bottom:5px">
<label>Search:</label>
<input type="text" name="search" id="search" size="30" value="<?php if ($search!="") echo $search; else echo "" ?>" onChange="changeCriteria()" />
&nbsp;
<label>Start Date:</label>
<input name="start_date" id="start_date" size="10"
	value="<?php if ($start_date!="") echo date("m/d/Y", strtotime($start_date)); else echo "" ?>" onChange="changeCriteria()" />
<script type="text/javascript">
	var s_cal = new tcal ({
		'controlname': 'start_date'
	});
</script>
&nbsp;
<label>End Date:</label>
<input name="end_date" id="end_date" size="10"  value="<?php if ($end_date!="") echo date("m/d/Y", strtotime($end_date)); else echo "" ?>" onChange="changeCriteria()" />
<script type="text/javascript">
	var e_cal = new tcal ({
		'controlname': 'end_date'
	});
</script>
&nbsp;
<label>Forecast Chance:</label>
<select id="forecast_chance" name="forecast_chance" onChange="changeCriteria()">
<option value="">All</option>
<?php
if ($session->isAdmin() || $session->isMaster()) {
?>
<option value="Hot Lead (0%)" <?if($forecast_chance=="Hot Lead (0%)") echo "selected=\"selected\"";?>>Hot Lead (0%)</option>
<option value="Listing Appointment (30%)" <?if($forecast_chance=="Listing Appointment (30%)") echo "selected=\"selected\"";?>>Listing Appointment (30%)</option>
<option value="Showing (30%)" <?if($forecast_chance=="Showing (30%)") echo "selected=\"selected\"";?>>Showing (30%)</option>
<option value="Docusign Sent (60%)" <?if($forecast_chance=="Docusign Sent (60%)") echo "selected=\"selected\"";?>>Docusign Sent (60%)</option>
<option value="Active Listing (70%)" <?if($forecast_chance=="Active Listing (70%)") echo "selected=\"selected\"";?>>Active Listing (70%)</option>
<option value="Offer (75%)" <?if($forecast_chance=="Offer (75%)") echo "selected=\"selected\"";?>>Offer (75%)</option>
<option value="Executed (75%)" <?if($forecast_chance=="Executed (75%)") echo "selected=\"selected\"";?>>Executed (75%)</option>
<option value="Pending (75%)" <?if($forecast_chance=="Pending (75%)") echo "selected=\"selected\"";?>>Pending (75%)</option>
<option value="Exit Confirmed (95%)" <?if($forecast_chance=="Exit Confirmed (95%)") echo "selected=\"selected\"";?>>Exit Confirmed (95%)</option>
<option value="Closed (100%)" <?if($forecast_chance=="Closed (100%)") echo "selected=\"selected\"";?>>Closed (100%)</option>
<?php
}
if ($session->isAssetManager()) {
?>
<option value="Hot Lead (0%)" <?if($forecast_chance=="Hot Lead (0%)") echo "selected=\"selected\"";?>>Hot Lead (0%)</option>
<option value="Showing (30%)" <?if($forecast_chance=="Showing (30%)") echo "selected=\"selected\"";?>>Showing (30%)</option>
<option value="Docusign Sent (60%)" <?if($forecast_chance=="Docusign Sent (60%)") echo "selected=\"selected\"";?>>Docusign Sent (60%)</option>
<option value="Offer (75%)" <?if($forecast_chance=="Offer (75%)") echo "selected=\"selected\"";?>>Offer (75%)</option>
<option value="Executed (75%)" <?if($forecast_chance=="Executed (75%)") echo "selected=\"selected\"";?>>Executed (75%)</option>
<option value="Pending (75%)" <?if($forecast_chance=="Pending (75%)") echo "selected=\"selected\"";?>>Pending (75%)</option>
<option value="Closed (100%)" <?if($forecast_chance=="Closed (100%)") echo "selected=\"selected\"";?>>Closed (100%)</option>
<?php
}
if ($session->isTATAgent()) {
?>
<option value="Hot Lead (0%)" <?if($forecast_chance=="Hot Lead (0%)") echo "selected=\"selected\"";?>>Hot Lead (0%)</option>
<option value="Listing Appointment (30%)" <?if($forecast_chance=="Listing Appointment (30%)") echo "selected=\"selected\"";?>>Listing Appointment (30%)</option>
<option value="Showing (30%)" <?if($forecast_chance=="Showing (30%)") echo "selected=\"selected\"";?>>Showing (30%)</option>
<option value="Active Listing (70%)" <?if($forecast_chance=="Active Listing (70%)") echo "selected=\"selected\"";?>>Active Listing (70%)</option>
<option value="Offer (75%)" <?if($forecast_chance=="Offer (75%)") echo "selected=\"selected\"";?>>Offer (75%)</option>
<option value="Executed (75%)" <?if($forecast_chance=="Executed (75%)") echo "selected=\"selected\"";?>>Executed (75%)</option>
<option value="Pending (75%)" <?if($forecast_chance=="Pending (75%)") echo "selected=\"selected\"";?>>Pending (75%)</option>
<option value="Closed (100%)" <?if($forecast_chance=="Closed (100%)") echo "selected=\"selected\"";?>>Closed (100%)</option>
<?php
}
if ($session->isSHSAgent()) {
?>
<option value="Hot Lead (0%)" <?if($forecast_chance=="Hot Lead (0%)") echo "selected=\"selected\"";?>>Hot Lead (0%)</option>
<option value="Docusign Sent (60%)" <?if($forecast_chance=="Docusign Sent (60%)") echo "selected=\"selected\"";?>>Docusign Sent (60%)</option>
<option value="Executed (75%)" <?if($forecast_chance=="Executed (75%)") echo "selected=\"selected\"";?>>Executed (75%)</option>
<option value="Pending (75%)" <?if($forecast_chance=="Pending (75%)") echo "selected=\"selected\"";?>>Pending (75%)</option>
<option value="Exit Confirmed (95%)" <?if($forecast_chance=="Exit Confirmed (95%)") echo "selected=\"selected\"";?>>Exit Confirmed (95%)</option>
<option value="Closed (100%)" <?if($forecast_chance=="Closed (100%)") echo "selected=\"selected\"";?>>Closed (100%)</option
<?php } ?>
</select>
&nbsp;
<input class="button" type="button" value="Submit" onChange="changeCriteria()" />
</td>
</tr>
</table>
<br />

<?php
if ($forecast_chance=="All") {
	$chances = array("Hot Lead (0%)","Listing Appointment (20%)","Showing (30%)","Docusign Sent (60%)","Active Listing (70%)","Offer (75%)","Executed (75%)", "Executed (85%)","Pending (90%)","Exit Confirmed (95%)","Closed (100%)");
} else {
	$chances = array($forecast_chance);
}
foreach ($chances as $fc) {
?>
<table class="grid" style="width:1200px;">
<tr><td colspan="10"><h3><?=$fc?></h3></td></tr>
<tr>
<th width="40"></th>
<th width="120">Company<br />Name</th>
<th width="120">Client<br />Name</th>
<th width="100">Exit<br />Strategy</th>
<th>Address 1</th>
<th width="115">End Of Option</th>
<th width="105">Close Date</th>
<th width="120">Lead Source</th>
<th width="100">Deal<br />Value</th>
</tr>
<?php	
	$query = "SELECT * FROM leads WHERE FORECAST_CHANCE='" . $fc . "' AND " . $stquery . " " . $edquery . " " . $sequery . " " . $uquery . " (STATUS='Live' OR STATUS='Placed' OR STATUS='Field Agent') ORDER BY CLOSED_DATE";	
	$result = $mysqli->query($query) or die(mysql_error());
	if ($result->num_rows>0) {
		$deal_grand_total = 0;
		while($row = mysqli_fetch_array($result)){
		  foreach($row AS $key => $value) {
				$row[$key] = stripslashes($value);
			}
			$query2 = "SELECT * FROM affiliates WHERE AFFILIATE_ID=" . $row['AFFILIATE_ID'];
			$result2 = $mysqli->query($query2) or die($mysqli->error);
			while($row2 = mysqli_fetch_array($result2)){
		  	foreach($row2 AS $key => $value) {
					$row2[$key] = stripslashes($value);
				}
				$affiliate_es = (float) $row2['COMMISSION_ES'];
				if ($affiliate_es==null) $affiliate_es = 1;
				$affiliate_conv = (float) $row2['COMMISSION_CONV'];
				if ($affiliate_conv==null) $affiliate_conv = 1;
				$affiliate_name = $row2['COMPANY_NAME'];
			}
			$deal_total = 0;
			$predicted = (float) $row['PREDICTED_AMT'];
			if ($fc=="Hot Lead (0%)") $deal_total += $predicted * 0;
			if ($fc=="Listing Appointment (30%)") $deal_total += $predicted * .30;
			if ($fc=="Showing (30%)") $deal_total += $predicted * .30;
			if ($fc=="Docusign Sent (60%)") $deal_total += $predicted * .60;
			if ($fc=="Active Listing (70%)") $deal_total += $predicted * .70;
			if ($fc=="Offer (75%)") $deal_total += $predicted * .75;
			if ($fc=="Executed (75%)") $deal_total += $predicted * .75;
			if ($fc=="Pending (75%)") $deal_total += $predicted * .75;
			if ($fc=="Exit Confirmed (95%)") $deal_total += $predicted * .95;
			if ($fc=="Closed (100%)") $deal_total += $predicted * 1;

			
			/*if ($fc=="Provider Selected") $deal_total = $predicted * .50;
			if ($fc=="Agreement Issued") $deal_total = $predicted * .85;
			if ($fc=="Signed") $deal_total = $predicted * .95;
			if ($fc=="Funds Cleared") $deal_total = $predicted * 1;*/
			$deal_grand_total += $deal_total;
?>
<tr>
<td class="center"><a href="editLead.php?lead_id=<?=$row['LEAD_ID']?>&action=search"><img src='images/edit.png' alt='Edit Lead' title='Edit Lead' /></a></td>
<td><?=$row['COMPANY_NAME']?></td>
<td><?=$row['FIRST_NAME']?> <?=$row['LAST_NAME']?></td>
<td><?=$row['EXIT_STRATEGY']?></td>
<td><?=$row['ADDRESS_1']?></td>
<td><?=$row['END_OF_OPTION']?></td>
<td><?=$row['CLOSED_DATE']?></td>
<td><?=$affiliate_name?></td>
<td>$ <?=number_format($deal_total, 2)?></td>
</tr>
<?php
		}
?>
<tr>
<td colspan="8" align="right">Total:</td>
<td class="orange">$ <?=number_format($deal_grand_total, 2)?></td>
</tr>
<?php
	} else {
?>
<tr><td colspan="9" align="center">No Leads Found</tr>
<?php
	}
?>
</table>
<p />
<?php
}
?>

</tr>
</table>

<!-- End Content -->
</div>
</body>
</html>