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
				<td valign="bottom" style="width: 35%; text-align: right;">
					<strong>Lead ID:</strong> <?php echo $lead_id; ?>
				</td>
			</tr>
			<table>

			<?php If($_SESSION['TEMP_VAR']['UPDATE_TASK']) { ?>
					<br />
					<div class="alert">
					  <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span> 
					  <?php echo $_SESSION['TEMP_VAR']['UPDATE_TASK']['MESSAGE']; ?>
					</div>		
					<?php unset($_SESSION['TEMP_VAR']['UPDATE_TASK']); ?>
			<?php } ?>

			<form name="form1" method="post" action="transactionCoordinatorHelper.php">
			<input type="hidden" name="update_task" value="1">
			<input type="hidden" name="lead_id" value="<?php echo $lead_id; ?>">
			<?php 

				$task_d =  $mysqli->query("SELECT * FROM lead_trans_task_list WHERE lead_id = " .$lead_id. " ORDER BY id DESC LIMIT 1") or die(mysql_error());
				$task_row = mysqli_fetch_array($task_d);
				$task_unserialize = unserialize($task_row['task_list']);

				$task_arr = array();
				$task_arr = array(
						1  => 'Intro Email',
						2  => 'Review Executed Contract and send to Title',
						3  => 'Make sure we have SD, LBP, SURVEY, LEASE', 
						4  => 'Add to Sales Boards-Online and Visual',
						5  => 'Make a Property Folder in Google Drive',
						6  => 'Upload Docs, Pics, Move to Oppty Folder to <strong>Buying the House</strong>',
						7  => 'Add to Google Calendar a reminder for End of Option and Tentative Close Date',
						8  => 'Email link to - Link Here and Link Here',
						9  => 'Schedule Project Mgr Walk Thru-only if necessary with aquisition sales',
						10 => 'Schedule Pics and Lock Box Placement at Property',
						11 => 'Upload in Back Agent and request CDA',
						12 => 'Send Title Commitment with PLV Intake to Attorney to draw lender docs',
						13 => 'Review HUD before closing and forward to LINK HERE to approve',
						14 => 'Obtain Writing Instructions from title and give to Frank for',
						15 => 'Secure Closing Date and update on Calendar for Brandon',
						16 => 'Provide Post Closing Instruction to Title',
						17 => 'Notify Leadership of Funding and Liz in account with acct# ',
						18 => 'Order Insurance for date we closed and turn on utilities',
						19 => 'Upload final HUD with date in Google Drive for property and upload in Backagent',
						20 => 'Update Sales Board online and Visual Wholesale or Flip',
						21 => 'File away hard copy or forward to PM or flip',
						22 => 'Email Monitizer Department that sale has been completed',
						23 => 'Send Shane email to update to update database for Acquisition Team/Send Feedback Email',
						24 => 'Upload EOI in google under insurance file and update utility spreadshit with new address',
						25 => 'Seller Leaseback Deposit if applicable',
						26 => 'Email Frank, Send Thank You card to lender and update social media of purchase'
					);

				$task_arr2 = array();
				$task_arr2 = array(
						27 => 'Draw up Termination of Contract & Release of EM and have buyer sign it',
						28 => 'Send to seller or seller’s agent',
						29 => 'Send to Title Company',
						30 => 'Erase off the board',
						31 => 'Throw away hard copy folder in bin',
						32 => 'Move property folder to “Potential Property” folder'
					);
			?>

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
                    	<?php foreach($task_arr as $tkey => $t) { ?>
								<input <?php echo isset($task_unserialize[$tkey]) ? 'checked' : ''; ?> type="checkbox" name="task[<?php echo $tkey; ?>]" value="1"> <?php echo $t; ?><br />
						<?php } ?>
						<br />
                    </td>
                    <td style="width: 50%;" valign="top">
                    	<?php foreach($task_arr2 as $tkey2 => $t2) { ?>
								<input <?php echo isset($task_unserialize[$tkey2]) ? 'checked' : ''; ?> type="checkbox" name="task[<?php echo $tkey2; ?>]" value="1"> <?php echo $t2; ?><br />
						<?php } ?>        
						<br />          	
						<table class="" width="100%">
							<tr><td colspan="2"><hr /></td></tr>
							<tr>
								<td width="25%">Roof/Insurance Claim:</td>
								<td width="75%">
									<select id="roof_ins_claim" name="roof_ins_claim">
										<option <?php echo $task_row['roof_ins_claim'] == "Yes" ? 'selected' : ''; ?> value="Yes">Yes</option>								
										<option <?php echo $task_row['roof_ins_claim'] == "No" ? 'selected' : ''; ?> value="No">No</option>								
									</select>
								</td>
							</tr>
							<tr>
								<td width="25%">Combo: </td>
								<td width="75%"><input type="text" style="width: 350px;" name="combo" id="combo" value="<?php echo $task_row['combo']; ?>"></td>
							</tr>
						</table>
                    </td>
                </tr>
            </table>
            </form>
            <br />

			<?php If($_SESSION['TEMP_VAR']['CONTRACT_PAPERWORK']) { ?>
					<br />
					<div class="alert">
					  <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span> 
					  <?php echo $_SESSION['TEMP_VAR']['CONTRACT_PAPERWORK']['MESSAGE']; ?>
					</div>		
					<?php unset($_SESSION['TEMP_VAR']['CONTRACT_PAPERWORK']); ?>
			<?php } ?>

			<?php If($_SESSION['TEMP_VAR']['CONTRACT_PAPERWORK_DEL']) { ?>
					<br />
					<div class="alert">
					  <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span> 
					  <?php echo $_SESSION['TEMP_VAR']['CONTRACT_PAPERWORK_DEL']['MESSAGE']; ?>
					</div>		
					<?php unset($_SESSION['TEMP_VAR']['CONTRACT_PAPERWORK_DEL']); ?>
			<?php } ?>

			<?php
				//$attachments_result = $mysqli->query("SELECT * FROM lead_attachments WHERE lead_id= ".$lead_id." AND type = 2 ORDER BY title ASC") or die(mysql_error());
			?>   
			<!--         
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
							<a target="_blank" href="files/contract_paperworks/<?php echo $row_attach['filename']; ?>"><img src='images/k-view-icon.png' alt='View Attachment' title='View Attachment' /></a> 
							<a href="transactionCoordinatorHelper.php?lead_id=<?php echo $lead_id; ?>&del_paperwork=1&attach_id=<?php echo $row_attach['id']; ?>&file=<?php echo $row_attach['filename']; ?>" onclick="return confirm('Are you sure you want to remove this file?')"><img src='images/delete.png' alt='Delete Contract Paperwork' title='Delete Contract Paperwork' /></a>
							<a href="files/contract_paperworks/<?php echo $row_attach['filename']; ?>"  onclick="window.open('files/contract_paperworks/<?php echo $row_attach['filename']; ?>', 'newwindow', 'width=800, height=800'); return false;"><img src='images/k-view-icon.png' alt='View Contract Paperwork' title='View Contract Paperwork' /></a>
						</td>
						<td style="width: 90%">
							<a href="files/contract_paperworks/<?php echo $row_attach['filename']; ?>"  onclick="window.open('files/contract_paperworks/<?php echo $row_attach['filename']; ?>', 'newwindow', 'width=800, height=800'); return false;"><?php echo $row_attach['title']; ?></a>
						</td>
					</tr>
				<?php } ?>
				<?php } else { ?>
							<tr><td colspan="2">No Attched File</td></tr>
				<?php } ?>
            </table> 
            </form>
            -->

            <table width="100%">
            	<tr>
					<?php
						$attachments_result = $mysqli->query("SELECT * FROM lead_attachments WHERE lead_id= ".$lead_id." AND type = 2 ORDER BY title ASC") or die(mysql_error());
					?>              	
            		<td width="50%" valign="top">
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
									<td style="width: 90%">
										<a href="files/contract_paperworks/<?php echo $row_attach['filename']; ?>"  onclick="window.open('files/contract_paperworks/<?php echo $row_attach['filename']; ?>', 'newwindow', 'width=800, height=800'); return false;"><?php echo $row_attach['title']; ?></a>
									</td>
								</tr>
							<?php } ?>
							<?php } else { ?>
										<tr><td colspan="2">No Attched File</td></tr>
							<?php } ?>
			            </table> 
			            </form>            			
            		</td>
					<?php
						$attachments_hud_result = $mysqli->query("SELECT * FROM lead_attachments WHERE lead_id= ".$lead_id." AND type = 3 ORDER BY title ASC") or die(mysql_error());
					?>              		
            		<td width="50%" valign="top">
						<form name="form1" method="post" action="transactionCoordinatorHelper.php" enctype="multipart/form-data">
						<input type="hidden" name="add_hud" value="1">
						<input type="hidden" name="lead_id" value="<?php echo $lead_id; ?>">
			            <table class="grid" width="100%">
			                <tr><td colspan="2"><h3>Hud</h3></td></tr>
			                <tr>
			                	<td colspan="2" style="text-align: left;"><input type="file" name="fileToUpload" id="fileToUpload"> <input class="button" type="submit" name="submit_file" value="Save File" /></td>
			                </tr>
			                <tr>
			                	<th>Actions</th>
			                	<th>Title</th>
			                </tr>
			                <?php if($attachments_hud_result->num_rows > 0) { ?>
							<?php
								while ($row_attach_hud = mysqli_fetch_array($attachments_hud_result)) {
								foreach ($row_attach_hud AS $key => $value) {
									$row_attach_hud[$key] = stripslashes($value);
								}				
							?>
								<tr>
									<td style="width: 10%;">
										&nbsp;&nbsp;
										<a href="transactionCoordinatorHelper.php?lead_id=<?php echo $lead_id; ?>&del_paperwork=1&attach_id=<?php echo $row_attach_hud['id']; ?>&file=<?php echo $row_attach_hud['filename']; ?>" onclick="return confirm('Are you sure you want to remove this file?')"><img src='images/delete.png' alt='Delete Hud' title='Delete Hud' /></a>
										<a href="files/hud/<?php echo $row_attach_hud['filename']; ?>"  onclick="window.open('files/hud/<?php echo $row_attach_hud['filename']; ?>', 'newwindow', 'width=800, height=800'); return false;"><img src='images/k-view-icon.png' alt='View Hud' title='View Hud' /></a>
									</td>
									<td style="width: 90%">
										<a href="files/hud/<?php echo $row_attach_hud['filename']; ?>"  onclick="window.open('files/hud/<?php echo $row_attach_hud['filename']; ?>', 'newwindow', 'width=800, height=800'); return false;"><?php echo $row_attach_hud['filename']; ?></a>
									</td>
								</tr>
							<?php } ?>
							<?php } else { ?>
										<tr><td colspan="2">No Attched File</td></tr>
							<?php } ?>
			            </table> 
			            </form>        			
            		</td>
            	</tr>
            </table>

            <br /><br />    
        </div>       
    </body>
</html>