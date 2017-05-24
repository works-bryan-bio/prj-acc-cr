<?php
require_once("include/checklogin.php");
require_once('include/db_connect.php');
require_once('include/pagination.php');

// store the lead_id if set
$lead_id = null;
if (isset($_GET['lead_id'])) {
	$lead_id = $_GET['lead_id'];
}

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="refresh" content="600">
        <title>SimpleHouseSolutions.com - Transaction Coordinator</title>
        <link rel="shortcut icon" href="/favicon.ico" />
        <link rel="stylesheet" type="text/css" href="css/dashboard.css"/>
        <link rel="stylesheet" type="text/css" href="css/dashboard_menu.css"/>
       <link rel="stylesheet" type="text/css" href="js/tigra_calendar/calendar.css">
      <script type="text/javascript" src="js/tigra_calendar/calendar_us.js"></script> 
    </head>
    <?php 

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
    <body>
        <div id="header"><?php require "header.inc.php"; ?></div>
        <div id="menu"><?php require "menu.inc.php"; ?></div>
        <div id="content">

		<div style="float:right;margin-right:10px;">	
			<?php if( !empty($prop_previous) ){ ?>
					<a class="button" href="transactionCoordinator.php?lead_id=<?php echo $prop_previous['lead_id']; ?>" style="text-decoration:none;color:#333;font-weight:normal;margin:0px;">&#xab;</a>
			<?php } ?>
			<?php if( !empty($prop_next) ){ ?>
					<a class="button" href="transactionCoordinator.php?lead_id=<?php echo $prop_next['lead_id']; ?>" style="text-decoration:none;color:#333;font-weight:normal;margin:0px;">&#xbb;</a>
			<?php } ?>
		</div>
		<br /><br />

			<table class="input" width="100%">
			<tr>
				<td valign="bottom" style="width: 65%">
					<a href="editLead.php?lead_id=<?=$lead_id?>">Client Information</a>&nbsp;|&nbsp;
					<a href="searchReport.php?lead_id=<?=$lead_id?>">Search Report</a>&nbsp;|&nbsp;
					<a class="poplight" href="#?w=700" rel="email_popup">Email Contact</a>&nbsp;|&nbsp;
					<a href="https://mail.google.com/mail/?view=cm&fs=1&to=<?=$prop['CLIENT_EMAIL']?>" target="_blank">Gmail</a>&nbsp;|&nbsp;
					<a href="https://fathom.backagent.net/" target="_new">Backagent</a>&nbsp;|&nbsp;
					<a href="transactionCoordinator.php?lead_id=<?php echo $lead_id; ?>">Transaction Coordinator</a>
				</td>
			</tr>
			<table>
			<form name="form1" method="post" action="transactionCoordinatorHelper.php">
			<input type="hidden" name="update_task" value="1">
            <table class="grid">
                <tr><td colspan="2"><h3>Task</h3></td></tr>
                <tr>
                    <td style="width: 50%;">
						<input class="button" type="submit" name="update_task_button" value="Update" />
                    </td>
                    <td style="width: 50%;">
						<p><strong>Please complete if contract is terminated</strong></p>                  	
                    </td>
                </tr>
                <tr>
                    <td style="width: 50%;" valign="top">
						<input type="checkbox" name="vehicle" value="Bike">Intro Email<br>
						<input type="checkbox" name="vehicle" value="Car">Review Executed Contract and send to Title<br />
						<input type="checkbox" name="vehicle" value="Car">Review Executed Contract and send to Title<br />
						<input type="checkbox" name="vehicle" value="Car">Make sure we have SD, LBP, SURVEY, LEASE<br />
						<input type="checkbox" name="vehicle" value="Car">Add to Sales Boards-Online and Visual<br />
						<input type="checkbox" name="vehicle" value="Car">Make a Property Folder in Google Drive<br />
						<input type="checkbox" name="vehicle" value="Car">Upload Docs, Pics, Move to Oppty Folder to <strong>Buying the House</strong><br />
						<input type="checkbox" name="vehicle" value="Car">Add to Google Calendar a reminder for End of Option and Tentative Close Date<br />
						<input type="checkbox" name="vehicle" value="Car">Email link to - Link Here and Link Here <br />
						<input type="checkbox" name="vehicle" value="Car">Schedule Project Mgr Walk Thru-only if necessary with aquisition sales<br />
						<input type="checkbox" name="vehicle" value="Car">Schedule Pics and Lock Box Placement at Property<br />
						<input type="checkbox" name="vehicle" value="Car">Upload in Back Agent and request CDA<br />
						<input type="checkbox" name="vehicle" value="Car">Send Title Commitment with PLV Intake to Attorney to draw lender docs <br />
						<input type="checkbox" name="vehicle" value="Car">Review HUD before closing and forward to LINK HERE to approve<br />
						<input type="checkbox" name="vehicle" value="Car">Obtain Writing Instructions from title and give to Frank for<br />
						<input type="checkbox" name="vehicle" value="Car">Secure Closing Date and update on Calendar for Brandon<br />
						<input type="checkbox" name="vehicle" value="Car">etc.<br />
						<input type="checkbox" name="vehicle" value="Car">etc.<br />
						<input type="checkbox" name="vehicle" value="Car">etc.<br />
						<input type="checkbox" name="vehicle" value="Car">etc.<br />
						<input type="checkbox" name="vehicle" value="Car">etc.<br />
						<input type="checkbox" name="vehicle" value="Car">etc.<br />
                    </td>
                    <td style="width: 50%;" valign="top">
						<input type="checkbox" name="vehicle" value="Car">etc.<br />
						<input type="checkbox" name="vehicle" value="Car">etc.<br />
						<input type="checkbox" name="vehicle" value="Car">etc.<br />
						<input type="checkbox" name="vehicle" value="Car">etc.<br />
						<input type="checkbox" name="vehicle" value="Car">etc.<br />
						<input type="checkbox" name="vehicle" value="Car">etc.<br />                   	
                    </td>
                </tr>
            </table>
            </form>
            <br />

			<?php
				$attachments_result = $mysqli->query("SELECT * FROM lead_attachments WHERE lead_id= ".$lead_id." AND type = 2 ORDER BY title ASC") or die(mysql_error());
			?>            
			<form name="form1" method="post" action="transactionCoordinatorHelper.php" enctype="multipart/form-data">
			<input type="hidden" name="add_contractpaper_work" value="1">
			<input type="hidden" name="lead_id" value="<?php echo $lead_id; ?>">
            <table class="grid">
                <tr><td colspan="2"><h3>Contract Paperwork</h3></td></tr>
                <tr>
                	<td colspan="2" style="text-align: left;"><input type="file" name="fileToUpload" id="fileToUpload"> <input type="text" name="file_title" id="file_title"> <input class="button" type="submit" name="submit_file" value="Save File" /></td>
                </tr>
                <tr>
                	<th>Actions</th>
                	<th>Title</th>
                </tr>
                <?php if($attachments_result->num_rows > 0) { ?>
				<?php
					while ($row_attach = mysqli_fetch_array($attachments_result)) {
					foreach ($row_attach AS $key => $value) {
						$row_attach[$key] = stripslashes($value);
					}				
				?>
					<tr>
						<td style="width: 10%;">
							&nbsp;&nbsp;
							<!-- <a target="_blank" href="files/contract_paperworks/<?php echo $row_attach['filename']; ?>"><img src='images/k-view-icon.png' alt='View Attachment' title='View Attachment' /></a>  -->
							<a href="transactionCoordinatorHelper.php?lead_id=<?php echo $lead_id; ?>&del_paperwork=1&attach_id=<?php echo $row_attach['id']; ?>&file=<?php echo $row_attach['filename']; ?>" onclick="return confirm('Are you sure you want to remove this file?')"><img src='images/delete.png' alt='Delete Contract Paperwork' title='Delete Contract Paperwork' /></a>
							<a href="files/contract_paperworks/<?php echo $row_attach['filename']; ?>"  onclick="window.open('files/contract_paperworks/<?php echo $row_attach['filename']; ?>', 'newwindow', 'width=800, height=800'); return false;"><img src='images/k-view-icon.png' alt='View Contract Paperwork' title='View Contract Paperwork' /></a>
						</td>
						<td style="width: 90%"><a target="_blank" href="files/contract_paperworks/<?php echo $row_attach['filename']; ?>"><?php echo $row_attach['title']; ?></a></td>						
					</tr>
				<?php } ?>
				<?php } else { ?>
							<tr><td colspan="2">No Attched File</td></tr>
				<?php } ?>
            </table> 
            </form>
            <br /><br />    
        </div>       
    </body>
</html>