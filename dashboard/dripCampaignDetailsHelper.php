<?php

require_once("include/checklogin.php");
require_once("include/session.php");
require_once("include/db_connect.php");

class DripCampaignHelper {

    function DripCampaignHelper() {
        global $session;
        /* Make sure administrator is accessing page */
        if (!$session->isAdmin() && !$session->isMaster()) {
            header("Location: index.php");
            return;
        }
        /* Admin submitted update user level form */
        if (isset($_POST['addripcampaignDetails'])) {
            $this->addDripCampaignDetails();
        }
        elseif( isset($_POST['updateDripcampaign']) ) {
            $this->updateDripCampaign();
        }
		else if (isset($_POST['deldripcampaign'])) { /* Admin submitted add user form */ 
            $this->deleteDripCampaign();
        }
        else if( isset($_GET['del']) ) {
            if($_GET['del'] == 'deldripcampaign') {
                $this->deleteDripCampaign();
                exit;
            }
        }

        /* Should not get here, redirect to home page */ else {
            header("Location: index.php");
        }
    }

	function addDripCampaignDetails() {
        global $session, $database, $form;

        $data = $_POST;        
        $campaign_id = $data['campaign_id'];
        if( $data['subject'] != '' && $data['date_to_send'] != '' ){
            $is_recipient_valid = false;            
            $recipients = $data['search_leads_auto_completec'];
            $lead_types = implode(",", $data['lead_type']);
            if( $recipients != "" || $lead_types != "" ){
                $is_recipient_valid = true;
            }            

            if( $is_recipient_valid ){
                $q = "INSERT INTO drip_campaign_details (drip_campaign_id, subject, lead_types, recipients, date_to_send, body_content, status, created) VALUES(" . $campaign_id . ",'" . stripslashes(str_replace('\r\n', ' ', $data['subject'])) . "','" . stripslashes(str_replace('\r\n', ' ', $lead_types)) . "','" . stripslashes(str_replace('\r\n', ' ', $recipients)) . "','" . date("Y-m-d",strtotime($data['date_to_send'])) . "','" . stripslashes(str_replace('\r\n', ' ', $data['messageDrip'])) . "',0,'" . date("Y-m-d H:i:s") . "')";                                
                $result = $database->query($q);                
                header("Location: dripCampaignDetails.php?drip_id=" . $campaign_id);
            }else{
                $form->setError("lead_sc", "Invalid recipient<br>");
                header("Location: " . $session->referrer);
            }
        }else{
            $form->setError("", "Cannot save record<br>");
            header("Location: " . $session->referrer);
        }
    }

    function updateDripCampaign() {
        global $session, $database, $form;

        $data = $_POST;
        if( $data['subject'] != '' && $data['date_to_send'] != '' ){
            $is_recipient_valid = false;
            $recipients = $data['search_leads_auto_completec'];
            $lead_types = implode(",", $data['lead_type']);
            if( $recipients != "" || $lead_types != "" ){
                $is_recipient_valid = true;
            } 
            

            if( $is_recipient_valid ){                
                $id               = $data['id'];
                $drip_campaign_id = $data['drip_campaign_id'];
                $subject          = $data['subject'];
                $date_to_send_arr = explode("/", $data['date_to_send']);
                $date_to_send_format = $date_to_send_arr[2] . '-' . $date_to_send_arr[0] . '-' . $date_to_send_arr[1];
                $messageDrip         = $data['messageDrip'];

                $q = "UPDATE drip_campaign_details SET subject = '". stripslashes(str_replace('\r\n', ' ', $subject)) ."', lead_types = '" . stripslashes(str_replace('\r\n', ' ', $lead_types)) . "', recipients = '" . stripslashes(str_replace('\r\n', ' ', $recipients)) . "', date_to_send = '$date_to_send_format', body_content = '" . stripslashes(str_replace('\r\n', ' ', $messageDrip)) . "' WHERE id = $id ";                
                $result = $database->query($q);
                header("Location: dripCampaignDetails.php?drip_id=" . $drip_campaign_id);
            }else{
                $form->setError("lead_sc", "Invalid recipient<br>");
                header("Location: " . $session->referrer);
            }
        }else{
            $form->setError("", "Cannot save record<br>");
            header("Location: " . $session->referrer);
        }
    }

    function deleteDripCampaign() {
        global $session, $database, $form;

        if ($_POST['name'] != null) {
            $q = "DELETE FROM drip_campaign_details WHERE id = '" . $data['id']. "'";
            $database->query($q);
            header("Location: " . $session->referrer);
        }elseif(isset($_GET['del']) && isset($_GET['drip_id'])) {
            $q = "DELETE FROM drip_campaign_details WHERE id = '" . $_GET['drip_id'] . "'";
            $database->query($q);
            header("Location: dripCampaignDetails.php?drip_id=" . $_GET['drip_campaing_id']);
        } else {
			$form->setError("deltemplate", "Name not entered<br>");
			header("Location: " . $session->referrer);
		}
    }

}

/* Initialize process */
$dripCampaignHelper = new DripCampaignHelper;
?>
