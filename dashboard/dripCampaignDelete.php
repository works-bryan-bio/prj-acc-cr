<?php

require_once("include/checklogin.php");
require_once("include/session.php");
require_once("include/db_connect.php");

global $session, $database, $form;
$id = $_GET['drip_id'];
if( $id > 0 ){
    $q = "DELETE FROM drip_campaign WHERE id = '" . $id. "'";
    $database->query($q);
    header("Location: " . $session->referrer);
}else{
    $form->setError("deltemplate", "Name not entered<br>");
    header("Location: " . $session->referrer);
}
?>
