<?php
require_once("include/db_connect.php");
$image = $_GET[image];
$id = $_GET[id];
$query="SELECT " . $image . "," . $image . "_mime FROM properties WHERE property_id=" . $id;
$result=$mysqli->query($query);
if($result!=NULL) {
	$row = mysqli_fetch_array($result);
	$imagebytes = $row[$image];
	$imagemime = $row[$image . "_mime"];
	header("Content-type: " . $imagemime);
	print $imagebytes;
} else {
	$blank = "images/nopic.png";
	print $blank;
}
$mysqli->close();
?>