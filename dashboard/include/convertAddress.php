<?php

function convertAddress2Geo($address){
    $url="https://maps.googleapis.com/maps/api/geocode/xml?address=$address";
    $xml = simplexml_load_file($url);
    $lat=$xml->result->geometry->location->lat;
    $lng=$xml->result->geometry->location->lng;
    $geo=array($lat,$lng);
    return $geo;
}

?>