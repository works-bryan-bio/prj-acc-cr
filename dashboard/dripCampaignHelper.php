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
        if (isset($_POST['addripcampaign'])) {
            $this->addDripCampaign();
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

	function addDripCampaign() {
        global $session, $database, $form;

        $data = $_POST;
        if( $data['subject'] != '' && $data['date_to_send'] != '' ){
            $is_recipient_valid = false;
            $recipients         = "";
            if( $data['lead_sc'] == 'search_lead' ){
                $recipient_type = 1;
                if( $data['search_leads_auto_completec'] != '' ){
                    $is_recipient_valid = true;
                    $recipients = $data['search_leads_auto_completec'];
                }
            }else{
                $recipient_type = 2;
                if( $data['lead_type'] != '' ){
                    $is_recipient_valid = true;
                    $recipients = $data['lead_type'];
                }
            }

            if( $is_recipient_valid ){
                $q = "INSERT INTO drip_campaign (subject, recipient_type, recipients, date_to_send, body_content, status, created) VALUES('" . stripslashes(str_replace('\r\n', ' ', $data['subject'])) . "'," . $recipient_type . ",'" . stripslashes(str_replace('\r\n', ' ', $recipients)) . "','" . date("Y-m-d",strtotime($data['date_to_send'])) . "','" . stripslashes(str_replace('\r\n', ' ', $data['messageDrip'])) . "',0,'" . date("Y-m-d H:i:s") . "')";                                
                $result = $database->query($q);                
                header("Location: dripCampaign.php");
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
            $recipients         = "";
            if( $data['lead_sc'] == 'search_lead' ){
                $recipient_type = 1;
                if( $data['search_leads_auto_completec'] != '' ){
                    $is_recipient_valid = true;
                    $recipients = $data['search_leads_auto_completec'];
                }
            }elseif($data['lead_sc'] == 'search_lead_type'){
                $recipient_type = 2;
                if( $data['lead_type'] != '' ){
                    $is_recipient_valid = true;
                    $recipients = $data['lead_type'];
                }
            } else {
                $is_recipient_valid = true;
                $recipient_type = $data['lead_sc'] == 'null' ? $data['lead_sc_default'] : $data['lead_sc'];
                $recipients = $data['leads_default_data'];
            } 

            if( $is_recipient_valid ){
                $id = $data['id'];
                $subject = $data['subject'];
                $date_to_send_arr = explode("/", $data['date_to_send']);
                $date_to_send_format = $date_to_send_arr[2] . '-' . $date_to_send_arr[0] . '-' . $date_to_send_arr[1];
                $messageDrip = $data['messageDrip'];

                $q = "UPDATE drip_campaign SET subject = '". stripslashes(str_replace('\r\n', ' ', $subject)) ."', recipient_type = $recipient_type, recipients = '" . stripslashes(str_replace('\r\n', ' ', $recipients)). "', date_to_send = '$date_to_send_format', body_content = '" . stripslashes(str_replace('\r\n', ' ', $messageDrip)) . "' WHERE id = $id ";
                $result = $database->query($q);
                header("Location: dripCampaign.php");
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
            $q = "DELETE FROM drip_campaign WHERE id = '" . $data['id']. "'";
            $database->query($q);
            header("Location: " . $session->referrer);
        }elseif(isset($_GET['del']) && isset($_GET['drip_id'])) {
            $q = "DELETE FROM drip_campaign WHERE id = '" . $_GET['drip_id'] . "'";
            $database->query($q);
            header("Location: dripCampaign.php");
        } else {
			$form->setError("deltemplate", "Name not entered<br>");
			header("Location: " . $session->referrer);
		}
    }

}

/* Initialize process */
$dripCampaignHelper = new DripCampaignHelper;
?>
