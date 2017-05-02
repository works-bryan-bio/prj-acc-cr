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
<script type="text/javascript">
function confirm_reject() {
	return confirm("Are you sure you want to reject this invoice?");
}
</script>
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
$link = "invoiceDash.php?page=%s";
$orderby = "date_approved ASC, date_submitted ASC";
if (isset($_GET['orderby'])){
    $orderby = $_GET['orderby'];
		$link .= "&orderby=" . $orderby;
}
$dir = "";
if (isset($_GET['dir'])){
    $dir = $_GET['dir'];
		$link .= "&dir=" . $dir;
}
$page = 1;
if (isset($_GET['page'])){
    $page = (int) $_GET['page'];
}
$pagination = new Pagination();
$pagination->setLink($link);
$pagination->setPage($page);
$pagination->setSize($size);
$result = $mysqli->query("SELECT COUNT(*) FROM invoices WHERE date_submitted IS NOT NULL AND DATE_APPROVED IS NULL");
$row = $result->fetch_row();
$total_records = $row[0];
$pagination->setTotalRecords($total_records);
$sql = "SELECT * FROM invoices " .
				"JOIN leads ON leads.lead_id=invoices.lead_id " .
				"JOIN properties ON invoices.property_id=properties.property_id " .
				"WHERE date_submitted IS NOT NULL ORDER BY " . $orderby . " " . $dir . " " . $pagination->getLimitSql();
$result = $mysqli->query($sql) or die(mysqli_error());
?>

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
	<th>Center Name&nbsp;<a href="invoiceDash.php?orderby=center_name&dir=ASC">&#9650;</a>&nbsp;<a href="invoiceDash.php?orderby=center_name&dir=DESC">&#9660;</a></th>
	<th>Property Type&nbsp;<a href="invoiceDash.php?orderby=properties.property_type&dir=ASC">&#9650;</a>&nbsp;<a href="invoiceDash.php?orderby=properties.property_type&dir=DESC">&#9660;</a></th>
	<th>Months Signed&nbsp;<a href="invoiceDash.php?orderby=months_signed&dir=ASC">&#9650;</a>&nbsp;<a href="invoiceDash.php?orderby=months_signed&dir=DESC">&#9660;</a></th>
	<th>Billing Frequency&nbsp;<a href="invoiceDash.php?orderby=billing_freq&dir=ASC">&#9650;</a>&nbsp;<a href="invoiceDash.php?orderby=billing_freq&dir=DESC">&#9660;</a></th>
	<th>Invoice Total&nbsp;<a href="invoiceDash.php?orderby=invoice_total&dir=ASC">&#9650;</a>&nbsp;<a href="invoiceDash.php?orderby=invoice_total&dir=DESC">&#9660;</a></th>
	<th>Lead Owner&nbsp;<a href="invoiceDash.php?orderby=username&dir=ASC">&#9650;</a>&nbsp;<a href="invoiceDash.php?orderby=username&dir=DESC">&#9660;</a></th>
	<th>Submitted&nbsp;<a href="invoiceDash.php?orderby=date_submitted&dir=ASC">&#9650;</a>&nbsp;<a href="invoiceDash.php?orderby=date_submitted&dir=DESC">&#9660;</a></th>
	<th>Approved&nbsp;<a href="invoiceDash.php?orderby=date_approved&dir=ASC">&#9650;</a>&nbsp;<a href="invoiceDash.php?orderby=date_approved&dir=DESC">&#9660;</a></th>
	<th>Actions</th>
</tr>
<?php
$i = 0;
while($row = mysqli_fetch_array($result)){
	foreach($row AS $key => $value) {
		$row[$key] = stripslashes($value);
	}
	$approved = $row['DATE_APPROVED']
?>
<tr onmouseover="ChangeColor(this, true);this.style.textDecoration='none';" 
onmouseout="ChangeColor(this, false);this.style.textDecoration='none';" 
<?php 
if ($approved!=null) {
	echo "class=\"Good\"";
} else if(!is_float($i/2)) {
	echo "class=\"alt\""; 
} 
?>
>
<td><?=$row['CENTER_NAME']?></td>
<td><?=$row['PROPERTY_TYPE']?></td>
<td><?=$row['MONTHS_SIGNED']?></td>
<td><?=$row['BILLING_FREQ']?></td>
<td><?=$row['INVOICE_TOTAL']?></td>
<td><?=$row['USERNAME']?></td>
<td><?=date("Y-m-d h:i A", strtotime($row['DATE_SUBMITTED']))?></td>
<td><?php if($approved!=null) echo date("Y-m-d h:i A", strtotime($row['DATE_APPROVED'])); ?></td>
<td nowrap="nowrap">
<input class="button" type="button" id="view" name="view" value="View" onclick="DoNav('invoiceDetails.php?lead_id=<?=$row['LEAD_ID']?>');" />&nbsp;
<input class="button" type="button" id="approve" name="approve" value="Approve" onclick="DoNav('invoiceDashHelper.php?action=approve&lead_id=<?=$row['LEAD_ID']?>');" <?php if($approved!=null) echo "disabled=\"disabled\""; ?>/>&nbsp;
<input class="button" type="button" id="reject" name="reject" value="Reject" onclick="if(confirm_reject()) DoNav('invoiceDashHelper.php?action=reject&lead_id=<?=$row['LEAD_ID']?>');" />&nbsp;
<input class="button" type="button" id="print" name="print" value="Print" onclick="DoNav('invoiceDashHelper.php?action=generatePDF&lead_id=<?=$row['LEAD_ID']?>');" />
</td>
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
<div clas="small" align="center">Total Invoices: <?=$total_records?></div>

<?php
//Clean up all open results/connections
$result->close();
$mysqli->close();
?>

<!-- End Content -->
</div>
</body>
</html>