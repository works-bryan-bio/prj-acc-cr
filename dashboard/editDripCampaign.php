<?php
require_once("include/checklogin.php");
require_once("include/db_connect.php");
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>SimpleHouseSolutions.com - Edit Drip Campaign</title>
        <link rel="shortcut icon" href="/favicon.ico" />
        <link rel="stylesheet" type="text/css" href="css/dashboard.css"/>
        <link rel="stylesheet" type="text/css" href="css/dashboard_menu.css"/>
       <link rel="stylesheet" type="text/css" href="js/tigra_calendar/calendar.css">
       <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
       <script type="text/javascript" src="js/tigra_calendar/calendar_us.js"></script>         
    </head>
<body>
<div id="header"><?php require "header.inc.php"; ?></div>
<div id="menu"><?php require "menu.inc.php"; ?></div>
<div id="content">
<div align="center">
<?php $date_to_send = date("Y-m-d", strtotime(date('m').'/01/'.date('Y').' 00:00:00')); ?>
    <form name="form1" method="post" action="" enctype="multipart/form-data">
        <br />
        <input class="button" type="submit" name="submit" value="Save Changes">
        <input class="button" type="submit" name="dsubmit" value="Save and Go to Drip List" />
        <input class="button" type="submit" name="delsubmit" value="Delete" onclick="return confirm_delete();" />
        <input class="button" type="button" onClick="javascript:history.back()" value="Cancel">
        <table class="input drip_edit_form">
            <tr>
                <th>Edit Drip Campaign</th>
                <th>&nbsp;</th>
                <th style="text-align:right;">Last Update: </th>
            </tr>
            <tr>
                <td align="right">Subject:</td>
                <td align="left" colspan="">
                    <input id="dripSubject" name="subject" type="text" style="width:97%" placeholder="" />
                </td>
            </tr>
            <tr>
                <td align="right">Recipient Type:</td>
                <td align="left" colspan="">
                    <select id="lead_sc" name="lead_sc">
                        <option value="search_lead">Leads</option>
                        <option value="search_lead_type">Lead Type</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td align="right">Recipient:</td>
                <td align="left" colspan="">
                    <div id="lead_type_containerc" style="display:none;">
                        <select id="lead_type" name="lead_type" style="width:99%">
                        <?php
                            $result = $mysqli->query("SELECT LEAD_TYPE FROM leads WHERE LEAD_TYPE <> '' GROUP BY LEAD_TYPE") or die(mysql_error());
                            while($row = mysqli_fetch_array($result)){
                                foreach($row AS $key => $value) {
                                    $row[$key] = stripslashes($value);
                                }
                        ?>          
                            <option value="<?php echo $row['LEAD_TYPE']; ?>"><?php echo $row['LEAD_TYPE']; ?></option>
                        <?php } ?>
                        </select>                       
                    </div>
                    <div id="leads_containerc" style="">
                        <input id="search_leads_auto_completec" name="search_leads_auto_completec" type="text" placeholder="Search Leads" />                        
                    </div>
                </td>
            </tr>
            <tr>
                <td align="right">Date to send:</td>
                <td align="left" colspan="">
                    <input type="text" name="date_to_send" id="date_to_send" size="10" value="<?php if ($date_to_send!="") echo date("m/d/Y", strtotime($date_to_send)); else echo "" ?>" onChange="" />
                    <script type="text/javascript">
                        var s_cal = new tcal ({
                            'controlname': 'date_to_send'
                        });
                    </script>
                </td>
            </tr>
            <tr>
                <td align="right">Template:</td>
                <td align="left" colspan="2">
                    <select id="templateMassEmailc" name="template" onchange="tinymce.get('messageMassEmailc').setContent(this.value);">
                    <option value="">None</option>
                    <?php
                        $result = $mysqli->query("SELECT name,content FROM email_templates") or die(mysql_error());
                        while($row = mysqli_fetch_array($result)){
                            foreach($row AS $key => $value) {
                                $row[$key] = stripslashes($value);
                            }
                    ?>
                    <option value="<?=str_replace('"', "'", $row['content'])?>"><?=$row['name']?></option>
                    <?php
                        }
                    ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td align="right">Email Content:</td>
                <td align="left" colspan="2">
                    <textarea id="messageMassEmailc" name="messageDrip" style="width:98%; height:480px"></textarea>
                </td>
            </tr>
        </table>
    </form>
</div>

<!-- End Content -->
</div>
</body>
</html>