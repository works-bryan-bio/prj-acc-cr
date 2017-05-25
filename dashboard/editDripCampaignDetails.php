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

       <style>
        .table-form td{
            padding:4px;
            vertical-align: top;
        }
        .lead-type-list{
            list-style: none;
            margin: 0px;
            padding: 0px;
        }
        .lead-type-list li{
            display: inline-block;
            margin: 5px;
            width:182px;    
        }
        .modal-label{
            padding:10px;
            background-color: #F2F2F2;
            width: 782px;
            text-align: left;
            margin:10px 0;
        }
        </style>

    </head>
<body>
<div id="header"><?php require "header.inc.php"; ?></div>
<div id="menu"><?php require "menu.inc.php"; ?></div>
<div id="content">
<div align="center">
<?php
    $drip_id = $_GET['drip_detail_id'];
    $result_query = $mysqli->query("SELECT * FROM drip_campaign_details WHERE id = $drip_id LIMIT 1") or die(mysqli_error());

    $result_array = array();
    while($prop = mysqli_fetch_array($result_query)){
        foreach($prop AS $key => $value) {
            $prop[$key] = stripslashes($value);
        }

        $result_array['id']             = $prop['id'];
        $result_array['drip_campaign_id'] = $prop['drip_campaign_id'];
        $result_array['subject']        = $prop['subject'];
        $result_array['lead_types']     = $prop['lead_types'];
        $result_array['recipients']     = $prop['recipients'];
        $result_array['date_to_send']   = $prop['date_to_send'];
        $result_array['body_content']  = $prop['body_content'];
        $result_array['status']         = $prop['status'];
    }

    $a_lead_types  = explode(",", $result_array['lead_types']);
    $a_leads_email = explode(",", $result_array['recipients']);
    $counter = 1;
    foreach( $a_leads_email as $e ){
        $json_data[] = '{id:"' . $e . '", name:"' . $e  . '"}';
        $counter++;
    }
    $leads_json_string = implode(",", $json_data);         
