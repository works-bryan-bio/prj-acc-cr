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
$link = "listProperties.php?page=%s";
$orderby = "center_name";
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
    $link .="&filter=" . $filter;
}
$pagination = new Pagination();
$pagination->setLink($link);
$pagination->setPage($page);
$pagination->setSize($size);
$result = $mysqli->query("SELECT COUNT(*) FROM properties WHERE properties.STATUS!='delisted'");
$row = $result->fetch_row();
$total_records = $row[0];
$pagination->setTotalRecords($total_records);
$query = "SELECT properties.PROPERTY_ID, properties.CENTER_NAME, properties.CITY, providers.COMPANY_NAME, properties.CONTACT_NAME, properties.OFFICE_PHONE,
		properties.DATE_ADDED, properties.LAST_UPDATED, properties.BUYER_DESCRIPTION, properties.PHOTO_1, properties.PHOTO_2, properties.PHOTO_3, properties.PHOTO_4,
		properties.PHOTO_5, properties.PHOTO_6, properties.STATUS
		FROM properties LEFT JOIN providers ON properties.provider_id=providers.provider_id
		WHERE (properties.center_name LIKE '%" . $filter . "%' OR properties.CITY LIKE '%" . $filter . "%' OR properties.ADDRESS_1 LIKE '%" .$filter . "%')
		AND properties.STATUS!='delisted'
		ORDER BY " . $orderby . " " . $dir . " " . $pagination->getLimitSql();
$result = $mysqli->query($query) or die(mysqli_error($mysqli));
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
<button class="button" onclick="window.location='addProperty.php'">Add Property</button>
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
	<th>Center Name&nbsp;<a href="listProperties.php?orderby=properties.center_name&dir=ASC&filter=<?=$filter?>">&#9650;</a>&nbsp;<a href="listProperties.php?orderby=properties.center_name&dir=DESC&filter=<?=$filter?>">&#9660;</a></th>
	<th>City&nbsp;<a href="listProperties.php?orderby=properties.city&dir=ASC&filter=<?=$filter?>">&#9650;</a>&nbsp;<a href="listProperties.php?orderby=properties.city&dir=DESC&filter=<?=$filter?>">&#9660;</a></th>
	<th>Provider Name&nbsp;<a href="listProperties.php?orderby=providers.company_name&dir=ASC&filter=<?=$filter?>">&#9650;</a>&nbsp;<a href="listProperties.php?orderby=providers.company_name&dir=DESC&filter=<?=$filter?>">&#9660;</a></th>
	<th>Contact Name&nbsp;<a href="listProperties.php?orderby=properties.contact_name&dir=ASC&filter=<?=$filter?>">&#9650;</a>&nbsp;<a href="listProperties.php?orderby=properties.contact_name&dir=DESC&filter=<?=$filter?>">&#9660;</a></th>
	<th>Office Phone&nbsp;<a href="listProperties.php?orderby=properties.office_phone&dir=ASC&filter=<?=$filter?>">&#9650;</a>&nbsp;<a href="listProperties.php?orderby=properties.office_phone&dir=DESC&filter=<?=$filter?>">&#9660;</a></th>
	<th>Date Added&nbsp;<a href="listProperties.php?orderby=properties.date_added&dir=ASC&filter=<?=$filter?>">&#9650;</a>&nbsp;<a href="listProperties.php?orderby=properties.date_added&dir=DESC&filter=<?=$filter?>">&#9660;</a></th>
	<th>Last Updated&nbsp;<a href="listProperties.php?orderby=properties.last_updated&dir=ASC&filter=<?=$filter?>">&#9650;</a>&nbsp;<a href="listProperties.php?orderby=properties.last_updated&dir=DESC&filter=<?=$filter?>">&#9660;</a></th>
	<th>Description</th>
	<th>Photos</th>
	<th>Status&nbsp;<a href="listProperties.php?orderby=properties.status&filter=<?=$filter?>">&#9650;</a>&nbsp;<a href="listProperties.php?orderby=properties.status&dir=DESC&filter=<?=$filter?>">&#9660;</a></th>
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
onclick="DoNav('editProperty.php?property_id=<?=$row['PROPERTY_ID']?>');"
<?php if(!is_float($i/2)) {?>class="alt"<?php } ?>>
<td class="center">
<a href="editProperty.php?property_id=<?=$row['PROPERTY_ID']?>">
<img src='images/edit.png' alt='Edit Property' title='Edit Property' /></a>
</td>
<td><?=$row['CENTER_NAME']?></td>
<td><?=$row['CITY']?></td>
<td><?=$row['COMPANY_NAME']?></td>
<td><?=$row['CONTACT_NAME']?></td>
<td><?=$row['OFFICE_PHONE']?></td>
<td><?=$row['DATE_ADDED']?></td>
<td><?php if($row['LAST_UPDATED']=="") echo ""; else echo date("Y-m-d h:i A", strtotime($row['LAST_UPDATED']))?></td>
<td>
<?php
$description = "Not Set";
if ($row['BUYER_DESCRIPTION']!="") {
	$description = "Set";
}
echo $description;
?>
</td>
<td>
<?php
$pname = "PHOTO_";
$count = 0;
for ($j=1; $j<=6; $j++) {
	if($row[$pname . $j]!="") {
		$count++;
	}
}
echo $count;
?>
</td>
<td><?=$row['STATUS']?></td>
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
<div clas="small" align="center">Total Properties: <?=$total_records?></div>

<?php
//Clean up all open results/connections
$result->close();
$mysqli->close();
?>

<!-- End Content -->
</div>
</body>
</html>