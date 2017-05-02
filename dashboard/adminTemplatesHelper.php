<?php

require_once("include/checklogin.php");
require_once("include/session.php");

class AdminTemplatesHelper {

    function AdminTemplatesHelper() {
        global $session;
        /* Make sure administrator is accessing page */
        if (!$session->isAdmin() && !$session->isMaster()) {
            header("Location: index.php");
            return;
        }
        /* Admin submitted update user level form */
        if (isset($_POST['addtemplate'])) {
            $this->addTemplate();
        }
		/* Admin submitted add user form */ else if (isset($_POST['deltemplate'])) {
            $this->deleteTemplate();
        }
        /* Should not get here, redirect to home page */ else {
            header("Location: index.php");
        }
    }

	function addTemplate() {
        global $session, $database, $form;

        if ($_POST['name'] != null && $_POST['content'] != null) {
            $q = "INSERT INTO email_templates (name, content) VALUES ('" . $_POST['name'] . "', '" . stripslashes(str_replace('\r\n', ' ', $_POST['content'])) . "')";
            $database->query($q);
            header("Location: " . $session->referrer);
        } else {
			$form->setError("addtemplate", "Name not entered<br>");
			header("Location: " . $session->referrer);
		}
    }

    function deleteTemplate() {
        global $session, $database, $form;

        if ($_POST['name'] != null) {
            $q = "DELETE FROM email_templates WHERE name = '" . $_POST['name'] . "'";
            $database->query($q);
            header("Location: " . $session->referrer);
        } else {
			$form->setError("deltemplate", "Name not entered<br>");
			header("Location: " . $session->referrer);
		}
    }

}

/* Initialize process */
$adminTemplatesHelper = new AdminTemplatesHelper;
?>
