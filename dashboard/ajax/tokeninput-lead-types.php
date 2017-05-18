<?php
require_once("../include/db_connect.php");
$param  = $_GET['q'];
$data = array();
$result = $mysqli->query("SELECT LEAD_ID, LEAD_TYPE FROM leads WHERE LEAD_TYPE LIKE '%" . $param . "%' AND LEAD_TYPE <> '' GROUP BY LEAD_TYPE LIMIT 10")or die(mysqli_error());
while($row = mysqli_fetch_array($result)){
	$data[] = array('id' => $row['LEAD_TYPE'], 'name' => $row['LEAD_TYPE']);
}
header('Content-Type: application/json');
echo json_encode($data);
?>
