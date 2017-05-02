<?php
require_once('db_connect.php');
require_once('searchlocation.php');

$query = "SELECT PROPERTY_ID,ADDRESS_1,CITY,STATE,ZIP,PROP_LAT,PROP_LONG FROM properties";
$result = $mysqli->query($query) or die(mysqli_error());

$i = 1;
while($row = mysqli_fetch_array($result)){
    foreach($row AS $key => $value) {
        $row[$key] = stripslashes($value);
    }
    $myaddress=$row['PROPERTY_ID'].','.$row['ADDRESS_1'].','.$row['CITY'].','.$row['STATE'].','.$row['ZIP'].','.$row['PROP_LAT'].','.$row['PROP_LONG'];
    $geo=convertAddress2Geo($myaddress);
    $query2 = "UPDATE properties SET PROP_LAT=$geo[0],PROP_LONG=$geo[1] WHERE PROPERTY_ID=$row[PROPERTY_ID]";
    #echo $query2;
    if ($geo[0] AND $geo[1]){
        $result2 = $mysqli->query($query2) or die(mysqli_error());
        echo $myaddress." done";
    }
    $i=$i+1;
}
echo "Completed";

?>