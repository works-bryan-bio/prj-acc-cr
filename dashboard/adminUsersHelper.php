<?php

require_once("include/checklogin.php");
require_once("include/session.php");

class AdminUsersHelper {
    /* Class constructor */

    function AdminUsersHelper() {
        global $session;
        /* Make sure administrator is accessing page */
        if (!$session->isAdmin() && !$session->isMaster()) {
            header("Location: index.php");
            return;
        }
        /* Admin submitted update user level form */
        if (isset($_POST['subupdlevel'])) {
            $this->procUpdateLevel();
        }
		/* Admin submitted add user form */ else if (isset($_POST['subadduser'])) {
            $this->procAddUser();
        }
        /* Admin submitted delete user form */ else if (isset($_POST['subdeluser'])) {
            $this->procDeleteUser();
        }
        /* Admin submitted change password form */ else if (isset($_POST['cpuser'])) {
            $this->procChangePassword();
        }
        /* Should not get here, redirect to home page */ else {
            header("Location: index.php");
        }
    }

	/**
     * procAddUser - Processes the user submitted registration form,
    * if errors are found, the user is redirected to correct the
    * information, if not, the user is effectively registered with
    * the system and an email is (optionally) sent to the newly
    * created user.
    */
    function procAddUser() {
		global $session, $database, $form;
		/* Convert username to all lowercase (by option) */
		if(ALL_LOWERCASE){
		   $_POST['user'] = strtolower($_POST['user']);
		}
		/* Registration attempt */
		$retval = $session->register($_POST['user'], $_POST['pass'], $_POST['email'], $_POST['fullname'], $_POST['ulevel']);

		/* Registration Successful */
		if($retval == 0){
		   $_SESSION['reguname'] = $_POST['user'];
		   $_SESSION['regsuccess'] = true;
		   header("Location: ".$session->referrer);
		}
		/* Error found with form */
		else if($retval == 1){
		   $_SESSION['value_array'] = $_POST;
		   $_SESSION['error_array'] = $form->getErrorArray();
		   header("Location: ".$session->referrer);
		}
		/* Registration attempt failed */
		else if($retval == 2){
		   $_SESSION['reguname'] = $_POST['user'];
		   $_SESSION['regsuccess'] = false;
		   header("Location: ".$session->referrer);
		}
    }

    /**
     * procUpdateLevel - If the submitted username is correct,
     * their user level is updated according to the admin's
     * request.
     */
    function procUpdateLevel() {
        global $session, $database, $form;
        /* Username error checking */
        $subuser = $this->checkUsername("upduser");

        /* Errors exist, have user correct them */
        if ($form->num_errors > 0) {
            $_SESSION['value_array'] = $_POST;
            $_SESSION['error_array'] = $form->getErrorArray();
            header("Location: " . $session->referrer);
        }
        /* Update user level */ else {
            $database->updateUserField($subuser, "userlevel", (int) $_POST['updlevel']);
            header("Location: " . $session->referrer);
        }
    }

    /**
     * procDeleteUser - If the submitted username is correct,
     * the user is deleted from the database.
     */
    function procDeleteUser() {
        global $session, $database, $form;
        /* Username error checking */
        $subuser = $this->checkUsername("deluser");

        /* Errors exist, have user correct them */
        if ($form->num_errors > 0) {
            $_SESSION['value_array'] = $_POST;
            $_SESSION['error_array'] = $form->getErrorArray();
            header("Location: " . $session->referrer);
        }
        /* Delete user from database */ else {
            $q = "DELETE FROM " . TBL_USERS . " WHERE username = '$subuser'";
            $database->query($q);
            header("Location: " . $session->referrer);
        }
    }

    /**
     * checkUsername - Helper function for the above processing,
     * it makes sure the submitted username is valid, if not,
     * it adds the appropritate error to the form.
     */
    function checkUsername($uname, $ban = false) {
        global $database, $form;
        /* Username error checking */
        $subuser = $_POST[$uname];
        $field = $uname;  //Use field name for username
        if (!$subuser || strlen($subuser = trim($subuser)) == 0) {
            $form->setError($field, "* Username not entered<br>");
        } else {
            /* Make sure username is in database */
            $subuser = stripslashes($subuser);
            if (strlen($subuser) < 5 || strlen($subuser) > 30 ||
                    !eregi("^([0-9a-z])+$", $subuser) ||
                    (!$ban && !$database->usernameTaken($subuser))) {
                $form->setError($field, "* Username does not exist<br>");
            }
        }
        return $subuser;
    }

    /**
     * procEditAccount - Attempts to edit the user's account
     * information, including the password, which must be verified
     * before a change is made.
     */
    function procChangePassword() {
        global $session, $form;
        /* Change password attempt */
        $retval = $session->changePassword($_POST['cpuser'], $_POST['newpass1'], $_POST['newpass2']);

        /* Account edit successful */
        if ($retval) {
            $_SESSION['pwupdate'] = true;
            header("Location: " . $session->referrer);
        }
        /* Error found with form */ else {
            $_SESSION['value_array'] = $_POST;
            $_SESSION['error_array'] = $form->getErrorArray();
            header("Location: " . $session->referrer);
        }
    }

}

;

/* Initialize process */
$adminUsersHelper = new AdminUsersHelper;
?>
