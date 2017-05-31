<?php
require_once('include/session.php');
?>
<ul id="menu">
    <li><a href="index.php">Dashboard</a></li>
    <li><a href="leadSearch.php">Lead Search</a>
		<?php if ($session->isMaster()) { ?>
			<ul>
				<li><a href="include/exportLeads.php" target="_blank">Export Leads</a></li>
			</ul>
		<?php } ?>
    </li>
    <li><a href="javascript:void(0);">Reports</a>
        <ul>
            <li><a href="forecasting.php">Forecasting</a></li>
			<?php if ($session->isAdmin() || $session->isMaster()) { ?>
	            <li><a href="report.php">Report</a></li>
			<?php } ?>
        </ul>
    </li>
	<?php if ($session->isAdmin() || $session->isMaster() || $session->isAssetManager()) { ?>
		<!--        <li><a href="javascript:void(0);">Accounts</a>
					<ul>
						<li><a href="invoiceDash.php">Invoices</a></li>
						<li><a href="accountsDash.php">Accounts</a></li>
					</ul>
				</li>-->
		<li><a href="javascript:void(0);">Properties</a>
			<ul>
				<li><a href="listProperties.php">All Properties</a></li>
				<li><a href="addProperty.php">Add Property</a></li>
			</ul>
		</li>
		<li><a href="javascript:void(0);">Providers</a>
			<ul>
				<li><a href="listProviders.php">All Providers</a></li>
				<li><a href="addProvider.php">Add Providers</a></li>
			</ul>
		<li>
	<?php } ?>
	<?php if ($session->isAdmin() || $session->isMaster()) { ?>
		<li><a href="javascript:void(0);">Lead Sources</a>
			<ul>
				<li><a href="listAffiliates.php">All Lead Sources</a></li>
				<li><a href="addAffiliate.php">Add Lead Source</a></li>
			</ul>
		</li>
	<?php } ?>
    <li><a href="javascript:void(0);">User</a>
        <ul>
            <li><a href="editUser.php">Edit My User</a></li>
			<?php if ($session->isAdmin() || $session->isMaster()) { ?>
	            <li><a href="adminUsers.php">User Administration</a></li>
			<?php } ?>
            <li><a href="process.php">Logout</a></li>
        </ul>
    </li>
	<?php if ($session->isAdmin() || $session->isMaster()) { ?>
	<li><a href="javascript:void(0);">Dashboard Admin</a>
        <ul>
            <li><a href="adminTemplates.php">Email Templates</a></li>
        </ul>
    </li>
	<?php } ?>
	<?php if ($session->isAdmin() || $session->isMaster()) { ?>
	<li><a href="javascript:void(0);">Email Blast</a>
        <ul>
            <li><a class="modal-poplight" href="#?w=700" rel="mass_email_popup">Mass</a></li>
            <li><a href="dripCampaign.php">Drip</a></li>
        </ul>
    </li>
	<?php } ?>
</ul>
<?php 
$notification_date = date("Y-m-d");
$minutesBefore     = strtotime('-5 minutes');
$minutesBefore     = date("H:i:00",$minutesBefore);
$result_followup_notification = $mysqli->query("SELECT LEAD_ID, FIRST_NAME, LAST_NAME, FOLLOW_UP_TIME FROM leads WHERE FOLLOW_UP_DATE='{$notification_date}' AND FOLLOW_UP_TIME >= '{$minutesBefore}'") or die(mysql_error());
$is_with_followup_notification = false;

if( $result_followup_notification->num_rows > 0 ){
	$is_with_followup_notification = true;
}
?>
<?php require_once('modal.forms.php'); ?>
<?php if( $is_with_followup_notification ){ ?>
<a class="modal-poplight button modal-followup-notification" style="display:none;" href="#?w=700" rel="followup_notification_popup"></a>
<script>	
	$(function(){
		$(".modal-followup-notification").click();
	});
</script>
<?php } ?>
<div style="float:right">
<input class="button" type="button" value="Logout" onclick="window.location.href='process.php'" />
</div>
<div id="followup_notification_popup" class="popup_block">
	<h3>Leads for today's followup</h3><br/>
	<table class="grid">
		<tr>
			<th>Name</th>
			<th>Followup Time</th>
			<th></th>
		</tr>
		<?php while($row = mysqli_fetch_array($result_followup_notification)){ ?>
			<tr>
				<td><b><?php echo $row['FIRST_NAME'] . ' ' . $row['LAST_NAME']; ?></b></td>
				<td><b><?php echo $row['FOLLOW_UP_TIME'] ?></b></td>
				<td align='center'><a href="editLead.php?lead_id=<?= $row['LEAD_ID'] ?>"><img src='images/edit.png' alt='Edit Lead' title='Edit Lead' /></a></td>
			</tr>
		<?php } ?>
	</table>	
</div>