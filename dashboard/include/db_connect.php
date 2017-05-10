<?php
$mysqli = new mysqli("localhost","simplehousesolut","pz!Cn_@#PD.*","simplehousesolut_db") or die($mysqli->error);
$username="simplehousesolut";
$password="pz!Cn_@#PD.*";

function mysqli_result($res, $row, $field=0) {
    $res->data_seek($row);
    $datarow = $res->fetch_array();
    return $datarow[$field];
} 

?>