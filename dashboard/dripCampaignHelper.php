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
		/* Admin submitted add user form */ else if (isset($_POST['deldripcampaign'])) {
            $this->deleteDripCampaign();
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

    function deleteDripCampaign() {
        global $session, $database, $form;

        if ($_POST['name'] != null) {
            $q = "DELETE FROM drip_campaign WHERE id = '" . $data['id']. "'";
            $database->query($q);
            header("Location: " . $session->referrer);
        } else {
			$form->setError("deltemplate", "Name not entered<br>");
			header("Location: " . $session->referrer);
		}
    }

}

/* Initialize process */
$dripCampaignHelper = new DripCampaignHelper;
?>
