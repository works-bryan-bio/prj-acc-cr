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
	<li><a href="javascript:void(0);">Email</a>
        <ul>
            <li><a class="poplight" href="#?w=700" rel="mass_email_popup">Mass</a></li>
            <li><a href="adminTemplates.php">Drip</a></li>
        </ul>
    </li>
	<?php } ?>
</ul>
<?php require_once('modal.forms.php'); ?>