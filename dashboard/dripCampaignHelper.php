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
        if( $data['name'] != '' ){            
            $q = "INSERT INTO drip_campaign (name, created) VALUES('" . stripslashes(str_replace('\r\n', ' ', $data['name'])) . "','" . date("Y-m-d H:i:s") . "')";                                
            $result = $database->query($q);                
            header("Location: dripCampaign.php");
        }else{
            $form->setError("", "Cannot save record<br>");
            header("Location: " . $session->referrer);
        }
    }

    function updateDripCampaign() {
        global $session, $database, $form;

        $data = $_POST;

        if( $data['name'] != '' ){   
            $q = "UPDATE drip_campaign SET name = '". stripslashes(str_replace('\r\n', ' ', $data['name'])) . "' WHERE id =" . $data['campaignId'];
            $result = $database->query($q);
            header("Location: dripCampaign.php");
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
