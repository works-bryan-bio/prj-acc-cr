<?php
$mysqli = new mysqli("localhost","root","","shaner_dash") or die($mysqli->error);
$username="shaner_dash";
$password="";

function mysqli_result($res, $row, $field=0) {
    $res->data_seek($row);
    $datarow = $res->fetch_array();
    return $datarow[$field];
} 

?>