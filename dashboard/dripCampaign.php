<?php
require_once("include/checklogin.php");
require_once('include/db_connect.php');
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="refresh" content="600">
        <title>SimpleHouseSolutions.com - Drip Campaign</title>
        <link rel="shortcut icon" href="/favicon.ico" />
        <link rel="stylesheet" type="text/css" href="css/dashboard.css"/>
        <link rel="stylesheet" type="text/css" href="css/dashboard_menu.css"/>
       <link rel="stylesheet" type="text/css" href="js/tigra_calendar/calendar.css">
      <script type="text/javascript" src="js/tigra_calendar/calendar_us.js"></script> 
        
        
    </head>
    <body>
        <div id="header"><?php require "header.inc.php"; ?></div>
        <div id="menu"><?php require "menu.inc.php"; ?></div>
        <div id="content">

            <br />
            <div>
                <a class="modal-poplight button" href="#?w=700" rel="create_campaign_popup">Create Campaign</a>
            </div>
            <br />
            <table class="grid">
                <tr><td colspan="13"><h3>Campaign List</h3></td></tr>
                <tr>
                    <th>Actions</th>
                    <th>Subject</th>
                    <th>Recipients</th>
                    <th>Date to send</th>
                    <th>Status</th>
                </tr>
                <?php
					$result = $mysqli->query("SELECT * FROM drip_campaign LIMIT 50")or die(mysqli_error());
					while($row = mysqli_fetch_array($result)){
                ?>
                <tr>
                    <td class="center">
                        <a href="editDripCampaign.php?drip_id=<?=$row['id']?>">
                            <img src='images/edit.png' alt='Edit Drip Campaign' title='Edit Drip Campaign' />
                        </a>
                    </td>
                    <td><?php echo $row['subject']; ?></td>
                    <td>
                        <?php 
                            if( $row['recipient_type'] ){
                                echo "Lead Email : " . $row['recipients'];
                            }else{
                                echo "Lead Type : " . $row['recipients'];
                            }
                        ?>                        
                    </td>
                    <td><?php echo $row['date_to_send']; ?></td>
                    <td><?php echo $row['status'] == 1 ? 'Sent' : 'Onqueue'; ?></td>
                </tr>
                <?php
                	}
                ?>

            </table>
	<?php require_once('modal.create.campaign.php'); ?>    
    </body>
</html>