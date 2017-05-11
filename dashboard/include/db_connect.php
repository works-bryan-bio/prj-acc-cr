<?php
$mysqli = new mysqli("localhost","root","p@ssw0rd","shaner_dash") or die($mysqli->error);
$username="simplehousesolut";
$password="pz!Cn_@#PD.*";

function mysqli_result($res, $row, $field=0) {
    $res->data_seek($row);
    $datarow = $res->fetch_array();
    return $datarow[$field];
} 

?>