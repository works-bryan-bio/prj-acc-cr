<?php
require_once("include/checklogin.php");
require_once('include/db_connect.php');
require_once('include/pagination.php');

// store the lead_id if set
$lead_id = null;
$folder  = null;
if (isset($_GET['lead_id'])) {
	$lead_id = $_GET['lead_id'];
}
if(isset($_GET['folder'])) {
	$folder = $_GET['folder'];
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
        <link rel="stylesheet" type="text/css" href="css/colorbox.css"/>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
      	<script type="text/javascript" src="js/tigra_calendar/calendar_us.js"></script> 
      	<script type="text/javascript" src="js/colorbox/jquery.colorbox.js"></script> 
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

		$size = 20;
		$link = "Pictures.php?lead_id=".$lead_id."&folder=".$folder."&page=%s";
		$orderby = "title";
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
		$filter = "";
		if (isset($_GET['filter'])){
		    $filter = $_GET['filter'];
		}
		$pagination = new Pagination();
		$pagination->setLink($link);
		$pagination->setPage($page);
		$pagination->setSize($size);
		$result        = $mysqli->query("SELECT COUNT(*) FROM lead_pictures WHERE lead_id = ".$lead_id." AND folder = '".$folder."'");
		$row           = $result->fetch_row();
		$total_records = $row[0];
		$pagination->setTotalRecords($total_records);

		$result_query = $mysqli->query("SELECT * FROM lead_pictures WHERE lead_id = ".$lead_id." AND folder = '".$folder."' ORDER BY " . $orderby . " " . $dir . " " . $pagination->getLimitSql()) or die(mysqli_error());		

    ?>
    <body>
        <div id="header"><?php require "header.inc.php"; ?></div>
        <div id="menu"><?php require "_menu.inc.php"; ?></div>
        <div id="content">

		<script>
			$.noConflict();
			$(document).ready(function(){
				//$(".group1").colorbox({rel:'group1'});
				$(".group1").colorbox({rel:'group1', height:"95%"});
			});
		</script>        

		<div style="float:right;margin-right:10px;">	
			<?php if( !empty($prop_previous) ){ ?>
					<a class="button" href="Pictures.php?lead_id=<?php echo $prop_previous['lead_id']; ?>" style="text-decoration:none;color:#333;font-weight:normal;margin:0px;">&#xab;</a>
			<?php } ?>
			<?php if( !empty($prop_next) ){ ?>
					<a class="button" href="Pictures.php?lead_id=<?php echo $prop_next['lead_id']; ?>" style="text-decoration:none;color:#333;font-weight:normal;margin:0px;">&#xbb;</a>
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
					<a href="transactionCoordinator.php?lead_id=<?php echo $lead_id; ?>">Transaction Coordinator</a>&nbsp;|&nbsp;
					<a href="Pictures.php?lead_id=<?php echo $lead_id; ?>">Pictures</a>
				</td>
				<td valign="bottom" style="width: 35%; text-align: right;">
					<strong>Lead ID:</strong> <?php echo $lead_id; ?>
				</td>
			</tr>
		<table>
        
        <?php if(empty($folder)) { ?>
        		<table class="grid">
        			<tr><td></td></tr>
        		</table>
		        <table class="">
		            <tr>
		            	<td>
		            		<div style="text-align: center; width: 200px;">
		            			<a href="Pictures.php?lead_id=<?php echo $lead_id; ?>&folder=before"><img src='images/folder.png' alt='Before Folder' title='Before Folder' /><br />Before</a>
		            		</div>
		            	</td>
		            	<td>
		            		<div style="text-align: center; width: 200px;">
		            			<a href="Pictures.php?lead_id=<?php echo $lead_id; ?>&folder=after"><img src='images/folder.png' alt='After Folder' title='After Folder' /><br />After</a>
		            		</div>
		            	</td>
		            </tr>
		        </table>      
        <?php } ?>  

        <?php if( !empty($folder) && !empty($lead_id) )  { ?>
        		<form name="form1" method="post" action="PictureHelper.php" enctype="multipart/form-data">
				<input type="hidden" name="add_picture" value="1">
				<input type="hidden" name="folder" value="<?php echo $folder; ?>">
				<input type="hidden" name="lead_id" value="<?php echo $lead_id; ?>">        		
        		<table class="grid">
        			<tr>
		            	<td colspan="2">
		            		<input type="file" name="fileToUpload" id="fileToUpload">
		            		<!-- <input type="text" name="file_title" id="file_title">  -->
		            		<input class="button" type="submit" name="submit_file" value="Upload" />
		            		<a class="button" href="Pictures.php?lead_id=<?php echo $lead_id; ?>">Back</a>
		            	</td>
		            </tr>
        		</table>
        		</form>
        		<br />

			<?php If($_SESSION['TEMP_VAR']['LEAD_PICTURE']) { ?>
					<div class="alert">
					  <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span> 
					  <?php echo $_SESSION['TEMP_VAR']['LEAD_PICTURE']['MESSAGE']; ?>
					</div>		
					<?php unset($_SESSION['TEMP_VAR']['LEAD_PICTURE']); ?>
			<?php } ?>

			<?php If($_SESSION['TEMP_VAR']['LEAD_PICTURE_DEL']) { ?>
					<div class="alert">
					  <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span> 
					  <?php echo $_SESSION['TEMP_VAR']['LEAD_PICTURE_DEL']['MESSAGE']; ?>
					</div>		
					<?php unset($_SESSION['TEMP_VAR']['LEAD_PICTURE_DEL']); ?>
			<?php } ?>			

		        <table class="grid">
		            <tr>
		            	<td colspan="2">
		            		<h3><?php echo ucfirst($folder); ?> Folder</h3> 
		            	</td>
		            </tr>
		            <tr>
		                <th style="width:5%;">Actions</th>
		                <th>Name&nbsp;<a href="Pictures.php?lead_id=<?php echo $lead_id; ?>&folder=<?php echo $folder; ?>&orderby=filename&dir=ASC&filter=<?=$filter?>">&#9650;</a>&nbsp;<a href="Pictures.php?lead_id=<?php echo $lead_id; ?>&folder=<?php echo $folder; ?>&orderby=filename&dir=DESC&filter=<?=$filter?>">&#9660;</a></th>                    
		            </tr>
		            <?php while($row = mysqli_fetch_array($result_query)){ ?>
		            <tr>
		                <td class="center">
		               		<a href="PictureHelper.php?lead_id=<?php echo $lead_id; ?>&del_pic=1&pic_id=<?php echo $row['id']; ?>&file=<?php echo $row['filename']; ?>&folder=<?php echo $folder; ?>" onclick="return confirm('Are you sure you want to remove this image?')"><img src='images/delete.png' alt='Delete Attachment' title='Delete Attachment' /></a>
		                </td>
		                <td><a class="group1" href="files/lead_img/<?php echo $folder; ?>/<?php echo $row['filename']; ?>" title=""><?php echo $row['filename']; ?></a></td>
		            </tr>
		            <?php
		                }
		            ?>
		        </table>     

	            <div class="pagination">
	                <?php
	                    $navigation = $pagination->create_links();
	                    echo $navigation;
	                    $result_query->close();
	                ?>
	            </div>
	            <div clas="small" align="center">Total Record(s): <?=$total_records?></div>		           

        <?php } ?>
   
        </div>       
    </body>
</html>