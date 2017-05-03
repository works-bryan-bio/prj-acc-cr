<?php
require_once("../include/db_connect.php");
$param  = $_GET['q'];
$data = array();
$result = $mysqli->query("SELECT LEAD_ID, CLIENT_EMAIL FROM leads WHERE client_email LIKE '%" . $param . "%' LIMIT 10")or die(mysqli_error());
while($row = mysqli_fetch_array($result)){
	$data[] = array('id' => $row['LEAD_ID'], 'name' => $row['CLIENT_EMAIL']);
	//echo $row['LEAD_ID'] . ' ' . $row['CLIENT_EMAIL'];
	//echo '<hr />';
}
header('Content-Type: application/json');
echo json_encode($data);
?>
