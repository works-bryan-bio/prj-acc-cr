<?php
require_once("include/checklogin.php");
require_once('include/db_connect.php');
require_once('include/pagination.php');

// store the lead_id if set
$property_id = null;
$folder  = null;
if (isset($_GET['property_id'])) {
	$property_id = $_GET['property_id'];
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
        <title>SimpleHouseSolutions.com - Property Pictures</title>
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
		//Next and Previous Record
		$result_next_record     = $mysqli->query("SELECT PROPERTY_ID FROM properties WHERE PROPERTY_ID >" . $property_id . " ORDER BY PROPERTY_ID ASC LIMIT 1")
			or die(mysqli_error());
		$result_previous_record = $mysqli->query("SELECT PROPERTY_ID FROM properties WHERE PROPERTY_ID <" . $property_id . " ORDER BY PROPERTY_ID DESC LIMIT 1")
			or die(mysqli_error());
		$prop_next     = mysqli_fetch_array($result_next_record);
		$prop_previous = mysqli_fetch_array($result_previous_record);

		$folderb = 'before';
		$result_query_before = $mysqli->query("SELECT * FROM property_pictures WHERE property_id = ".$property_id." AND folder = '".$folderb."' ORDER BY filename ASC LIMIT 100") or die(mysqli_error());	
		$total_records_before = $result_query_before->num_rows;

		$foldera = 'after';
		$result_query_after = $mysqli->query("SELECT * FROM property_pictures WHERE property_id = ".$property_id." AND folder = '".$foldera."' ORDER BY filename ASC LIMIT 100") or die(mysqli_error());	
		$total_records_after = $result_query_after->num_rows;
    ?>
    <body>
        <div id="header"><?php require "header.inc.php"; ?></div>
        <div id="menu"><?php require "_menu.inc.php"; ?></div>
        <div id="content">

		<script>
			$.noConflict();
			$(document).ready(function(){
				$(".group1").colorbox({rel:'group1', height:"95%"});
				$(".group2").colorbox({rel:'group2', height:"95%"});
			});
		</script>        

		<div style="float:right;margin-right:10px;">	
			<?php if( !empty($prop_previous) ){ ?>
					<a class="button" href="propertyPictures.php?property_id=<?php echo $prop_previous['PROPERTY_ID']; ?>" style="text-decoration:none;color:#333;font-weight:normal;margin:0px;">&#xab;</a>
			<?php } ?>
			<?php if( !empty($prop_next) ){ ?>
					<a class="button" href="propertyPictures.php?property_id=<?php echo $prop_next['PROPERTY_ID']; ?>" style="text-decoration:none;color:#333;font-weight:normal;margin:0px;">&#xbb;</a>
			<?php } ?>
		</div>
		<br /><br />

		<table class="input" width="100%">
			<tr>
				<td valign="bottom" style="width: 65%">
					<a href="editProperty.php?property_id=<?php echo $property_id; ?>">Edit Property</a> | 
					<a href="propertyPictures.php?property_id=<?php echo $property_id; ?>">Pictures</a>
				</td>
				<td valign="bottom" style="width: 35%; text-align: right;">
					<strong>Lead ID:</strong> <?php echo $property_id; ?>
				</td>
			</tr>
		<table>
        
		<br />

		<table class="input" width="100%">
			<tr>
				<td valign="top" width="50%">

			        <table class="grid">
			            <tr>
			            	<td colspan="3">
			            		<h3>Before</h3> 
			            	</td>
			            </tr>
			            <tr>
			                <th colspan="3"></th>
			            </tr>
			            <tr>
			            	<?php $i = 0; ?>
			            	<?php while($row = mysqli_fetch_array($result_query_before)){ ?>
			            			<?php if($i %3 == 0) { ?>
			            					</tr><tr><td class="center">
			            						<!-- <img src='images/folder.png' alt='Before Folder' title='Before Folder' /> -->
			            						<a class="group1" href="files/property_img/<?php echo $folderb; ?>/<?php echo $row['filename']; ?>" title=""><img height="128" width="120" src="files/property_img/<?php echo $folderb; ?>/<?php echo $row['filename']; ?>" alt='' title='' /></a>
			            					</td>
			            			<?php }else { ?>
			            					<td class="center">
			            						<!-- <img src='images/folder.png' alt='Before Folder' title='Before Folder' /> -->
			            						<a class="group1" href="files/property_img/<?php echo $folderb; ?>/<?php echo $row['filename']; ?>" title=""><img height="128" width="120" src="files/property_img/<?php echo $folderb; ?>/<?php echo $row['filename']; ?>" alt='' title='' /></a>
			            					</td>
			            			<?php } ?>
				            <?php $i++; } ?>
			            </tr>
			        </table>     

			        <div class="pagination"></div>
			        <div clas="small" align="center">Total Record(s): <?php echo $total_records_before; ?></div>		           
					
				</td>

				<td valign="top" width="50%">

			        <table class="grid">
			            <tr>
			            	<td colspan="3">
			            		<h3>After</h3> 
			            	</td>
			            </tr>
			            <tr>
			                <th colspan="3"></th>
			            </tr>
			            <tr>
			            	<?php $ia = 0; ?>
			            	<?php while($rowa = mysqli_fetch_array($result_query_after)){ ?>
			            			<?php if($ia %3 == 0) { ?>
			            					</tr><tr><td class="center">
			            						<!-- <img src='images/folder.png' alt='Before Folder' title='Before Folder' /> -->
			            						<a class="group2" href="files/property_img/<?php echo $foldera; ?>/<?php echo $rowa['filename']; ?>" title=""><img height="128" width="120" src="files/property_img/<?php echo $foldera; ?>/<?php echo $rowa['filename']; ?>" alt='' title='' /></a>
			            					</td>
			            			<?php }else { ?>
			            					<td class="center">
			            						<!-- <img src='images/folder.png' alt='Before Folder' title='Before Folder' /> -->
			            						<a class="group2" href="files/property_img/<?php echo $foldera; ?>/<?php echo $rowa['filename']; ?>" title=""><img height="128" width="120" src="files/property_img/<?php echo $foldera; ?>/<?php echo $rowa['filename']; ?>" alt='' title='' /></a>
			            					</td>
			            			<?php } ?>
				            <?php $ia++; } ?>
			            </tr>   
			        </table>   

			        <div class="pagination"></div>
			        <div clas="small" align="center">Total Record(s): <?php echo $total_records_after; ?></div>
					
				</td>
			</tr>
		</table>
   
        </div>       
    </body>
</html>