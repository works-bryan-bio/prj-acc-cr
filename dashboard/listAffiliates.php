<?php
require_once("include/checklogin.php");
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
<script type="text/javascript" src="js/highlightlink.js"></script>

</head>
<body>
<div id="header"><?php require "header.inc.php"; ?></div>
<div id="menu"><?php require "menu.inc.php"; ?></div>
<div id="content">
<!-- Begin Content-->

<?php
require_once('include/db_connect.php');
require_once('include/pagination.php');

$size = 20;
$link = "listAffiliates.php?page=%s";
$orderby = "company_name";
if (isset($_GET['orderby'])){
    $orderby = $_GET['orderby'];
		$link .= "&orderby=" . $orderby;
}
$dir = "ASC";
if (isset($_GET['dir'])){
    $dir = $_GET['dir'];
		$link .= "&dir=" . $dir;
}
$page = 1;
if (isset($_GET['page'])){
    $page = (int) $_GET['page'];
}
if (isset($_GET['filter'])){
    $filter = $_GET['filter'];
}
$pagination = new Pagination();
$pagination->setLink($link);
$pagination->setPage($page);
$pagination->setSize($size);
$result = $mysqli->query("SELECT COUNT(*) FROM affiliates");
$row = $result->fetch_row();
$total_records = $row[0];
$pagination->setTotalRecords($total_records);
$result = $mysqli->query("SELECT * FROM affiliates WHERE company_name LIKE '%" . $filter . "%'
													ORDER BY " . $orderby . " " . $dir . " " . $pagination->getLimitSql())
	or die(mysqli_error());
?>

<table>
<tr><td>
<form name="filterform" id="filterform" action="" method="get">
<input type="text" name="filter" id="filter" size="30" value="<?=$filter?>" />
<button class="button" type="submit">Filter</button>
<button class="button" type="submit" onClick="document.filterform.filter.value=''">Clear</button>
</form>
<td>
<td>
<button class="button" onclick="window.location='addAffiliate.php'">Add Lead Source</button>
</td></tr>
</table>

<p />
<div class="pagination">
<?php
$navigation = $pagination->create_links();
echo $navigation;
?>
</div>

<p />
<table class="grid">
<tr>
	<th>Actions</th>
	<th>Company Name&nbsp;<a href="listAffiliates.php?orderby=company_name&dir=ASC&filter=<?=$filter?>">&#9650;</a>&nbsp;<a href="listAffiliates.php?orderby=company_name&dir=DESC&filter=<?=$filter?>">&#9660;</a></th>
	<th>Contact Name&nbsp;<a href="listAffiliates.php?orderby=contact_name&dir=ASC&filter=<?=$filter?>">&#9650;</a>&nbsp;<a href="listAffiliates.php?orderby=contact_name&dir=DESC&filter=<?=$filter?>">&#9660;</a></th>
	<th>Contact Email&nbsp;<a href="listAffiliates.php?orderby=contact_email&dir=ASC&filter=<?=$filter?>">&#9650;</a>&nbsp;<a href="listAffiliates.php?orderby=contact_email&dir=DESC&filter=<?=$filter?>">&#9660;</a></th>
	<th>Date Added&nbsp;<a href="listAffiliates.php?orderby=date_added&dir=ASC&filter=<?=$filter?>">&#9650;</a>&nbsp;<a href="listAffiliates.php?orderby=date_added&dir=DESC&filter=<?=$filter?>">&#9660;</a></th>
	<th>Last Updated&nbsp;<a href="listAffiliates.php?orderby=last_updated&dir=ASC&filter=<?=$filter?>">&#9650;</a>&nbsp;<a href="listAffiliates.php?orderby=last_updated&dir=DESC&filter=<?=$filter?>">&#9660;</a></th>
</tr>
<?php
$i = 0;
while($row = mysqli_fetch_array($result)){
  foreach($row AS $key => $value) {
		$row[$key] = stripslashes($value);
	}
?>
<tr onmouseover="ChangeColor(this, true),this.style.textDecoration='none';"
 onmouseout="ChangeColor(this, false),this.style.textDecoration='none';"
onclick="DoNav('editAffiliate.php?affiliate_id=<?=$row['AFFILIATE_ID']?>');"
<?php if(!is_float($i/2)) {?>class="alt"<?php } ?>>
<td class="center">
<a href="editAffiliate.php?affiliate_id=<?=$row['AFFILIATE_ID']?>">
<img src='images/edit.png' alt='Edit Affiliate' title='Edit Affiliate' /></a>
</td>
<td><?=$row['COMPANY_NAME']?></td>
<td><?=$row['CONTACT_NAME']?></td>
<td><?=$row['CONTACT_EMAIL']?></td>
<td><?=$row['DATE_ADDED']?></td>
<td><?php if($row['LAST_UPDATED']=="") echo ""; else echo date("Y-m-d h:i A", strtotime($row['LAST_UPDATED']))?></td>
</tr>
<?php
	++$i;
}
?>
</table>

<p />
<div class="pagination">
<?php
$navigation = $pagination->create_links();
echo $navigation;
?>
</div>


<p />
<div clas="small" align="center">Total Affiliates: <?=$total_records?></div>

<?php
//Clean up all open results/connections
$result->close();
$mysqli->close();
?>

<!-- End Content -->
</div>
</body>
</html>