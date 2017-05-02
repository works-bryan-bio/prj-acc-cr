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
	var start_date = document.getElementById('start_date').value;
	var end_date = document.getElementById('end_date').value;
	window.location.href='report.php?start_date=' + start_date + '&end_date=' + end_date;
}

</script>
</head>
<body>
<div id="header"><?php require "header.inc.php"; ?></div>
<div id="menu"><?php require "menu.inc.php"; ?></div>
<div id="content">
<!-- Begin Content-->

<?php
$start_date = date("Y-m-") . "01";
if(isset($_GET["start_date"]) && $_GET["start_date"]!="") {
	$start_date = $_GET["start_date"];
}
$stquery = "DATE_ADDED>='" . date("Y-m-d", strtotime($start_date)) . "' AND";

$end_date = date("Y-m-t");
if(isset($_GET["end_date"]) && $_GET["start_date"]!="") {
	$end_date = $_GET["end_date"];
}
$edquery = "DATE_ADDED<='" . date("Y-m-d", strtotime($end_date)) . "' AND";
?>

<table class="grid" align="center">
<tr>
<td align="center" padding-bottom:5px">
<label>Start Date:</label>
<input name="start_date" id="start_date" size="10" value="<?php if ($start_date!="") echo date("m/d/Y", strtotime($start_date)); else echo "" ?>" />
<script type="text/javascript">
	var s_cal = new tcal ({
		'controlname': 'start_date'
	});
</script>
&nbsp;
<label>End Date:</label>
<input name="end_date" id="end_date" size="10"  value="<?php if ($end_date!="") echo date("m/d/Y", strtotime($end_date)); else echo "" ?>" />
<script type="text/javascript">
	var e_cal = new tcal ({
		'controlname': 'end_date'
	});
</script>
&nbsp;
<input class="button" type="button" value="Submit" onClick="changeCriteria()" />
<input class="button" type="button" name="reset" value="Reset" onclick="window.location='report.php'" />
</td>
</tr>
</table>
<br />

<table class="grid" align="center" style="width:50% !important">
<tr><td colspan="8"><h3><?=date("m/d/Y", strtotime($start_date))?> - <?=date("m/d/Y", strtotime($end_date))?></h3></td></tr>
<tr>
<th>Consultant Name</th>
<th>Leads</th>
<th>Appointment</th>
<th>Appointment Percent</th>
<th>Executed</th>
<th>Executed Percent</th>
<th>Closed</th>
<th>Closed Percent</th>
</tr>
<?php
$result = $mysqli->query("SELECT * FROM users WHERE fullname!='' AND userlevel > 0 ORDER BY fullname ASC") or die(mysql_error());

while($row = mysqli_fetch_array($result)){
	$fullname = $row['fullname'];
	$username = $row['username'];
	$lead_id = "";
	$leads = 0;
	$appointments = 0;
	$apercent = 0;
	$hasAppointment = false;
	$executed = 0;
	$epercent = 0;
	$closed = 0;
	$cpercent = 0;
	$result2 = $mysqli->query("SELECT * FROM leads LEFT JOIN search_report ON leads.LEAD_ID=search_report.LEAD_ID WHERE " . $stquery . " " . $edquery . " USERNAME='" . $username . "' ORDER BY leads.LEAD_ID") or die($mysqli->error);
	while($row2 = mysqli_fetch_array($result2)) {
		if ($lead_id!=$row2['LEAD_ID']) {
			$leads++;
			if ($row2['FORECAST_CHANCE']=="Executed (85%)") {
			$executed++;
			}
			if ($row2['FORECAST_CHANCE']=="Closed (100%)") {
				$closed++;
			}
			$lead_id = $row2['LEAD_ID'];
		}
		if ($row2['TOUR_DATE']!=null && $hasAppointment != true) {
			$appointments++;
			$hasAppointment = true;
		}
	}
	if ($leads!=0) {
		$apercent = ($appointments / $leads) * 100;
		$epercent = ($executed / $leads) * 100;
		$cpercent = ($closed / $leads) * 100;
	}
?>
	<tr>
	<td><?=$fullname?></td>
	<td><?=$leads?></td>
	<td><?=$appointments?></td>
	<td><?=sprintf("%02.1f", $apercent)?> %</td>
	<td><?=$executed?></td>
	<td><?=sprintf("%02.1f", $epercent)?> %</td>
	<td><?=$closed?></td>
	<td><?=sprintf("%02.1f", $cpercent)?> %</td>
	</tr>
<?php } ?>
</table>
<p />


<!-- End Content -->
</div>
</body>
</html>