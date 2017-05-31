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

    ?>
    <body>
        <div id="header"><?php require "header.inc.php"; ?></div>
        <div id="menu"><?php require "_menu.inc.php"; ?></div>
        <div id="content">

		<script>
			$.noConflict();
			$(document).ready(function(){
				//Examples of how to assign the ColorBox event to elements
				$(".group1").colorbox({rel:'group1'});
				$(".group2").colorbox({rel:'group2', transition:"fade"});
				$(".group3").colorbox({rel:'group3', transition:"none", width:"75%", height:"75%"});
				$(".group4").colorbox({rel:'group4', slideshow:true});
				$(".ajax").colorbox();
				$(".youtube").colorbox({iframe:true, innerWidth:425, innerHeight:344});
				$(".iframe").colorbox({iframe:true, width:"80%", height:"80%"});
				$(".inline").colorbox({inline:true, width:"50%"});
				$(".callbacks").colorbox({
					onOpen:function(){ alert('onOpen: colorbox is about to open'); },
					onLoad:function(){ alert('onLoad: colorbox has started to load the targeted content'); },
					onComplete:function(){ alert('onComplete: colorbox has displayed the loaded content'); },
					onCleanup:function(){ alert('onCleanup: colorbox has begun the close process'); },
					onClosed:function(){ alert('onClosed: colorbox has completely closed'); }
				});
				
				//Example of preserving a JavaScript event for inline calls.
				$("#click").click(function(){ 
					$('#click').css({"background-color":"#f00", "color":"#fff", "cursor":"inherit"}).text("Open this window again and this message will still be here.");
					return false;
				});
			});
		</script>        

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
				<a href="transactionCoordinator.php?lead_id=<?php echo $lead_id; ?>">Transaction Coordinator</a>&nbsp;|&nbsp;
				<a href="Pictures.php?lead_id=<?php echo $lead_id; ?>">Pictures</a>
			</td>
			<td valign="bottom" style="width: 35%; text-align: right;">
				<strong>Lead ID:</strong> <?php echo $lead_id; ?>
			</td>
		</tr>
		<table>

        <table class="grid">
            <tr><th colspan="2"><h3>Pictures</h3></th></tr>
        </table>
        <br />
        
        <table class="">
            <tr>
            	<td>
            		<div style="text-align: center; width: 200px;">
            		<img src='images/folder.png' alt='Before Folder' title='Before Folder' /><br />Before</div>
            	</td>
            	<td>
            		<div style="text-align: center; width: 200px;">
            		<img src='images/folder.png' alt='After Folder' title='After Folder' /><br />After</div>

            	</td>
            </tr>
        </table>        

		<h2>Elastic Transition</h2>
		<p><a class="group1" href="files/ohoopee1.jpg" title="Me and my grandfather on the Ohoopee.">Grouped Photo 1</a></p>
		<p><a class="group1" href="files/ohoopee2.jpg" title="On the Ohoopee as a child">Grouped Photo 2</a></p>
		<p><a class="group1" href="files/ohoopee3.jpg" title="On the Ohoopee as an adult">Grouped Photo 3</a></p>
		
		<h2>Fade Transition</h2>
		<p><a class="group2" href="files/ohoopee1.jpg" title="Me and my grandfather on the Ohoopee">Grouped Photo 1</a></p>
		<p><a class="group2" href="files/ohoopee2.jpg" title="On the Ohoopee as a child">Grouped Photo 2</a></p>
		<p><a class="group2" href="files/ohoopee3.jpg" title="On the Ohoopee as an adult">Grouped Photo 3</a></p>
		
		<h2>No Transition + fixed width and height (75% of screen size)</h2>
		<p><a class="group3" href="files/ohoopee1.jpg" title="Me and my grandfather on the Ohoopee.">Grouped Photo 1</a></p>
		<p><a class="group3" href="files/ohoopee2.jpg" title="On the Ohoopee as a child">Grouped Photo 2</a></p>
		<p><a class="group3" href="files/ohoopee3.jpg" title="On the Ohoopee as an adult">Grouped Photo 3</a></p>
		
		<h2>Slideshow</h2>
		<p><a class="group4"  href="files/ohoopee1.jpg" title="Me and my grandfather on the Ohoopee.">Grouped Photo 1</a></p>
		<p><a class="group4"  href="files/ohoopee2.jpg" title="On the Ohoopee as a child">Grouped Photo 2</a></p>
		<p><a class="group4"  href="files/ohoopee3.jpg" title="On the Ohoopee as an adult">Grouped Photo 3</a></p>

   
        </div>       
    </body>
</html>