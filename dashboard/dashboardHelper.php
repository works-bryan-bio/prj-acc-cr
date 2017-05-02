<?php

require_once("include/checklogin.php");
require_once("include/session.php");
require_once("include/db_connect.php");

$leads = $_POST['leads'];
$move_to = $_POST['move_to'];
$assign_to = $_POST['assign_to'];

foreach ($leads as $lead) {
	if ($move_to != "" && $assign_to == "") {
		$sql = "UPDATE leads SET FOLLOW_UP_DATE='" . date("Y-m-d", strtotime($move_to)) . "' ";
	} else if ($move_to == "" && $assign_to != "") {
		$sql = "UPDATE leads SET USERNAME='" . $assign_to . "' ";
	} else if ($move_to != "" && $assign_to != "") {
		$sql = "UPDATE leads SET FOLLOW_UP_DATE='" . date("Y-m-d", strtotime($move_to)) . "', USERNAME='" . $assign_to . "' ";
	}
	$sql .= "WHERE LEAD_ID=" . $lead;
	//echo $sql;
	$query = $mysqli->query($sql) or die($mysqli->error);
}

header("Location: " . $_SERVER["HTTP_REFERER"]);
?>