?>
<?php $date_to_send = date("Y-m-d", strtotime(date('m').'/01/'.date('Y').' 00:00:00')); ?>
    <form name="dripForm" method="post" action="dripCampaignDetailsHelper.php" enctype="multipart/form-data">
        <br />
        <input class="button" type="submit" name="saveCampaign" id="saveCampaign" value="Save Changes">
        <!-- <input class="button" type="submit" name="dsaveCampaign" id="dsaveCampaign" value="Save and Go to Drip List" /> -->
        <!-- <input  class="button" type="submit" name="delsubmit" value="Delete" onclick="return confirm_delete();" /> -->
        <a id="delsubmit" class="button" onclick="return confirm_delete();" href="dripCampaignDetailsHelper.php?del=deldripcampaign&drip_campaing_id=<?php echo $result_array['drip_campaign_id']; ?>&drip_id=<?php echo $result_array['id']; ?>">Delete</a>
        <input class="button" type="button" onClick="javascript:history.back()" value="Cancel">
        <input type="hidden" name="updateDripcampaign" value="1">
        <input type="hidden" name="id" value="<?php echo $result_array['id']; ?>">        
        <input type="hidden" name="drip_campaign_id" value="<?php echo $result_array['drip_campaign_id']; ?>">        
        <table class="input drip_edit_form">
            <tr>
                <th>Edit Drip Campaign List</th>
                <th>&nbsp;</th>
                <th style="text-align:right;">Last Update: </th>
            </tr>
        </table>
        <h3 class="modal-label">Recipient Details</h3>
        <table class="input drip_edit_form">
            <tr>
                <td align="right" style="width:10%;">Subject:</td>
                <td align="left" colspan="">
                    <input id="dripSubject" name="subject" type="text" style="width:97%" value="<?php echo $result_array['subject']; ?>" placeholder="" />
                </td>
            </tr>
            <tr>
                <td align="right">Leads:</td>
                <td align="left" colspan="">
                    <input id="search_leads_auto_completec" name="search_leads_auto_completec" type="text" value="" placeholder="Search Leads" />                                
                </td>
            </tr>
            <tr>
                <td align="right">Lead Types</td>
                <td>        
                    <ul class="lead-type-list">         
                    <?php 
                        $result = $mysqli->query("SELECT LEAD_TYPE FROM leads WHERE LEAD_TYPE <> '' GROUP BY LEAD_TYPE") or die(mysql_error());
                        while($row = mysqli_fetch_array($result)){
                            foreach($row AS $key => $value) {
                                $row[$key] = stripslashes($value);
                            }

                            $is_checked = "";
                            if( in_array($row['LEAD_TYPE'], $a_lead_types) ){
                                $is_checked = 'checked';
                            }
                    ?>
                        <li><label><input type="checkbox" <?php echo $is_checked; ?> value="<?php echo $row['LEAD_TYPE']; ?>" name="lead_type[<?php echo $row['LEAD_TYPE']; ?>]" /><?php echo $row['LEAD_TYPE']; ?></label></li>
                    <?php } ?>
                    </ul>                       
                </td>
            </tr>
            </table>
            <?php
                if($result_array['date_to_send'] != '') {
                    $date_array = explode("-", $result_array['date_to_send']);
                    $format_date_to_send = $date_array[1] . "/" . $date_array[2] . "/" . $date_array[0];
                } else {
                    $format_date_to_send = "";
                }
                
            ?>            
            <h3 class="modal-label">Email Details</h3>
            <table class="input drip_edit_form">
            <tr>
                <td align="right" style="width:15%;">Date to send:</td>
                <td align="left" colspan="">
                    <input type="text" name="date_to_send" id="date_to_send" size="10" value="<?php echo $result_array['date_to_send'] != '' ? $format_date_to_send : $date_to_send; ?>" onChange="" />
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
                    <option value="">--</option>
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
                    <textarea id="messageMassEmailc" name="messageDrip" style="width:98%; height:480px"><?php echo $result_array['body_content']; ?></textarea>
                </td>
            </tr>
        </table>
    </form>
</div>

<script>

    tinyMCE.init({
            mode: "exact",
            elements: "elm1,message,messageMassEmailc",
            plugins : "spellchecker",
            theme: "advanced",
            theme_advanced_buttons1: "bold,italic,underline,strikethrough,separator,justifyleft,justifycenter,justifyright,justifyfull,bullist,numlist,undo,redo,link,unlink,spellchecker,image",
            theme_advanced_buttons2: "",
            theme_advanced_buttons3: "",
            theme_advanced_buttons4: "",
            theme_advanced_toolbar_location: "top",
            theme_advanced_toolbar_align: "left"
        });

    $(document).ready(function () {

            //Autocomplete
            $("#search_leads_auto_completec").tokenInput("ajax/tokeninput-leads-email.php", {
                prePopulate: [<?php echo $leads_json_string; ?>],
                theme: "facebook",
                preventDuplicates: true
            });
            $("#search_lead_types_auto_completecc").tokenInput("ajax/tokeninput-lead-types.php", {
                theme: "facebook",
                preventDuplicates: true
            });

    });

    function confirm_delete() {
      return confirm("Are you sure you want to delete this item?");
    }    

</script>    
</script>

<script>
    $(document).ready(function(){
        $("#lead_sc").change(function(){
            var optionValue = $(this).attr("value");
            if(optionValue == 'search_lead'){
                $("#leads_containerc").show();
                $("#lead_type_containerc").hide();
                $("#leads_default").hide();
            }else if(optionValue == 'search_lead_type'){
                $("#leads_containerc").hide();
                $("#lead_type_containerc").show();
                $("#leads_default").hide();
            } else {
                $("#leads_containerc").hide();
                $("#lead_type_containerc").hide();
                $("#leads_default").show();                
            }
        });
    });
</script>

<!-- End Content -->
</div>
</body>
</html>