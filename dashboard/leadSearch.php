<?php
require_once("include/checklogin.php");
require_once('include/db_connect.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>SimpleHouseSolutions.com - Lead Search</title>
<link rel="shortcut icon" href="/favicon.ico" />
<link rel="stylesheet" type="text/css" href="css/dashboard.css"/>
<link rel="stylesheet" type="text/css" href="css/dashboard_menu.css"/>
<link rel="stylesheet" type="text/css" href="js/tigra_calendar/calendar.css">
<script type="text/javascript" src="js/tigra_calendar/calendar_us.js"></script>
<script type="text/javascript">

var queryString = "";
function changeCriteria() {
	var search = document.getElementById('search').value;
	var status = document.getElementById('status').value;
	var affiliate_id = document.getElementById('affiliate_id').value;
	var priority = document.getElementById('priority').value;
	var username = document.getElementById('username').value;
	var start_date = document.getElementById('start_date').value;
	var end_date = document.getElementById('end_date').value;
	queryString = 'leadSearch.php?search=' + search + '&status=' + status + '&affiliate_id=' + affiliate_id + '&priority=' + priority + '&username=' + username + '&start_date=' + start_date + '&end_date=' + end_date + '&submit';
	window.location.href = queryString;
}

</script>
</head>
<body>
<div id="header"><?php require "header.inc.php"; ?></div>
<div id="menu"><?php require "menu.inc.php"; ?></div>
<div id="content">
<!-- Begin Content-->

<?php
$start_date = date("Y-m-d", strtotime(date('m').'/01/'.date('Y').' 00:00:00'));
$end_date = date("Y-m-d", strtotime('-1 second',strtotime('+1 month',strtotime(date('m').'/01/'.date('Y').' 00:00:00'))));

$result = null;
if (isset($_GET['orderby'])){
  $orderby = $_GET['orderby'];
} else {
	$orderby = "STATUS,PRIORITY";
}
$dir = "ASC";
if (isset($_GET['dir'])){
  $dir = $_GET['dir'];
}
if (isset($_GET['search'])){
   $search = $_GET['search'];
} else {
	$search = "";
}
if (isset($_GET['status'])){
	$status = $_GET['status'];
	$searchStatus = "AND STATUS='" . $status . "' ";
	if ($status=="All") {
		$searchStatus = "";
	}
} else {
	$searchStatus = "";
}
if (isset($_GET['affiliate_id'])){
	$affiliate_id = $_GET['affiliate_id'];
	$searchAffiliate = "AND AFFILIATE_ID='" . $affiliate_id . "' ";
	if ($affiliate_id=="All") {
		$searchAffiliate = "";
	}
} else {
	$searchAffiliate = "";
}
if (isset($_GET['priority'])){
	$priority = $_GET['priority'];
	$searchPriority = "AND PRIORITY='" . $priority . "' ";
	if ($priority=="All") {
		$searchPriority = "";
	}
} else {
	$searchPriority = "";
}
if (isset($_GET['username'])){
	$username = $_GET['username'];
	$searchUsername = "AND USERNAME='" . $username . "' ";
	if ($username=="All") {
		$searchUsername = "";
	}
} else {
	$searchUsername = "";
}
$stquery = "AND DATE_ADDED>='" . date("Y-m-d", strtotime(date('m').'/01/'.date('Y').' 00:00:00')) . " 00:00:00' ";
if(isset($_GET["start_date"])) {
	$start_date = $_GET["start_date"];
	if($start_date=="") {
		$stquery = "";
	} else {
		$stquery = "AND DATE_ADDED>='" . date("Y-m-d", strtotime($start_date)) . " 00:00:00' ";
	}
}
$edquery = "AND DATE_ADDED<='" . date("Y-m-d", strtotime('-1 second', strtotime('+1 month', strtotime(date('m').'/01/'. date('Y'))))) .  " 23:59:59' ";
if(isset($_GET["end_date"])) {
	$end_date = $_GET["end_date"];
	if($end_date=="") {
		$edquery = "";
	} else {
		$edquery = "AND DATE_ADDED<='" . date("Y-m-d", strtotime($end_date)) . " 23:59:59' ";
	}
}

$queryString = "search=" . $search . "&status=" . $status . "&affiliate_id=" . $affiliate_id . "&priority=" . $priority . "&username=" . $username . "&start_date=" . $start_date . "&end_date=" . $end_date;
?>

<p />
<table class="input">
<tr><td align="center">
<label for="search">Search:</label>
<input type="text" name="search" id="search" size="30" value="<?php if ($search!=null) echo $_GET['search']; ?>" onChange="changeCriteria()" />
<script type="text/javascript">
	var inputSearch = document.getElementById('search');
	var strLength = inputSearch.value.length * 2;
	inputSearch.focus();
	inputSearch.setSelectionRange(strLength, strLength);
</script>
&nbsp;
<label for="status">Status:</label>
<select id="status" name="status" onChange="changeCriteria()">
<option value="All">All</option>
<option value="New" <?if($status=="New") echo "selected=\"selected\"";?>>New</option>
<option value="Live" <?if($status=="Live") echo "selected=\"selected\"";?>>Live</option>
<option value="Dead" <?if($status=="Dead") echo "selected=\"selected\"";?>>Dead</option>
<option value="On Hold" <?if($status=="On Hold") echo "selected=\"selected\"";?>>On Hold</option>
<option value="Unqualified" <?if($status=="Unqualified") echo "selected=\"selected\"";?>>Unqualified</option>
<option value="Terminated" <?if($status=="Terminated") echo "selected=\"selected\"";?>>Terminated</option>
<option value="Closed" <?if($status=="Closed") echo "selected=\"selected\"";?>>Closed</option>
</select>
&nbsp;
<label for="status">Lead Source:</label>
<select id="affiliate_id" name="affiliate_id" onChange="changeCriteria()">
<?= $affiliate_id ?>
<option value="All">All</option>
<?php
		$result = $mysqli->query("SELECT * FROM affiliates ORDER BY COMPANY_NAME ASC")	or die(mysql_error());
		while($row = mysqli_fetch_array($result)){
			foreach($row AS $key => $value) {
				$row[$key] = stripslashes($value);
			}
?>
<option value="<?=$row['AFFILIATE_ID']?>" <?if($row['AFFILIATE_ID']==$affiliate_id) echo "selected=\"selected\""?>><?=$row['COMPANY_NAME']?></option>
<?php
		}
?>
</select>
&nbsp;
<label for="priority">Strength:</label>
<select id="priority" name="priority" onchange="changeCriteria()">
<option value="All">All</option>
<option value="NoContact" <?if($priority=="NoContact") echo "selected=\"selected\"";?>>No Contact</option>
<option value="Weak" <?if($priority=="Weak") echo "selected=\"selected\"";?>>Weak</option>
<option value="Mild" <?if($priority=="Mild") echo "selected=\"selected\"";?>>Mild</option>
<option value="Strong" <?if($priority=="Strong") echo "selected=\"selected\"";?>>Strong</option>
<option value="Hot" <?if($priority=="Hot") echo "selected=\"selected\"";?>>Hot</option>
</select>
&nbsp;
<label for="username">Owner:</label>
<select id="username" name="username" onchange="changeCriteria()">
<option value="All">All</option>
<option value="Not Assigned">Not Assigned</option>
<?php
		$result = $mysqli->query("SELECT * FROM users WHERE fullname!='' AND userlevel > 0 ORDER BY fullname ASC") or die(mysql_error());
		while($row = mysqli_fetch_array($result)){
  		foreach($row AS $key => $value) {
				$row[$key] = stripslashes($value);
			}
?>
<option value="<?=$row['username']?>" <?if($row['username'] == $username) echo "selected=\"selected\""?>><?=$row['fullname']?></option>
<?php
		}
?>
</select>
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
<input class="button" type="submit" name="submit" value="Submit" onClick="changeCriteria()" />
<input class="button" type="button" name="reset" value="Reset" onclick="window.location='leadSearch.php'" />
</td></tr>
</table>

<?php
$query = "SELECT * FROM leads WHERE
					(COMPANY_NAME LIKE '%" . $search . "%'
						OR FIRST_NAME LIKE '%" . $search . "%'
						OR LAST_NAME LIKE '%" . $search . "%'
						OR CLIENT_EMAIL LIKE '%" . $search . "%'
						OR OFFICE_PHONE LIKE '%" . $search . "%'
						OR CELL_PHONE LIKE '%" . $search . "%'
						OR OTHER_PHONE LIKE '%" . $search . "%'
						OR FAX LIKE '%" . $search . "%'
						OR WEBSITE LIKE '%" . $search . "%'
						OR ADDRESS_1 LIKE '%" . $search . "%'
						OR ADDRESS_2 LIKE '%" . $search . "%'
						OR CITY LIKE '%" . $search . "%'
						OR STATE LIKE '%" . $search . "%'
						OR ZIP LIKE '%" . $search . "%'
						OR COUNTRY LIKE '%" . $search . "%'
						OR EXTRA_FIRST_NAME LIKE '%" . $search . "%'
						OR EXTRA_LAST_NAME LIKE '%" . $search . "%'
						OR EXTRA_CLIENT_EMAIL LIKE '%" . $search . "%'
						OR SEARCH_CITY LIKE '%" . $search ."%'
						OR SEARCH_STATE LIKE '%" . $search ."%')
						" . $searchStatus . "
						" . $searchAffiliate . "
						" . $searchPriority . "
						" . $searchUsername . "
						" . $stquery . "
						" . $edquery . "
					ORDER BY " . $orderby . " " . $dir;
//echo $query;
if (isset($_GET['submit'])) {
	$result = $mysqli->query($query) or die(mysql_error());
}
?>
<p />
<?php if($result!=null) { ?>
<div style="float:left; width:88%">
<table class="grid">
<tr><td colspan="13"><h3>Matching Leads</h3></td></tr>
<tr style="font-weight:bold">
<th>Actions</th>
<th>Lead Type&nbsp;<a href="leadSearch.php?<?=$queryString?>&orderby=lead_type&dir=ASC&submit">&#9650;</a>&nbsp;<a href="leadSearch.php?<?= $queryString ?>&orderby=lead_type&dir=DESC&submit">&#9660;</a></th>
<th>First Name</th>
<th>Last Name</th>
<th>City</th>
<th>State</th>
<th>Home/Office Phone</th>
<th>Cell Phone</th>
<th>Follow-Up Date&nbsp;<a href="leadSearch.php?<?=$queryString?>&orderby=follow_up_date&dir=ASC&submit">&#9650;</a>&nbsp;<a href="leadSearch.php?<?= $queryString ?>&orderby=follow_up_date&dir=DESC&submit">&#9660;</a></th>
<th>Follow-Up Time&nbsp;<a href="leadSearch.php?<?= $queryString ?>&follow_up_time=<?= follow_up_time ?>&orderby=follow_up_time&dir=ASC&submit">&#9650;</a>&nbsp;<a href="leadSearch.php?<?= $queryString ?>&follow_up_time=<?= follow_up_time ?>&orderby=follow_up_time&dir=DESC&submit">&#9660;</a></th>
<th>Lead Strength&nbsp;<a href="leadSearch.php?<?=$queryString?>&orderby=priority&dir=ASC&submit">&#9650;</a>&nbsp;<a href="leadSearch.php?<?= $queryString ?>&orderby=priority&dir=DESC&submit">&#9660;</a></th>
<th>Status&nbsp;<a href="leadSearch.php?<?=$queryString?>&orderby=status&dir=ASC&submit">&#9650;</a>&nbsp;<a href="leadSearch.php?<?= $queryString ?>&orderby=status&dir=DESC&submit">&#9660;</a></th>
<th>Owner</th>
</tr>
<?php
if ($result->num_rows==0) {
?>
<tr><td colspan="13" align="center">No leads found</td></tr>
<?php
}
while($row = mysqli_fetch_array($result)){
  foreach($row AS $key => $value) {
		$row[$key] = stripslashes($value);
	}
?>
<?php
if ($row['PRIORITY'] == "Hot")
	++$hot;
if ($row['PRIORITY'] == "Strong")
	++$strong;
if ($row['PRIORITY'] == "Mild")
	++$mild;
if ($row['PRIORITY'] == "Weak")
	++$weak;
if ($row['PRIORITY'] == "NoContact")
	++$no_contract;
++$total;
?>
<tr>
<td class="center"><a href="editLead.php?lead_id=<?=$row['LEAD_ID']?>&action=search"><img src='images/edit.png' alt='Edit Lead' title='Edit Lead' /></a></td>
<td><?=$row['LEAD_TYPE'] ?></td>
<td><?=$row['FIRST_NAME']?></td>
<td><?=$row['LAST_NAME']?></td>
<?php if ($row['LEAD_TYPE'] == "Buyer") { ?>
<td><?= stripslashes($row['SEARCH_CITY']) ?></td>
<td align="center"><?= $row['SEARCH_STATE'] ?></td>
<?php } else { ?>
<td><?= stripslashes($row['CITY']) ?></td>
<td align="center"><?= $row['STATE'] ?></td>
<?php } ?>
<td><?=$row['OFFICE_PHONE']?></td>
<td><?=$row['CELL_PHONE']?></td>
<td><?=$row['FOLLOW_UP_DATE']?></td>
<td><?=$row['FOLLOW_UP_TIME']?></td>
<td class="<?= $row['PRIORITY'] ?>"><?php if ($row['PRIORITY'] == "NoContact") { echo "No Contact"; } else { echo $row['PRIORITY']; } ?></td>
<td><?=$row['STATUS']?></td>
<td><?=$row['USERNAME']?></td>
</tr>
<?php
}
?>
</table>
</div>
<div style="float:right; width:10%">
    <table class="grid">
        <tr><td colspan="2"><h3>Summary</h3></td></tr>
        <tr><td align="right">Call Back Total:</td><td><?= $total ?></td></tr>
        <tr class="Hot"><td align="right">Hot:</td><td><?= $hot ?></td></tr>
        <tr class="Strong"><td align="right">Strong:</td><td><?= $strong ?></td></tr>
        <tr class="Mild"><td align="right">Mild:</td><td><?= $mild ?></td></tr>
        <tr class="Weak"><td align="right">Weak:</td><td><?= $weak ?></td></tr>
        <tr class="NoContact"><td align="right">No Contact:</td><td><?= $no_contract ?></td></tr>
    </table>
</div>
<?php } ?>

<!-- End Content -->
</div>
</body>
</html>