<?php
require_once("include/db_connect.php");

// Get parameters from URL
$center_lat = $mysqli->real_escape_string($_GET["lat"]);
$center_lng = $mysqli->real_escape_string($_GET["lng"]);
$radius = $mysqli->real_escape_string($_GET["radius"]);
$county = $mysqli->real_escape_string($_GET["county"]);
$type = $mysqli->real_escape_string($_GET["type"]);
$typeDetails = $_GET["typeDetails"];

if ($type=="Virtual Office") {
	$property_type = "(PROPERTY_TYPE='Virtual Office' OR PROPERTY_TYPE='Executive Suite')";
} else {
	$property_type = "PROPERTY_TYPE='" . $type . "'";
}

// Start XML file, create parent node
$dom = new DOMDocument("1.0");
$node = $dom->createElement("properties");
$parnode = $dom->appendChild($node);

// Search the rows in the properties table
$query = "SELECT *, (3959 * acos(cos(radians('" . $center_lat . "') ) * cos( radians(PROP_LAT)) *
		  cos(radians(PROP_LONG) - radians('" . $center_lng . "')) +
		  sin(radians('" . $center_lat . "')) * sin(radians(PROP_LAT))))
		  AS distance FROM properties HAVING distance < '" . $radius . "' ";
		if ($county != null) {
			$query .= "AND LEAD_COUNTIES like '%" . $county . "%' ";
		}
		if ($typeDetails != null) {
			$query .= "AND (";
			$length = count($typeDetails);
			for($i = 0; $i < $length; $i++) {
				if ($i == 0) {
					$query .= "PROPERTY_TYPE_DETAILS like '%" . $typeDetails[$i] . "%' ";
				} else {
					$query .= "OR PROPERTY_TYPE_DETAILS like '%" . $typeDetails[$i] . "%' ";
				}
			}
			$query .= ") ";
		}
$query .= "AND " . $property_type . " AND status!='delisted' ORDER BY distance";
error_log($query);
$result = $mysqli->query($query);

header("Content-type: text/xml");

// Iterate through the rows, adding XML nodes for each
while ($row = @mysqli_fetch_assoc($result)){
	$primary = $row['PRIMARY_PHOTO'];
	if ($row["PHOTO_" . $primary]==null) {
		$photo = null;
	} else {
		$photo = "photo_" . $primary;
	}
  $node = $dom->createElement("marker");
  $newnode = $parnode->appendChild($node);
  $newnode->setAttribute("id", $row['PROPERTY_ID']);
  $newnode->setAttribute("center_name", $row['CENTER_NAME']);
  $newnode->setAttribute("contact_name", $row['CONTACT_NAME']);
  $newnode->setAttribute("contact_email", $row['CONTACT_EMAIL']);
  $newnode->setAttribute("office_phone", $row['OFFICE_PHONE']);
  $newnode->setAttribute("address", $row['ADDRESS_1']);
  $newnode->setAttribute("address2", $row['ADDRESS_2']);
  $newnode->setAttribute("city", $row['CITY']);
  $newnode->setAttribute("state", $row['STATE']);
  $newnode->setAttribute("zip", $row['ZIP']);
  $newnode->setAttribute("lat", $row['PROP_LAT']);
  $newnode->setAttribute("lng", $row['PROP_LONG']);
  $newnode->setAttribute("distance", $row['distance']);
  $newnode->setAttribute("photo", $photo);
}

echo $dom->saveXML();
?>