<?php
require_once("dashboard/include/db_connect.php");
$lead_id = $_GET['id'];
if ($lead_id == null) {
  header("Location: errorPage.php");
} else {
  $result = $mysqli->query("SELECT COUNT(*) AS TOTAL FROM search_report JOIN properties ON search_report.PROPERTY_ID=properties.PROPERTY_ID WHERE LEAD_ID=" . $lead_id .
						" AND TOUR_DATE IS NOT NULL AND TOUR_DATE!='0000-00-00' AND TOUR_TIME IS NOT NULL AND TOUR_TIME!='00:00:00'") or die($mysqli->error);
  $row = $row = mysqli_fetch_array($result);
  if ($row['TOTAL'] == null || $row['TOTAL'] == 0) {
	$tours_title = "My Appointments";
  } else {
	$tours_title = "<span style='font-weight:bold; color:#008A52'>My Tours (" . $row['TOTAL'] . ")</span>";
  }
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
	<meta charset="utf-8" />
	<title>SimpleHouseSolutions.com - Search Report</title>
	<link rel="shortcut icon" href="/favicon.ico" />
	<link rel="stylesheet" href="/css/crmds.css" />
	<link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/dojo/1.9.1/dijit/themes/claro/claro.css">
	<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBIf62qWrq4nVCn5ULkT1G9nHPiSFEEPrI"></script>
	<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/dojo/1.9.1/dojo/dojo.js" data-dojo-config="parseOnLoad:true, async:true"></script>
	<script type="text/javascript">
	  require([
		"dojo/parser",
		"dijit/layout/TabContainer",
		"dijit/layout/ContentPane",
		"dijit/form/Form",
		"dijit/form/Button",
		"dijit/form/DateTextBox",
		"dijit/form/Select",
		"dijit/form/TimeTextBox",
		"dijit/Dialog",
		"dijit/TitlePane"
	  ]);

	  var map1;
	  var map2;
	  var bounds1;
	  var bounds2;
	  var infowindow1;
	  var infowindow2;

	  require(["dojo/ready"], function(ready){
		ready(function(){
		  dojo.connect(dijit.byId("allProperties"), "onShow", function(){
			if (dijit.byId("mapdiv1").get("open") === true) {
			  google.maps.event.trigger(map1, 'resize');
			}
		  });

		  dojo.connect(dijit.byId("myFavorites"), "onShow", function(){
			if (dijit.byId("mapdiv2").get("open") === true) {
			  google.maps.event.trigger(map2, 'resize');
			}
		  });
		});
	  });

	  function callHelper(uri) {
		if (uri === "") {
		  return;
		}
		if (window.XMLHttpRequest) { // code for IE7+, Firefox, Chrome, Opera, Safari
		  xmlhttp = new XMLHttpRequest();
		} else { // code for IE6, IE5
		  xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange = function() {
		  if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
			var serverResponse = xmlhttp.responseText;
			if (serverResponse !== "") {
			  if (uri.indexOf("addToFavorites") !== -1) {
				window.location.reload();
			  }
			  if (uri.indexOf("removeFavorite") !== -1) {
				window.location.reload();
			  }
			  if (uri.indexOf("requestPrice") !== -1) {
				alert(serverResponse);
			  }
			  if (uri.indexOf("bookTour") !== -1) {
				alert(serverResponse);
			  }
			}
		  }
		};
		xmlhttp.open("GET", uri);
		xmlhttp.send();
	  }

	  function callHelperPost(uri, id) {
		if (uri === "") {
		  return;
		}
		if (window.XMLHttpRequest) { // code for IE7+, Firefox, Chrome, Opera, Safari
		  xmlhttp = new XMLHttpRequest();
		} else { // code for IE6, IE5
		  xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange = function() {
		  if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
			var serverResponse = xmlhttp.responseText;
			if (serverResponse !== "") {
			  if (uri.indexOf("bookTour") !== -1) {
				alert(serverResponse);
			  }
			}
		  }
		};
		xmlhttp.open("POST", uri, true);
		xmlhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		xmlhttp.send(
			"tour_date=" + escape(dijit.byId("tour_date_" + id).attr("displayedValue")) +
			"&tour_time=" +	escape(dijit.byId("tour_time_" + id).attr("displayedValue")) +
			"&tour_zone=" +	escape(dijit.byId("tour_zone_" + id).attr("displayedValue"))
		);
	  }

	  function loadPropInfo(infodiv) {
		var info = document.getElementById(infodiv);
		if (info.style.display === "none") {
		  info.style.display = "block";
		} else {
		  info.style.display = "none";
		}
	  }

	  function mapIt(marker, mapdiv) {
		scroll(0,0);
		dijit.byId(mapdiv).set("open", true);;
		google.maps.event.trigger(marker, 'click');
	  }

	  function switchImage(div, property, photo) {
		document.getElementById(div).src = "dashboard/getimage.php?id=" + property + "&image=photo_" + photo;
	  }
	</script>
  </head>
  <body class="claro">
	<div id="wrapper">
	  <div id="content">
		<div id="col0">
		  <div class="content-text">
			<!-- Begin Content -->
			<div id="header" style="float:left"><img src="dashboard/images/logo.png" /></div>

			<div data-dojo-type="dijit/layout/TabContainer" style="width: 100%; height: 100%;" doLayout="false">

			  <div id="allProperties" data-dojo-type="dijit/layout/ContentPane" title="All Properties">
				<div id="mapdiv1" data-dojo-type="dijit/TitlePane" data-dojo-props="title:'Map View', open:false">
				  <div id="map1" style="overflow:hidden; width:100%; height:500px; border:1px solid #000;"></div>
				  <script>
					var mapOptions = {
					  zoom: 8,
					  mapTypeId: google.maps.MapTypeId.ROADMAP
					};
					map1 = new google.maps.Map(document.getElementById('map1'), mapOptions);
					infowindow1 = new google.maps.InfoWindow({
					  content: "loading...",
					  maxWidth: 400
					});
					bounds1 = new google.maps.LatLngBounds();
				  </script>
				</div>
				<p />
				<?php
				$result = $mysqli->query("SELECT * FROM search_report JOIN properties ON search_report.property_id=properties.property_id WHERE rejected!=1 AND lead_id=" . $lead_id . " ORDER BY properties.CENTER_NAME ASC") or die($mysqli->error);
				if ($mysqli->affected_rows == 0) {
				  ?>
				  <div align="center"><h3>No Properties Found</h3></div>
				  <?php
				} else {
				  while ($row = mysqli_fetch_array($result)) {
					foreach ($row AS $key => $value) {
					  $row[$key] = stripslashes($value);
					}
					$property_id = $row['PROPERTY_ID'];
					?>
					<div class="content-box">
					  <fieldset>
						<div style="float:left; padding:5px; width:100px; min-height:110px;">
						  <?
						  $primary = $row['PRIMARY_PHOTO'];
						  if ($row['PHOTO_' . $primary] != null) {
						  ?>
	  					  <img src="dashboard/getimage.php?id=<?= $property_id ?>&image=photo_<?= $row['PRIMARY_PHOTO'] ?>" style="height:100px; width:100px;" />
						  <? } else { ?>
	  					  <img src="dashboad/images/nopic.png" style="height:100px; width:100px;" />
						  <? } ?>
						</div>
						<div style="float:left; padding:5px; width:200px; min-height:110px;">
						  <strong><?= $row['CENTER_NAME'] ?></strong><br />
						  <?php
						  $address = "";
						  if ($row['ADDRESS_1'] != '')
							$address .= $row['ADDRESS_1'] . '<br />';
						  if ($row['ADDRESS_2'] != '')
							$address .= $row['ADDRESS_2'] . '<br />';
						  if ($row['CITY'] != '')
							$address .= $row['CITY'] . ', ';
						  if ($row['STATE'] != '')
							$address .= $row['STATE'] . ' ';
						  if ($row['ZIP'] != '')
							$address .= $row['ZIP'] . '<br />';
						  echo $address;
						  ?>
						</div>
						<div style="float:left; padding:5px; width:300px; min-height:110px;">
						  <?php
						  if ($row['BUYER_DESCRIPTION'] != '') {
							echo substr(stripslashes($row['BUYER_DESCRIPTION']), 0, 250) . "...";
						  }
						  ?>
						</div>
						<p />
						<div style="float:right;">
						  <button type="button" data-dojo-type="dijit/form/Button" onclick="mapIt(marker_1_<?= $property_id ?>, 'mapdiv1');">Map It</button>
						  <button type="button" data-dojo-type="dijit/form/Button" onClick="loadPropInfo('info1_<?= $property_id ?>');">Property Info</button>
						  <button type="button" data-dojo-type="dijit/form/Button" onClick="callHelper('searchReportHelper.php?action=requestPrice&lead_id=<?= $lead_id ?>&property_id=<?= $row['PROPERTY_ID'] ?>');">Request Price</button>
						  <?php if ($row['FAVORITE'] == 0) { ?>
	  					  <button type="button" data-dojo-type="dijit/form/Button" onClick="callHelper('searchReportHelper.php?action=addToFavorites&lead_id=<?= $lead_id ?>&property_id=<?= $row['PROPERTY_ID'] ?>');">Add To Favorites</button>
						  <?php } else { ?>
	  					  <button type="button" data-dojo-type="dijit/form/Button" onClick="callHelper('searchReportHelper.php?action=removeFavorite&lead_id=<?= $lead_id ?>&property_id=<?= $row['PROPERTY_ID'] ?>');">Remove From Favorites</button>
						  <?php } ?>
						  <p />
						  <form data-dojo-type="dijit/form/Form" name="sendTourForm_<?= $property_id ?>" id="sendTourForm_<?= $property_id ?>">
							<input type="text" data-dojo-type="dijit/form/DateTextBox" name="tour_date_<?= $property_id ?>" id="tour_date_<?= $property_id ?>" style="width:8em" data-dojo-props="required:true" placeHolder="Date" />
							<input type="text" data-dojo-type="dijit/form/TimeTextBox" name="tour_time_<?= $property_id ?>" id="tour_time_<?= $property_id ?>" style="width:8em" data-dojo-props="constraints:{min:'T06:00:00', max:'T21:00:00', visibleRange:'T02:00:00'}, required:true" placeHolder="Time" />
							<select data-dojo-type="dijit/form/Select" name="tour_zone_<?= $property_id ?>" id="tour_zone_<?= $property_id ?>" style="width:4em" data-dojo-props="required:true" placeHolder="TZ">
							  <option value=""></option>
							  <option value="ET">ET</option>
							  <option value="CT">CT</option>
							  <option value="MT">MT</option>
							  <option value="PT">PT</option>
							</select>
							<button type="button" data-dojo-type="dijit/form/Button" id="sendTourSubmit_<?= $property_id ?>" onclick="callHelperPost('searchReportHelper.php?action=bookTour&lead_id=<?= $lead_id ?>&property_id=<?= $property_id ?>', <?= $property_id ?>);">Book Tour</button>
						  </form>
						</div>
						<script type="text/javascript">
						  var point_1_<?= $row['PROPERTY_ID'] ?> = new google.maps.LatLng(parseFloat(<?= $row['PROP_LAT'] ?>), parseFloat(<?= $row['PROP_LONG'] ?>));
						  var html_1_<?= $row['PROPERTY_ID'] ?> = "";
						  <? if ($row['PHOTO_' . $primary] != null) { ?>
							html_1_<?= $row['PROPERTY_ID'] ?> += "<div style='padding-right:5px;float:left;width:70px;'><img style='height:70px;width:70px;' src='dashboard/getimage.php?id=<?= $row['PROPERTY_ID'] ?>&image=photo_<?= $primary ?>' /></div>";
						  <? } else { ?>
							html_1_<?= $row['PROPERTY_ID'] ?> += "<div style='padding-right:5px;float:left;width:60px;'><img style='height:60px;width:60px;' src='dashboard/images/nopic.png' /></div>";
						  <? } ?>
						  html_1_<?= $row['PROPERTY_ID'] ?> += "<div align='left' style='font-size:10px;float:left;min-width:300px;'><strong><?= $row['CENTER_NAME'] ?></strong><br />";
						  html_1_<?= $row['PROPERTY_ID'] ?> += "<?= $address ?>";
						  html_1_<?= $row['PROPERTY_ID'] ?> += "</div>";
						  var marker_1_<?= $row['PROPERTY_ID'] ?> = new google.maps.Marker({
							position: point_1_<?= $row['PROPERTY_ID'] ?>,
							html: html_1_<?= $row['PROPERTY_ID'] ?>,
							map: map1
						  });
						  google.maps.event.addListener(marker_1_<?= $row['PROPERTY_ID'] ?>, 'click', function() {
							infowindow1.setContent(this.html);
							infowindow1.open(map1, this);
						  });
						  bounds1.extend(point_1_<?= $row['PROPERTY_ID'] ?>);
						</script>
						<p />
						<div id="info1_<?= $property_id ?>" style="float:left; padding:5px; margin-top:10px; width:100%; display:none;">
							<div style="float:left; width:30%;">
								<h3>Photos</h3>
								<p />
								<?
								$primary = $row['PRIMARY_PHOTO'];
								if ($row['PHOTO_' . $primary] != null) {
								?>
								<img id="primary1_<?= $property_id ?>" class="thumbnail" src="dashboard/getimage.php?id=<?= $property_id ?>&image=photo_<?= $row['PRIMARY_PHOTO'] ?>" />
								<? } else { ?>
								<img src="dashboard/images/logo.png" />
								<? } ?>
								<p />
								<? if ($row['PHOTO_1'] != null) { ?><img class="thumbnail" src="dashboard/getimage.php?id=<?= $property_id ?>&image=photo_1" onClick="switchImage('primary1_<?= $property_id ?>', <?= $property_id ?>, 1);" /><? } ?>
								<? if ($row['PHOTO_2'] != null) { ?><img class="thumbnail" src="dashboard/getimage.php?id=<?= $property_id ?>&image=photo_2" onClick="switchImage('primary1_<?= $property_id ?>', <?= $property_id ?>, 2);" /><? } ?>
								<? if ($row['PHOTO_3'] != null) { ?><img class="thumbnail" src="dashboard/getimage.php?id=<?= $property_id ?>&image=photo_3" onClick="switchImage('primary1_<?= $property_id ?>', <?= $property_id ?>, 3);" /><? } ?>
								<? if ($row['PHOTO_4'] != null) { ?><img class="thumbnail" src="dashboard/getimage.php?id=<?= $property_id ?>&image=photo_4" onClick="switchImage('primary1_<?= $property_id ?>', <?= $property_id ?>, 4);" /><? } ?>
								<? if ($row['PHOTO_5'] != null) { ?><img class="thumbnail" src="dashboard/getimage.php?id=<?= $property_id ?>&image=photo_5" onClick="switchImage('primary1_<?= $property_id ?>', <?= $property_id ?>, 5);" /><? } ?>
								<? if ($row['PHOTO_6'] != null) { ?><img class="thumbnail" src="dashboard/getimage.php?id=<?= $property_id ?>&image=photo_6" onClick="switchImage('primary1_<?= $property_id ?>', <?= $property_id ?>, 6);" /><? } ?>
							</div>
							<div style="float:left; width:35%;">
								<h3>Description</h3>
								<p />
								<?= stripslashes(nl2br($row['BUYER_DESCRIPTION'])) ?>
							</div>
							<div style="float:right; width:30%; padding-left:50px;">
								<h3>Amenities</h3>
								<p />
								<? if ($row['LEASED'] == 1) { ?><img src="dashboard/images/check-icon.png" />&nbsp;&nbsp;Leased<br /><? } ?>
								<? if ($row['NEEDS_WORK'] == 1) { ?><img src="dashboard/images/check-icon.png" />&nbsp;&nbsp;Needs Work<br /><? } ?>
								<? if ($row['FULLY_RENOVATED'] == 1) { ?><img src="dashboard/images/check-icon.png" />&nbsp;&nbsp;Fully Renovated<br /><? } ?>
								<? if ($row['RENTAL_GRADE_FINISH'] == 1) { ?><img src="dashboard/images/check-icon.png" />&nbsp;&nbsp;Rental Grade Finish<br /><? } ?>
							</div>
						</div>
					  </fieldset>
					</div>
					<?php
				  }
				}
				?>
			  </div>

			  <script type="text/javascript">
				  map1.fitBounds(bounds1);
			  </script>

			  <div id="myFavorites" data-dojo-type="dijit/layout/ContentPane" title="My Favorites">
				<div id="mapdiv2" data-dojo-type="dijit/TitlePane" data-dojo-props="title:'Map View', open:false">
				  <div id="map2" style="overflow:hidden; width:100%; height:500px; border:1px solid #000;"></div>
				  <script>
					map2 = new google.maps.Map(document.getElementById('map2'), mapOptions);
					infowindow2 = new google.maps.InfoWindow({
					  content: "loading...",
					  maxWidth: 400
					});
					bounds2 = new google.maps.LatLngBounds();
				  </script>
				</div>
				<p />
				  <?php
				  $result = $mysqli->query("SELECT * FROM search_report JOIN properties ON search_report.property_id=properties.property_id WHERE rejected!=1 AND lead_id=" . $lead_id . " AND FAVORITE=1 ORDER BY properties.CENTER_NAME ASC") or die($mysqli->error);
				  if ($mysqli->affected_rows == 0) {
				  ?>
					<div align="center"><h3>No Favorite Properties Selected</h3></div>
				  <?php
				  } else {
					while ($row = mysqli_fetch_array($result)) {
					  foreach ($row AS $key => $value) {
						$row[$key] = stripslashes($value);
					  }
					  $property_id = $row['PROPERTY_ID'];
				  ?>
				  <div class="content-box">
					<fieldset>
					  <div style="float:left; padding:5px; width:100px; min-height:110px;">
						<?
						$primary = $row['PRIMARY_PHOTO'];
						if ($row['PHOTO_' . $primary] != null) {
						?>
						<img src="dashboard/getimage.php?id=<?= $property_id ?>&image=photo_<?= $row['PRIMARY_PHOTO'] ?>" style="height:100px; width:100px;" />
						<? } else { ?>
						<img src="dashboard/images/nopic.png" style="height:100px; width:100px;" />
						<? } ?>
					  </div>
					  <div style="float:left; padding:5px; width:200px; min-height:110px;">
						<strong><?= $row['CENTER_NAME'] ?></strong><br />
						<?php
						$address = "";
						if ($row['ADDRESS_1'] != '')
						  $address .= $row['ADDRESS_1'] . '<br />';
						if ($row['ADDRESS_2'] != '')
						  $address .= $row['ADDRESS_2'] . '<br />';
						if ($row['CITY'] != '')
						  $address .= $row['CITY'] . ', ';
						if ($row['STATE'] != '')
						  $address .= $row['STATE'] . ' ';
						if ($row['ZIP'] != '')
						  $address .= $row['ZIP'] . '<br />';
						echo $address;
						?>
					  </div>
					  <div style="float:left; padding:5px; width:300px; min-height:110px;">
						<?php
						if ($row['BUYER_DESCRIPTION'] != '') {
						  echo substr(stripslashes($row['BUYER_DESCRIPTION']), 0, 250) . "...";
						}
						?>
					  </div>
					  <p />
					  <div style="float:right;">
						<button type="button" data-dojo-type="dijit/form/Button" onclick="mapIt(marker_2_<?= $property_id ?>, 'mapdiv2');">Map It</button>
						<button type="button" data-dojo-type="dijit/form/Button" onClick="loadPropInfo('info2_<?= $property_id ?>');">Property Info</button>
						<button type="button" data-dojo-type="dijit/form/Button" onClick="callHelper('searchReportHelper.php?action=requestPrice&lead_id=<?= $lead_id ?>&property_id=<?= $row['PROPERTY_ID'] ?>');">Request Price</button>
						<?php if ($row['FAVORITE'] == 0) { ?>
						<button type="button" data-dojo-type="dijit/form/Button" onClick="callHelper('searchReportHelper.php?action=addToFavorites&lead_id=<?= $lead_id ?>&property_id=<?= $row['PROPERTY_ID'] ?>');">Add To Favorites</button>
						<?php } else { ?>
						<button type="button" data-dojo-type="dijit/form/Button" onClick="callHelper('searchReportHelper.php?action=removeFavorite&lead_id=<?= $lead_id ?>&property_id=<?= $row['PROPERTY_ID'] ?>');">Remove From Favorites</button>
						<?php } ?>
						<p />
						<form data-dojo-type="dijit/form/Form" name="sendTourForm_<?= $property_id ?>" id="sendTourForm_2_<?= $property_id ?>">
							<input type="text" data-dojo-type="dijit/form/DateTextBox" name="tour_date_<?= $property_id ?>" id="tour_date_2_<?= $property_id ?>" style="width:8em" data-dojo-props="required:true, placeHolder:'Date'" />
							<input type="text" data-dojo-type="dijit/form/TimeTextBox" name="tour_time_<?= $property_id ?>" id="tour_time_2_<?= $property_id ?>" style="width:8em" data-dojo-props="constraints:{min:'T06:00:00', max:'T21:00:00', visibleRange:'T02:00:00'}, required:true, placeHolder:'Time'" />
							<select data-dojo-type="dijit/form/Select" name="tour_zone_<?= $property_id ?>" id="tour_zone_2_<?= $property_id ?>" style="width:4em" data-dojo-props="required:true, placeHolder:'TZ'">
							  <option value=""></option>
							  <option value="ET">ET</option>
							  <option value="CT">CT</option>
							  <option value="MT">MT</option>
							  <option value="PT">PT</option>
							</select>
							<button type="button" data-dojo-type="dijit/form/Button" id="sendTourSubmit_2_<?= $property_id ?>" onclick="callHelperPost('searchReportHelper.php?action=bookTour&lead_id=<?= $lead_id ?>&property_id=<?= $property_id ?>', <?= $property_id ?>);">Book Tour</button>
						</form>
					  </div>
					  <script type="text/javascript">
						  var point_2_<?= $row['PROPERTY_ID'] ?> = new google.maps.LatLng(parseFloat(<?= $row['PROP_LAT'] ?>), parseFloat(<?= $row['PROP_LONG'] ?>));
						  var html_2_<?= $row['PROPERTY_ID'] ?> = "";
						  <? if ($row['PHOTO_' . $primary] != null) { ?>
							html_2_<?= $row['PROPERTY_ID'] ?> += "<div style='padding-right:5px;float:left;width:70px;'><img style='height:70px;width:70px;' src='/getimage.php?id=<?= $row['PROPERTY_ID'] ?>&image=photo_<?= $primary ?>' /></div>";
						  <? } else { ?>
							html_2_<?= $row['PROPERTY_ID'] ?> += "<div style='padding-right:5px;float:left;width:60px;'><img style='height:60px;width:60px;' src='dashboard/images/logo-nopic.png' /></div>";
						  <? } ?>
						  html_2_<?= $row['PROPERTY_ID'] ?> += "<div align='left' style='font-size:10px;float:left;min-width:300px;'><strong><?= $row['CENTER_NAME'] ?></strong><br />";
						  html_2_<?= $row['PROPERTY_ID'] ?> += "<?= $address ?>";
						  html_2_<?= $row['PROPERTY_ID'] ?> += "</div>";
						  var marker_2_<?= $row['PROPERTY_ID'] ?> = new google.maps.Marker({
							position: point_2_<?= $row['PROPERTY_ID'] ?>,
							html: html_2_<?= $row['PROPERTY_ID'] ?>,
							map: map2
						  });
						  google.maps.event.addListener(marker_2_<?= $row['PROPERTY_ID'] ?>, 'click', function() {
							infowindow2.setContent(this.html);
							infowindow2.open(map2, this);
						  });
						  bounds2.extend(point_2_<?= $row['PROPERTY_ID'] ?>);
					  </script>
					  <p />
					  <div id="info2_<?= $property_id ?>" style="float:left; padding:5px; margin-top:10px; width:100%; display:none;">
						  <div style="float:left; width:30%;">
								<h3>Photos</h3>
								<p />
								<?
								$primary = $row['PRIMARY_PHOTO'];
								if ($row['PHOTO_' . $primary] != null) {
								?>
								<img id="primary2_<?= $property_id ?>" class="thumbnail" src="dashboard/getimage.php?id=<?= $property_id ?>&image=photo_<?= $row['PRIMARY_PHOTO'] ?>" />
								<? } else { ?>
								<img src="dashboard/images/logo.png" />
								<? } ?>
								<p />
								<? if ($row['PHOTO_1'] != null) { ?><img class="thumbnail" src="dashboard/getimage.php?id=<?= $property_id ?>&image=photo_1" onClick="switchImage('primary2_<?= $property_id ?>', <?= $property_id ?>, 1);" /><? } ?>
								<? if ($row['PHOTO_2'] != null) { ?><img class="thumbnail" src="dashboard/getimage.php?id=<?= $property_id ?>&image=photo_2" onClick="switchImage('primary2_<?= $property_id ?>', <?= $property_id ?>, 2);" /><? } ?>
								<? if ($row['PHOTO_3'] != null) { ?><img class="thumbnail" src="dashboard/getimage.php?id=<?= $property_id ?>&image=photo_3" onClick="switchImage('primary2_<?= $property_id ?>', <?= $property_id ?>, 3);" /><? } ?>
								<? if ($row['PHOTO_4'] != null) { ?><img class="thumbnail" src="dashboard/getimage.php?id=<?= $property_id ?>&image=photo_4" onClick="switchImage('primary2_<?= $property_id ?>', <?= $property_id ?>, 4);" /><? } ?>
								<? if ($row['PHOTO_5'] != null) { ?><img class="thumbnail" src="dashboard/getimage.php?id=<?= $property_id ?>&image=photo_5" onClick="switchImage('primary2_<?= $property_id ?>', <?= $property_id ?>, 5);" /><? } ?>
								<? if ($row['PHOTO_6'] != null) { ?><img class="thumbnail" src="dashboard/getimage.php?id=<?= $property_id ?>&image=photo_6" onClick="switchImage('primary2_<?= $property_id ?>', <?= $property_id ?>, 6);" /><? } ?>
							</div>
							<p />
							<div style="float:left; width:35%;">
								<h3>Description</h3>
								<p />
								<?= stripslashes(nl2br($row['BUYER_DESCRIPTION'])) ?>
							</div>
							<div style="float:left; width:30%; padding-left:50px;">
								  <h3>Amenities</h3>
								  <p />
								  <? if ($row['LEASED'] == 1) { ?><img src="dashboard/images/check-icon.png" />&nbsp;&nbsp;Leased<br /><? } ?>
								  <? if ($row['NEEDS_WORK'] == 1) { ?><img src="dashboard/images/check-icon.png" />&nbsp;&nbsp;Needs Work<br /><? } ?>
								  <? if ($row['FULLY_RENOVATED'] == 1) { ?><img src="dashboard/images/check-icon.png" />&nbsp;&nbsp;Fully Renovated<br /><? } ?>
								  <? if ($row['RENTAL_GRADE_FINISH'] == 1) { ?><img src="dashboard/images/check-icon.png" />&nbsp;&nbsp;Rental Grade Finish<br /><? } ?>
							</div>
						</div>
					</fieldset>
				  </div>
				  <?php
				  }
				}
				?>
			  </div>

			  <script type="text/javascript">
				map2.fitBounds(bounds2);
			  </script>

			  <div data-dojo-type="dijit/layout/ContentPane" title="<?=$tours_title?>">
				<?php
				$result = $mysqli->query("SELECT * FROM search_report JOIN properties ON search_report.PROPERTY_ID=properties.PROPERTY_ID WHERE LEAD_ID=" . $lead_id .
						" AND TOUR_DATE IS NOT NULL AND TOUR_DATE!='0000-00-00' AND TOUR_TIME IS NOT NULL AND TOUR_TIME!='00:00:00' ORDER BY TOUR_DATE, TOUR_TIME") or die($mysqli->error);
				if ($mysqli->affected_rows > 0) {
				  while ($row = mysqli_fetch_array($result)) {
					  $address = "";
					  if ($row['CENTER_NAME'] != "")
						$address .= $row['CENTER_NAME'] . '<br />';
					  if ($row['ADDRESS_1'] != "")
						$address .= $row['ADDRESS_1'] . '<br />';
					  if ($row['ADDRESS_2'] != "")
						$address .= $row['ADDRESS_2'] . '<br />';
					  if ($row['CITY'] != "")
						$address .= $row['CITY'] . ', ';
					  if ($row['STATE'] != "")
						$address .= $row['STATE'] . ' ';
					  if ($row['ZIP'] != "")
						$address .= $row['ZIP'] . '<br />';
					  if ($row['PRIMARY_PHOTO'] != null) {
						$image = '<img src="dashboard/getimage.php?id=' . $row['PROPERTY_ID'] . '&image=photo_' . $row['PRIMARY_PHOTO'] . '" style="height:100px;width:100px;" />';
					  }
					  $contact_name = "";
					  if ($row['CONTACT_NAME'] != '') {
						$contact_name = $row['CONTACT_NAME'];
					  }
					  $contact_phone = "";
					  if ($row['OFFICE_PHONE'] != '') {
						$contact_phone = '<a href="tel:' . $row['OFFICE_PHONE'] . '">' . $row['OFFICE_PHONE'] . '</a>';
					  }
					  $map_link = "";
					  if ($row['PROP_LAT'] != '' && $row['PROP_LONG'] != '') {
						$map_link .= '<a href="http://maps.google.com/maps?q=' . $row['PROP_LAT'] . ',' . $row['PROP_LONG'] . '" target="_new">Map Link</a>';
					  }
					  ?>
					  <div class="content-box">
						<fieldset>
						  <div style="float:left; padding:5px; width:100px; min-height:100px;">
							<? if ($row['PRIMARY_PHOTO'] != null) { ?>
							  <img src="dashboard/getimage.php?id=<?=$row['PROPERTY_ID']?>&image=photo_<?=$row['PRIMARY_PHOTO']?>" style="height:100px;width:100px;" />
							<? } else { ?>
							  <img src="dashboard/images/nopic.png" style="height:100px;width:100px;" />
							<? } ?>
						  </div>
						  <div style="float:left; padding:5px; width:400px; min-height:100px;">
							<strong>Date:&nbsp;</strong><?= date('m/d/Y', strtotime($row['TOUR_DATE'])) ?><br />
							<strong>Time:&nbsp;</strong><?= date('g:i a', strtotime($row['TOUR_TIME'])) ?><br />
							<? if ($contact_name!="") { ?><strong>Contact Name:&nbsp</strong><?= $contact_name ?><br /><? } ?>
							<? if ($contact_phone!="") { ?><strong>Contact Phone:&nbsp</strong><?= $contact_phone ?><br /><? } ?>
							<? if ($map_link!="") { ?><strong>Directions:&nbsp</strong><?= $map_link ?><br /><? } ?>
							<? if ($address!="") { ?><strong>Address:</strong><br /><?= $address ?><? } ?>
							<?php
							if ($row['PROP_LAT'] != '' && $row['PROP_LONG'] != '') {
							  $map_link .= '<a href="http://maps.google.com/maps?q=' . $row['PROP_LAT'] . ',' . $row['PROP_LONG'] . '" target="_new">Map Link</a>';
							}
							?>
						  </div>
						</fieldset>
					  </div>
					<?php
					  }
					} else {
					?>
					<div align="center"><h3>No Tours Scheduled</h3></div>
					<?php } ?>
			  </div>

			</div>

			<?php
			$mysqli->query("UPDATE leads SET SEARCH_REPORT_READ=SYSDATE() WHERE LEAD_ID=" . $lead_id) or die($mysqli->error);
			?>
			<!-- End Content -->
		  </div>
		</div>
	  </div>
	</div>
	<div data-dojo-type="dijit/Dialog" data-dojo-id="propInfoDialog" title="Property Info" style="display: none;">
	</div>
  </body>
</html>