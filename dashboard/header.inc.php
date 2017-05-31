<?php
require_once('include/db_connect.php');
?>
<div style="float:left">
<img src="images/logo.png" />
</div>

<div style="position: absolute; left: 50%; transform: translateX(-50%);">
<?php
$result = $mysqli->query("SELECT COUNT(LEAD_ID) FROM leads WHERE USERNAME='Not Assigned'") or die(mysql_error());
$row = $result->fetch_row();
$total_leads = $row[0];
if ($total_leads > 0) {
	?>
	<span style="color:red;font-weight:bold;font-size:1.5em;">** New Unassigned Leads **</span>
	<?php
}
?>
</div>