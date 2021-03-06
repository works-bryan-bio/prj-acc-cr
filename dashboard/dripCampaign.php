<?php
require_once("include/checklogin.php");
require_once('include/db_connect.php');
require_once('include/pagination.php');

$size = 20;
$link = "dripCampaign.php?page=%s";
$orderby = "name";
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
$result        = $mysqli->query("SELECT COUNT(*) FROM drip_campaign");
$row           = $result->fetch_row();
$total_records = $row[0];
$pagination->setTotalRecords($total_records);
$result_query = $mysqli->query("SELECT * FROM drip_campaign ORDER BY " . $orderby . " " . $dir . " " . $pagination->getLimitSql()) or die(mysqli_error());
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
                <tr><td colspan="2"><h3>Campaign List</h3></td></tr>
                <tr>
                    <th style="width:5%;">Actions</th>
                    <th>Name&nbsp;<a href="dripCampaign.php?orderby=subject&dir=ASC&filter=<?=$filter?>">&#9650;</a>&nbsp;<a href="dripCampaign.php?orderby=subject&dir=DESC&filter=<?=$filter?>">&#9660;</a></th>                    
                </tr>
                <?php while($row = mysqli_fetch_array($result_query)){ ?>
                <tr>
                    <td class="center">
                        <a href="dripCampaignDetails.php?drip_id=<?=$row['id']?>">
                            <img src='images/email.png' alt='Campaign Details' title='Campaign Details' />
                        </a>
                        <a class="modal-poplight" href="#?w=700" rel="create_campaign_popup_<?= $row['id'] ?>">
                            <img src='images/edit.png' alt='Edit' title='Edit' />
                        </a>
                        <a href="dripCampaignDelete.php?drip_id=<?=$row['id']?>">
                            <img src='images/delete.png' alt='Delete' title='Delete' />
                        </a>

                        <div id="create_campaign_popup_<?= $row['id'] ?>" class="popup_block">
                            <h3>Edit Campaign</h3><br/>
                            <form name="dripEdit" action="dripCampaignHelper.php" method="post">
                            <input type="hidden" name="updateDripcampaign" value="1">
                            <input type="hidden" name="campaignId" value="<?= $row['id'] ?>">
                                <table style="width:100%;" class="table-form">
                                    <tr>
                                        <td style="width:180px;">Campaign Name</td>
                                        <td><input id="dripName" name="name" value="<?= $row['name'] ?>" type="text" style="width:97%" placeholder="" /></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" style="text-align:right;"><input class="button" type="submit" id="saveCampaign" name="saveCampaign" value="Save Campaign" onClick="callHelper('searchReportHelper.php?action=sendEmail&lead_id='); $('.popup_block').hide(); $('#fade, a.close').remove();" /></td>
                                    </tr>           
                                </table>
                            </form>
                        </div>

                    </td>
                    <td><?= $row['name'] ?></td>                    
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
                    //$mysqli->close();
                ?>
            </div>
            <div clas="small" align="center">Total Record(s): <?=$total_records?></div>
    <?php require_once('modal.create.campaign.php'); ?>    
    </body>
</html>