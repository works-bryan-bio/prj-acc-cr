<?php
require_once("include/checklogin.php");
require_once("include/session.php");
require_once("include/db_connect.php");

// store the lead_id if set
$lead_id = null;
if (isset($_GET['lead_id'])) {
    $lead_id = $_GET['lead_id'];
}

$result = $mysqli->query("SELECT * FROM leads WHERE lead_id=" . $lead_id) or die(mysql_error());
while ($row = mysqli_fetch_array($result)) {
    foreach ($row AS $key => $value) {
        $row[$key] = stripslashes($value);
    }
    $search_city = $row['SEARCH_CITY'];
    $search_state = $row['SEARCH_STATE'];
    $property_type = $row['PROPERTY_TYPE'];
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>SimpleHouseSolutions.com - Dashboard</title>
        <link rel="shortcut icon" href="/favicon.ico" />
        <link rel="stylesheet" type="text/css" href="css/dashboard.css"/>
        <link rel="stylesheet" type="text/css" href="css/dashboard_menu.css"/>
        <link rel="stylesheet" type="text/css" href="js/tigra_calendar/calendar.css">
        <script type="text/javascript" src="js/tigra_calendar/calendar_db.js"></script>
        <script type="text/javascript" src="js/tiny_mce/tiny_mce.js"></script>
        <script type="text/javascript" src="js/site.js"></script>
        <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBIf62qWrq4nVCn5ULkT1G9nHPiSFEEPrI"></script>
        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
        <script type="text/javascript">
            //<![CDATA[
            var map;
            var geocoder;
            var infowindow;
            var markersArray = [];

			function getCheckedBoxes(chkboxName) {
				var checkboxes = document.getElementsByName(chkboxName);
				var checkboxesChecked = [];
				// loop over them all
				for (var i=0; i<checkboxes.length; i++) {
				   // And stick the checked ones onto an array...
				   if (checkboxes[i].checked) {
					  checkboxesChecked.push(checkboxes[i].value);
				   }
				}
				// Return the array if it is non-empty, or null
				return checkboxesChecked.length > 0 ? checkboxesChecked : null;
			  }

            function load() {
                geocoder = new google.maps.Geocoder();
                var mapOptions = {
                    zoom: 8,
                    mapTypeId: google.maps.MapTypeId.ROADMAP
                };
                map = new google.maps.Map(document.getElementById('map'), mapOptions);
                infowindow = new google.maps.InfoWindow({
                    content: "loading...",
                    maxWidth: 400
                });
                var searchCity = "<?= $search_city ?>";
                var searchState = "<?= $search_state ?>";
                var searchString = "";
                if (searchCity !== "" && searchState === "")
                    searchString = searchCity;
                if (searchCity !== "" && searchState !== "")
                    searchString = searchCity + ", " + searchState;
                if (searchCity === "" && searchState !== "")
                    searchString = searchState;
                var propType = "<?= $property_type ?>";
                if (propType !== null && propType !== "") {
                    document.getElementById('property_type').value = propType;
                }
                if (searchString !== "") {
                    document.getElementById('addressInput').value = searchString;
                    doSearch();
                } else {
                    document.getElementById('addressInput').focus();
                }
            }

            function doSearch() {
				var address = document.getElementById('addressInput').value;
				var county = document.getElementById('county').value;
				var type = document.getElementById('property_type').value;
			    var type_details = getCheckedBoxes('property_type_details');
                var radius = document.getElementById('radiusSelect').value;
                if (address !== "") {
                    geocoder.geocode({'address': address}, function (results, status) {
                        if (status === google.maps.GeocoderStatus.OK) {
                            if (results[0].geometry.location) {
                                searchLocations(results[0].geometry.location, radius, county, type, type_details);
                            } else {
                                document.getElementById('sidebar').innerHTML = "<span style='color:red;'>Error: Address Not Found</span>";
                            }
                        } else {
                            document.getElementById('sidebar').innerHTML = "<span style='color:red;'>Error: " + results + "</span>";
                        }
                    });
                } else {
                    document.getElementById('addressInput').focus();
                }
            }

            function searchLocations(center, radius, county, type, type_details) {
                clearOverlays();
                var searchUrl = 'gmap_search.php?lat=' + center.lat() + '&lng=' + center.lng() + '&radius=' + radius + '&county=' + county  + '&type=' + type;
				if (type_details	&& type_details.length > 0) {
					for (var i=0; i<type_details.length; i++) {
						searchUrl += "&typeDetails[]=" + type_details[i];
					}
				}

                $.ajax({
                    type: "GET",
                    url: searchUrl,
                    dataType: "xml",
                    success: function (xml) {
                        var properties = [];
                        $(xml).find("marker").each(function () {
                            properties.push($(this));
                        });
                        var sidebar = document.getElementById('sidebar');
                        sidebar.innerHTML = '<div align=\'center\'><input class=\'button\' type=\'submit\' id=\'addPropsBtn\' value=\'Add To Report\' \/></div>' +
                                '<input type=\'checkbox\' id=\'selectAll\' onClick=\'javascript:checkAll()\' \/><b>Select All</b><br \/>';
                        var bounds = new google.maps.LatLngBounds();
                        if (properties.length === 0) {
                            sidebar.innerHTML = 'No Results Found';
                            var point = new google.maps.LatLng(center.lat(), center.lng());
                            bounds.extend(point);
                            map.setCenter(bounds.getCenter());
                        } else {
                            for (var i = 0; i < properties.length; i++) {
                                var id = properties[i][0].getAttribute('id');
                                var center_name = properties[i][0].getAttribute('center_name');
                                var contact_name = properties[i][0].getAttribute('contact_name');
                                var contact_email = properties[i][0].getAttribute('contact_email');
                                var office_phone = properties[i][0].getAttribute('office_phone');
                                var address = properties[i][0].getAttribute('address');
                                var address2 = properties[i][0].getAttribute('address2');
                                var city = properties[i][0].getAttribute('city');
                                var state = properties[i][0].getAttribute('state');
                                var zip = properties[i][0].getAttribute('zip');
                                var photo = properties[i][0].getAttribute('photo');
                                var distance = parseFloat(properties[i][0].getAttribute('distance'));
                                var point = new google.maps.LatLng(parseFloat(properties[i][0].getAttribute('lat')), parseFloat(properties[i][0].getAttribute('lng')));
                                var marker = createMarker(point, id, center_name, contact_name, contact_email, office_phone, address, address2, city, state, zip, photo);
                                var sidebarEntry = createSidebarEntry(marker, id, center_name, contact_name, contact_email, office_phone, address, address2, city, state, zip, distance);
                                sidebar.appendChild(sidebarEntry);
                                bounds.extend(point);
                            }
                            map.fitBounds(bounds);
                        }
                    }
                });
            }

            function createMarker(point, id, center_name, contact_name, contact_email, office_phone, address, address2, city, state, zip, photo) {
                var html = '';
                if (photo !== null && photo !== "") {
                    html += '<div style=\'padding-right:5px;float:left;width:70px;\'><img style=\'height:70px;width:70px;\' src=\'getimage.php?id=' + id + '&image=' + photo + '\' /></div>';
                } else {
                    html += '<div style=\'padding-right:5px;float:left;width:60px;\'><img style=\'height:60px;width:60px;\' src=\'images/nopic.png\' /></div>';
                }
                html += '<div align=\'left\' style=\'font-size:10px;float:left;min-width:300px;\'><b>' + center_name + '</b>';
                if (address !== '')
                    html += '<br/>' + address;
                if (address2 !== '')
                    html += '<br/>' + address2;
                if (city !== '')
                    html += '<br/>' + city;
                if (state !== '')
                    html += ', ' + state;
                if (zip !== '')
                    html += ' ' + zip;
                if (contact_name !== '') {
                    html += '<br/>' + contact_name + " - " + office_phone;
                } else {
                    html += '<br/>' + office_phone;
                }
                if (contact_email !== '') {
                    contact_email = contact_email.replace(';', '<br />');
                    contact_email = contact_email.replace(',', '<br />');
                    html += '<br/>' + contact_email;
                }
                html += '<br /><a href=\"javascript:void(0)\" onclick=\"document.getElementById(\'selectedProperties_' + id + '\').checked=true;\">Select</a>';
                html += '&nbsp;&nbsp;<a href=\"javascript:void(0)\" onclick=\"document.getElementById(\'selectedProperties_' + id + '\').checked=false;\">Unselect</a>';
                html += '<\/div>';
                var marker = new google.maps.Marker({
                    position: point,
                    html: html,
                    map: map
                });
                markersArray.push(marker);
                google.maps.event.addListener(marker, 'click', function () {
                    infowindow.setContent(this.html);
                    infowindow.open(map, this);
                });
                return marker;
            }

            function createSidebarEntry(marker, id, center_name, contact_name, contact_email, office_phone, address, address2, city, state, zip, distance) {
                var div = document.createElement('div');
                var html = '<input class=\'checkbox\' type=\'checkbox\' id=\'selectedProperties_' + id + '\' name=\'selectedProperties[]\' value=\'' + id + '\' />';
                html += '<span id=\'prop_' + id + '\'><b>' + center_name + '</b> (' + distance.toFixed(1) + ')<br />';
                if (address !== '')
                    html += address + ', ';
                if (address2 !== '')
                    html += address2 + ', ';
                if (city !== '')
                    html += city + ', ';
                if (state !== '')
                    html += state + ' ';
                if (zip !== '')
                    html += zip;
                if (contact_name !== '') {
                    html += '<br/>' + contact_name + " - " + office_phone;
                } else {
                    html += '<br/>' + office_phone;
                }
                if (contact_email !== '')
                    html += '<br/>' + contact_email;
                html += '</span>';
                div.innerHTML = html;
                div.style.cursor = 'pointer';
                div.style.marginBottom = '10px';
                div.style.fontSize = '12px';
                div.addEventListener('mouseover', function () {
                    div.style.backgroundColor = '#f2f2f2';
                });
                div.addEventListener('mouseout', function () {
                    div.style.backgroundColor = 'transparent';
                });
                div.addEventListener('click', function () {
                    google.maps.event.trigger(marker, 'click');
                });
                return div;
            }

            function mapIt(id, center_name, contact_name, contact_email, office_phone, address, address2, city, state, zip, photo, lat, lng) {
                clearOverlays();
                var bounds = new google.maps.LatLngBounds();
                var point = new google.maps.LatLng(parseFloat(lat), parseFloat(lng));
                var marker = createMarker(point, id, center_name, contact_name, contact_email, office_phone, address, address2, city, state, zip, photo);
                marker.setMap(map);
                bounds.extend(point);
                map.setZoom(15);
                map.setCenter(bounds.getCenter());
            }

            // Removes the overlays from the map, but keeps them in the array
            function clearOverlays() {
                if (markersArray) {
                    for (i in markersArray) {
                        markersArray[i].setMap(null);
                    }
                    markersArray = [];
                }
            }

            function searchKeyPress(e) {
                if (window.event) {
                    e = window.event;
                }
                if (e.keyCode === 13) {
                    document.getElementById('btnSearch').click();
                }
            }

            function checkAll() {
                var field = document.selectForm.selectedProperties;
                if (document.selectForm.selectAll.checked === true) {
                    $(".checkbox").attr("checked", "checked");

                }
                if (document.selectForm.selectAll.checked === false) {
                    $(".checkbox").removeAttr('checked');
                }
            }

            function callHelper(uri) {
                if (uri === "") {
                    return;
                }
                if (window.XMLHttpRequest) { // code for IE7+, Firefox, Chrome, Opera, Safari
                    xmlhttp = new XMLHttpRequest();
                } else { // code for IE6, IE5
                    xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
                }
                xmlhttp.onreadystatechange = function () {
                    if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
                        var serverResponse = xmlhttp.responseText;
                        if (serverResponse !== "") {
                            if (uri.indexOf("generateAppointment") !== -1) {
                                if (serverResponse.indexOf("Error:") !== -1) {
                                    $('.popup_block').hide();
                                    $('#fade, a.close').remove();
                                    alert(serverResponse);
                                } else {
                                    document.getElementById('message').value = serverResponse;
                                    tinyMCE.execCommand('mceAddControl', true, 'message');
                                }
                            }
                            if (uri.indexOf("generateUpdate") !== -1) {
                                if (serverResponse.indexOf("Error:") !== -1) {
                                    $('.popup_block').hide();
                                    $('#fade, a.close').remove();
                                    alert(serverResponse);
                                } else {
                                    document.getElementById('update_message').value = serverResponse;
                                    tinyMCE.execCommand('mceAddControl', true, 'update_message');
                                }
                            }
                            if (uri.indexOf("sendLead") !== -1) {
                                alert(serverResponse);
                            }
                            if (uri.indexOf("sendSearchReport") !== -1) {
                                alert(serverResponse);
                            }
                            if (uri.indexOf("sendIntro") !== -1) {
                                alert(serverResponse);
                                window.location.href = 'searchReport.php?lead_id=<?= $lead_id ?>';
                            }
                            if (uri.indexOf("status") !== -1) {
                                alert(serverResponse);
                            }
                            if (uri.indexOf("rejectreason") !== -1) {
                                alert(serverResponse);
                            }
                        }
                    }
                };
                xmlhttp.open("GET", uri);
                xmlhttp.send();
            }

            $(document).ready(function () {

                //When you click on a link with class of poplight and the href starts with a #
                $('input.poplight[href^=#]').click(function () {
                    var popID = $(this).attr('rel'); //Get Popup Name
                    var popURL = $(this).attr('href'); //Get Popup href to define size

                    //Pull Query & Variables from href URL
                    var query = popURL.split('?');
                    var dim = query[1].split('&');
                    var popWidth = dim[0].split('=')[1]; //Gets the first query string value

                    //Fade in the Popup and add close button
                    $('#' + popID).fadeIn().css({'width': Number(popWidth)}).prepend('<a href="#" style="float:right" class="close">Close [X]</a>');

                    //Define margin for center alignment (vertical   horizontal) - we add 80px to the height/width to accomodate for the padding  and border width defined in the css
                    var popMargTop = ($('#' + popID).height() + 80) / 2;
                    var popMargLeft = ($('#' + popID).width() + 80) / 2;

                    //Apply Margin to Popup
                    $('#' + popID).css({
                        'margin-top': -popMargTop,
                        'margin-left': -popMargLeft
                    });

                    if (popID === "appointment_popup") {
                        callHelper('searchReportHelper.php?action=generateAppointment&lead_id=' + <?= $lead_id ?> + '&property_id=' + document.sendAppointmentForm.property_id.value);
                        document.getElementById('message').focus();
                    }

                    if (popID === "update_popup") {
                        var property_id = document.sendUpdateForm.property_id.value;
                        if (property_id !== null && property_id !== "") {
                            callHelper('searchReportHelper.php?action=generateUpdate&lead_id=' + <?= $lead_id ?> + '&property_id=' + property_id);
                        } else {
                            callHelper('searchReportHelper.php?action=generateUpdateAll&lead_id=' + <?= $lead_id ?>);
                        }
                        document.getElementById('update_message').focus();
                    }

                    //Fade in Background
                    $('body').append('<div id="fade"></div>'); //Add the fade layer to bottom of the body tag.
                    $('#fade').css({'filter': 'alpha(opacity=80)'}).fadeIn(); //Fade in the fade layer - .css({'filter' : 'alpha(opacity=80)'}) is used to fix the IE Bug on fading transparencies

                    return false;
                });

                //Close Popups and Fade Layer
                $('a.close, #fade').live('click', function () { //When clicking on the close or fade layer...
                    tinyMCE.execCommand('mceRemoveControl', true, 'message');
                    tinyMCE.execCommand('mceRemoveControl', true, 'update_message');
                    $('#fade , .popup_block').fadeOut(function () {
                        $('#fade, a.close').remove();  //fade them both out
                    });
                    return false;
                });

            });

            tinyMCE.init({
                mode: "exact",
                elements: "elm1",
                plugins: "spellchecker",
                theme: "advanced",
                theme_advanced_buttons1: "bold,italic,underline,strikethrough,separator,justifyleft,justifycenter,justifyright,justifyfull,bullist,numlist,undo,redo,link,unlink,spellchecker",
                theme_advanced_buttons2: "",
                theme_advanced_buttons3: "",
                theme_advanced_buttons4: "",
                theme_advanced_toolbar_location: "top",
                theme_advanced_toolbar_align: "left"
            });

            //]]>
        </script>
    </head>
    <body onload="load();">
        <div id="header"><?php require "header.inc.php"; ?></div>
        <div id="menu"><?php require "menu.inc.php"; ?></div>
        <div id="content">
            <!-- Begin Content-->

            <div align="center">
                <br /><br />
                <table class="input" width="100%">
                    <tr>
                        <th valign="bottom" align="left">
                            <a href="editLead.php?lead_id=<?= $lead_id ?>">Client Information</a>&nbsp;|&nbsp;
                            <a href="searchReport.php?lead_id=<?= $lead_id ?>">Search Report</a>
<!--                            <a href="invoiceDetails.php?lead_id=<?= $lead_id ?>">Invoice Details</a>-->
                        </th>
                    </tr>
                    <tr>
                        <th style="padding:10px;" align="left">
                            <a href="javascript:void(0)" onClick="document.getElementById('maprow_1').style.display = 'table-row';">Show Map</a> |
                            <a href="javascript:void(0)" onClick="document.getElementById('maprow_1').style.display = 'none';">Hide Map</a>
                        </th>
                    </tr>
                    <tr id="maprow_1" style="border-left:1px solid #cccccc;border-right:1px solid #cccccc;border-bottom:1px solid #cccccc;">
                        <td style="padding:0px;" align="center">
                            <p />
                            Address: <input type="text" id="addressInput" size="45" onkeypress="searchKeyPress(event);">&nbsp;
                            Miles:
                            <select id="radiusSelect">
                                <option value=".5">.5</option>
                                <option value="1">1</option>
                                <option value="1.5">1.5</option>
                                <option value="2">2</option>
                                <option value="2.5">2.5</option>
                                <option value="3">3</option>
                                <option value="5">5</option>
                                <option value="10" selected>10</option>
                                <option value="15">15</option>
                                <option value="20">20</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                                <option value="250">250</option>
                            </select>&nbsp;
							County: <input type="text" id="county" size="30" /><br />
							<select id="property_type">
								<option value="Single Family">Single Family</option>
								<option value="Townhome/Condo">Townhome/Condo</option>
								<option value="Duplex">Duplex</option>
								<option value="Multi Family">Multi Family</option>
								<option value="Land">Land</option>
								<option value="Agent Lead">Agent Lead</option>
								<option value="Listing">Listing</option>
								<option value="TAT Agent">TAT Agent</option>
								<option value="SHS Agent">SHS Agent</option>
								<option value="FLV">FLV</option>
							</select>
							<input type="checkbox" name="property_type_details" value="Rental" />Rental
							<input type="checkbox" name="property_type_details" value="Flip" />Flip
							<input type="checkbox" name="property_type_details" value="Wholesale" />Wholesale
							<input type="checkbox" name="property_type_details" value="Owner Occupied" />Owner Occupied
                            <input class="button" type="button" id="btnSearch" onclick="doSearch();" value="Search Locations"/>
                            <p />
                            <table style="border-collapse:true;margin:0 auto;">
                                <tr>
                                    <td valign="top" style="border: 1px solid black;">
                                        <div id="map" style="overflow:hidden; width:650px; height:500px"></div>
                                    </td>
                                    <td align="left" valign="top" style="border: 1px solid black; font-size:12px;">
                                        <form name="selectForm" method="post" action="searchReportHelper.php?action=add&lead_id=<?= $lead_id ?>">
                                            <div id="sidebar" style="overflow:auto; width:400px; height:500px;"></div>
                                        </form>
                                    </td>
                                </tr>
                            </table>
                            <br />
                        </td>
                    </tr>
                </table>

                <p />
                <form name="searchReportForm" method="post" action="searchReportHelper.php?lead_id=<?= $lead_id ?>">
                    <div align="center">
                        <input class="button poplight" type="button" href="#?w=300" rel="intro_popup" value="Send Intro Mail" />&nbsp;
                        <input class="button" type="button" name="sendInquiry" value="Send Lead" onClick="callHelper('searchReportHelper.php?action=sendLead&lead_id=<?= $lead_id ?>');" />&nbsp;
                        <input class="button poplight" type="button" style="width:130px;" href="#?w=700" rel="update_popup" value="Send Update" />&nbsp;
                        <input class="button" type="button" name="sendReport" value="Send Search Report" onClick="callHelper('searchReportHelper.php?action=sendSearchReport&lead_id=<?= $lead_id ?>');" />&nbsp;
                        <input class="button" type="button" name="delayReport" value="Delay Search Report" onClick="callHelper('searchReportHelper.php?delay=1&action=sendSearchReport&lead_id=<?= $lead_id ?>');" />&nbsp;
                    </div>

                    <p />
                    <table class="grid">
                        <tr style="font-weight:bold;">
                            <th>Property</th>
                            <th>Inquiry Sent</th>
                            <th>Client Favorite</th>
                            <th>Provider Status</th>
                            <th>Appointment Time</th>
                            <th>Actions</th>
                        </tr>
                        <?php
                        $result2 = $mysqli->query("SELECT * FROM search_report JOIN properties ON search_report.property_id=properties.property_id WHERE lead_id=" . $lead_id . " ORDER BY properties.CENTER_NAME ASC") or die(mysql_error());
                        if ($mysqli->affected_rows == 0) {
                            ?>
                            <tr>
                                <td align="center" colspan="6">No Properties Selected</td>
                            </tr>
                            <?php
                        } else {
                            while ($row = mysqli_fetch_array($result2)) {
                                foreach ($row AS $key => $value) {
                                    $row[$key] = stripslashes($value);
                                }
                                $property_id = $row['PROPERTY_ID'];
                                ?>
                                <tr>
                                    <td align="left" valign="center">
                                        <div style="float:left; padding:5px; width:70px; height:70px;">
                                            <?php
                                            $primary = $row['PRIMARY_PHOTO'];
                                            if ($row['PHOTO_' . $primary] != null) {
                                                ?>
                                                <img src="getimage.php?id=<?= $property_id ?>&image=photo_<?= $row['PRIMARY_PHOTO'] ?>" style="height:70px;width:70px;" />
                                            <?php } else { ?>
                                                <img src="images/nopic.png" style="height:60px;width:60px;" />
                                            <?php } ?>
                                        </div>
                                        <div style="float:left">
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
                                            if ($row['CONTACT_NAME'] != '') {
                                                $address .= $row['CONTACT_NAME'] . ' - ' . $row['OFFICE_PHONE'] . '<br />';
                                            } else {
                                                $address .= $row['OFFICE_PHONE'] . '<br />';
                                            }
                                            if ($row['CONTACT_EMAIL'] != '')
                                                $address .= $row['CONTACT_EMAIL'] . '<br />';
                                            ?>
                                            <?= $address ?>
                                        </div>
                                    </td>
                                    <td valign="center" align="center">
                                        <?php if ($row['INQUIRYSENT'] == true) { ?><img src="images/email.png" title="<?= date("m/d/Y h:i A T", strtotime($row['INQUIRY_TIMESTAMP'])) ?>" /><?php } ?>
                                    </td>
                                    <td valign="center" align="center">
                                        <select name="favorite_<?= $row['PROPERTY_ID'] ?>" id="favorite_<?= $row['PROPERTY_ID'] ?>"
                                                onChange="callHelper('searchReportHelper.php?action=favorite&property_id=<?= $property_id ?>&lead_id=<?= $lead_id ?>&status=' + this.value);">
                                            <option value="null" <?php if ($row['FAVORITE'] == 0) { ?>selected="selected"<?php } ?>>No</option>
                                            <option value="favorite" <?php if ($row['FAVORITE'] == 1) { ?>selected="selected"<?php } ?>>Yes</option>
                                        </select>
                                    </td>
                                    <td valign="center" align="center">
                                        <select name="status_<?= $row['PROPERTY_ID'] ?>" id="status_<?= $row['PROPERTY_ID'] ?>"
                                                title="<?php if ($row['REJECTED'] == 1 && $row['REJECTED_REASON'] == "")
                                    echo "No Reason Specified";
                                else
                                    echo $row['REJECTED_REASON'];
                                ?>"
                                                onChange="callHelper('searchReportHelper.php?action=status&property_id=<?= $property_id ?>&lead_id=<?= $lead_id ?>&status=' + this.value);">
                                            <option value="null" <?php if ($row['REJECTED'] == 0 && $row['ACCEPTED'] == 0) { ?>selected="selected"<?php } ?>></option>
                                            <option value="accepted" <?php if ($row['REJECTED'] == 0 && $row['ACCEPTED'] == 1) { ?>selected="selected"<?php } ?>>Accepted</option>
                                            <option value="rejected" <?php if ($row['REJECTED'] == 1 && $row['ACCEPTED'] == 0) { ?>selected="selected"<?php } ?>>Rejected</option>
                                        </select>&nbsp;
                                        <select name="rejectReason" id="rejectReason" onChange="callHelper('searchReportHelper.php?action=rejectreason&property_id=<?= $property_id ?>&lead_id=<?= $lead_id ?>&reason=' + this.value);">
                                            <option value="" <?php if ($row['REJECTED_REASON'] == "") { ?>selected="selected"<?php } ?>></option>
                                            <option value="Received the lead from my own site" <?php if ($row['REJECTED_REASON'] == "Received the lead from my own site") { ?>selected="selected"<?php } ?>>Received the lead from my own site</option>
                                            <option value="Received the lead from Agent Broker" <?php if ($row['REJECTED_REASON'] == "Received the lead from Agent Broker") { ?>selected="selected"<?php } ?>>Received the lead from Agent Broker</option>
                                            <option value="Received the lead from a Web Broker" <?php if ($row['REJECTED_REASON'] == "Received the lead from a Web Broker") { ?>selected="selected"<?php } ?>>Received the lead from a Web Broker</option>
                                            <option value="Received the lead through walk-in" <?php if ($row['REJECTED_REASON'] == "Received the lead through walk-in") { ?>selected="selected"<?php } ?>>Received the lead through walk-in</option>
                                            <option value="Client has already toured" <?php if ($row['REJECTED_REASON'] == "Client has already toured") { ?>selected="selected"<?php } ?>>Client has already toured</option>
                                            <option value="Client called from building signage" <?php if ($row['REJECTED_REASON'] == "Client called from building signage") { ?>selected="selected"<?php } ?>>Client called from building signage</option>
                                            <option value="Client was a previous tenant that defaulted" <?php if ($row['REJECTED_REASON'] == "Client was a previous tenant that defaulted") { ?>selected="selected"<?php } ?>>Client was a previous tenant that defaulted</option>
                                            <option value="Nothing in the area specified" <?php if ($row['REJECTED_REASON'] == "Nothing in the area specified") { ?>selected="selected"<?php } ?>>Nothing in the area specified</option>
                                            <option value="">---</option>
                                            <option value="100% Full" <?php if ($row['REJECTED_REASON'] == "100% Full") { ?>selected="selected"<?php } ?>>100% Full</option>
                                            <option value="Only taking virtual clients" <?php if ($row['REJECTED_REASON'] == "Only taking virtual clients") { ?>selected="selected"<?php } ?>>Only taking virtual clients</option>
                                            <option value="Inquiry too small" <?php if ($row['REJECTED_REASON'] == "Inquiry too small") { ?>selected="selected"<?php } ?>>Inquiry too small</option>
                                            <option value="Inquiry too large" <?php if ($row['REJECTED_REASON'] == "Inquiry too large") { ?>selected="selected"<?php } ?>>Inquiry too large</option>
                                            <option value="No match" <?php if ($row['REJECTED_REASON'] == "No match") { ?>selected="selected"<?php } ?>>No match</option>
                                            <option value="Proposed usage not allowed" <?php if ($row['REJECTED_REASON'] == "Proposed usage not allowed") { ?>selected="selected"<?php } ?>>Proposed usage not allowed</option>
                                        </select>
                                    </td>
                                    <td align="center" valign="center">
                                        <input name="tour_date_<?= $property_id ?>" id="tour_date_<?= $property_id ?>" size="10" value="<?php if ($row['TOUR_DATE'] == "0000-00-00")
                                    echo "";
                                else
                                    echo $row['TOUR_DATE'];
                                ?>" onChange="callHelper('searchReportHelper.php?action=tour_date&property_id=<?= $property_id ?>&lead_id=<?= $lead_id ?>&value=' + this.value);" />
                                        <script type="text/javascript">
                                            var t_cal_<?= $property_id ?> = new tcal({
                                                'controlname': 'tour_date_' + <?= $property_id ?>,
                                                'time_comp': false
                                            });
                                        </script>&nbsp;
                                        <select name="tour_time" id="tour_time" onChange="callHelper('searchReportHelper.php?action=tour_time&property_id=<?= $property_id ?>&lead_id=<?= $lead_id ?>&value=' + this.value);">
                                            <option value=""></option>
                                            <option value="06:00" <?php if ($row['TOUR_TIME'] == '06:00:00') echo 'selected=\'selected\'' ?>>6:00 AM</option>
                                            <option value="06:15" <?php if ($row['TOUR_TIME'] == '06:15:00') echo 'selected=\'selected\'' ?>>6:15 AM</option>
                                            <option value="06:30" <?php if ($row['TOUR_TIME'] == '06:30:00') echo 'selected=\'selected\'' ?>>6:30 AM</option>
                                            <option value="06:45" <?php if ($row['TOUR_TIME'] == '06:45:00') echo 'selected=\'selected\'' ?>>6:45 AM</option>

                                            <option value="07:00" <?php if ($row['TOUR_TIME'] == '07:00:00') echo 'selected=\'selected\'' ?>>7:00 AM</option>
                                            <option value="07:15" <?php if ($row['TOUR_TIME'] == '07:15:00') echo 'selected=\'selected\'' ?>>7:15 AM</option>
                                            <option value="07:30" <?php if ($row['TOUR_TIME'] == '07:30:00') echo 'selected=\'selected\'' ?>>7:30 AM</option>
                                            <option value="07:45" <?php if ($row['TOUR_TIME'] == '07:45:00') echo 'selected=\'selected\'' ?>>7:45 AM</option>

                                            <option value="08:00" <?php if ($row['TOUR_TIME'] == '08:00:00') echo 'selected=\'selected\'' ?>>8:00 AM</option>
                                            <option value="08:15" <?php if ($row['TOUR_TIME'] == '08:15:00') echo 'selected=\'selected\'' ?>>8:15 AM</option>
                                            <option value="08:30" <?php if ($row['TOUR_TIME'] == '08:30:00') echo 'selected=\'selected\'' ?>>8:30 AM</option>
                                            <option value="08:45" <?php if ($row['TOUR_TIME'] == '08:45:00') echo 'selected=\'selected\'' ?>>8:45 AM</option>

                                            <option value="09:00" <?php if ($row['TOUR_TIME'] == '09:00:00') echo 'selected=\'selected\'' ?>>9:00 AM</option>
                                            <option value="09:15" <?php if ($row['TOUR_TIME'] == '09:15:00') echo 'selected=\'selected\'' ?>>9:15 AM</option>
                                            <option value="09:30" <?php if ($row['TOUR_TIME'] == '09:30:00') echo 'selected=\'selected\'' ?>>9:30 AM</option>
                                            <option value="09:45" <?php if ($row['TOUR_TIME'] == '09:45:00') echo 'selected=\'selected\'' ?>>9:45 AM</option>

                                            <option value="10:00" <?php if ($row['TOUR_TIME'] == '10:00:00') echo 'selected=\'selected\'' ?>>10:00 AM</option>
                                            <option value="10:15" <?php if ($row['TOUR_TIME'] == '10:15:00') echo 'selected=\'selected\'' ?>>10:15 AM</option>
                                            <option value="10:30" <?php if ($row['TOUR_TIME'] == '10:30:00') echo 'selected=\'selected\'' ?>>10:30 AM</option>
                                            <option value="10:45" <?php if ($row['TOUR_TIME'] == '10:45:00') echo 'selected=\'selected\'' ?>>10:45 AM</option>

                                            <option value="11:00" <?php if ($row['TOUR_TIME'] == '11:00:00') echo 'selected=\'selected\'' ?>>11:00 AM</option>
                                            <option value="11:15" <?php if ($row['TOUR_TIME'] == '11:15:00') echo 'selected=\'selected\'' ?>>11:15 AM</option>
                                            <option value="11:30" <?php if ($row['TOUR_TIME'] == '11:30:00') echo 'selected=\'selected\'' ?>>11:30 AM</option>
                                            <option value="11:45" <?php if ($row['TOUR_TIME'] == '11:45:00') echo 'selected=\'selected\'' ?>>11:45 AM</option>

                                            <option value="12:00" <?php if ($row['TOUR_TIME'] == '12:00:00') echo 'selected=\'selected\'' ?>>12:00 PM</option>
                                            <option value="12:15" <?php if ($row['TOUR_TIME'] == '12:15:00') echo 'selected=\'selected\'' ?>>12:15 PM</option>
                                            <option value="12:30" <?php if ($row['TOUR_TIME'] == '12:30:00') echo 'selected=\'selected\'' ?>>12:30 PM</option>
                                            <option value="12:45" <?php if ($row['TOUR_TIME'] == '12:45:00') echo 'selected=\'selected\'' ?>>12:45 PM</option>

                                            <option value="13:00" <?php if ($row['TOUR_TIME'] == '13:00:00') echo 'selected=\'selected\'' ?>>1:00 PM</option>
                                            <option value="13:15" <?php if ($row['TOUR_TIME'] == '13:15:00') echo 'selected=\'selected\'' ?>>1:15 PM</option>
                                            <option value="13:30" <?php if ($row['TOUR_TIME'] == '13:30:00') echo 'selected=\'selected\'' ?>>1:30 PM</option>
                                            <option value="13:45" <?php if ($row['TOUR_TIME'] == '13:45:00') echo 'selected=\'selected\'' ?>>1:45 PM</option>

                                            <option value="14:00" <?php if ($row['TOUR_TIME'] == '14:00:00') echo 'selected=\'selected\'' ?>>2:00 PM</option>
                                            <option value="14:15" <?php if ($row['TOUR_TIME'] == '14:15:00') echo 'selected=\'selected\'' ?>>2:15 PM</option>
                                            <option value="14:30" <?php if ($row['TOUR_TIME'] == '14:30:00') echo 'selected=\'selected\'' ?>>2:30 PM</option>
                                            <option value="14:45" <?php if ($row['TOUR_TIME'] == '14:45:00') echo 'selected=\'selected\'' ?>>2:45 PM</option>

                                            <option value="15:00" <?php if ($row['TOUR_TIME'] == '15:00:00') echo 'selected=\'selected\'' ?>>3:00 PM</option>
                                            <option value="15:15" <?php if ($row['TOUR_TIME'] == '15:15:00') echo 'selected=\'selected\'' ?>>3:15 PM</option>
                                            <option value="15:30" <?php if ($row['TOUR_TIME'] == '15:30:00') echo 'selected=\'selected\'' ?>>3:30 PM</option>
                                            <option value="15:45" <?php if ($row['TOUR_TIME'] == '15:45:00') echo 'selected=\'selected\'' ?>>3:45 PM</option>

                                            <option value="16:00" <?php if ($row['TOUR_TIME'] == '16:00:00') echo 'selected=\'selected\'' ?>>4:00 PM</option>
                                            <option value="16:15" <?php if ($row['TOUR_TIME'] == '16:15:00') echo 'selected=\'selected\'' ?>>4:15 PM</option>
                                            <option value="16:30" <?php if ($row['TOUR_TIME'] == '16:30:00') echo 'selected=\'selected\'' ?>>4:30 PM</option>
                                            <option value="16:45" <?php if ($row['TOUR_TIME'] == '16:45:00') echo 'selected=\'selected\'' ?>>4:45 PM</option>

                                            <option value="17:00" <?php if ($row['TOUR_TIME'] == '17:00:00') echo 'selected=\'selected\'' ?>>5:00 PM</option>
                                            <option value="17:15" <?php if ($row['TOUR_TIME'] == '17:15:00') echo 'selected=\'selected\'' ?>>5:15 PM</option>
                                            <option value="17:30" <?php if ($row['TOUR_TIME'] == '17:30:00') echo 'selected=\'selected\'' ?>>5:30 PM</option>
                                            <option value="17:45" <?php if ($row['TOUR_TIME'] == '17:45:00') echo 'selected=\'selected\'' ?>>5:45 PM</option>

                                            <option value="18:00" <?php if ($row['TOUR_TIME'] == '18:00:00') echo 'selected=\'selected\'' ?>>6:00 PM</option>
                                            <option value="18:15" <?php if ($row['TOUR_TIME'] == '18:15:00') echo 'selected=\'selected\'' ?>>6:15 PM</option>
                                            <option value="18:30" <?php if ($row['TOUR_TIME'] == '18:30:00') echo 'selected=\'selected\'' ?>>6:30 PM</option>
                                            <option value="18:45" <?php if ($row['TOUR_TIME'] == '18:45:00') echo 'selected=\'selected\'' ?>>6:45 PM</option>

                                            <option value="19:00" <?php if ($row['TOUR_TIME'] == '19:00:00') echo 'selected=\'selected\'' ?>>7:00 PM</option>
                                            <option value="19:15" <?php if ($row['TOUR_TIME'] == '19:15:00') echo 'selected=\'selected\'' ?>>7:15 PM</option>
                                            <option value="19:30" <?php if ($row['TOUR_TIME'] == '19:30:00') echo 'selected=\'selected\'' ?>>7:30 PM</option>
                                            <option value="19:45" <?php if ($row['TOUR_TIME'] == '19:45:00') echo 'selected=\'selected\'' ?>>7:45 PM</option>

                                            <option value="20:00" <?php if ($row['TOUR_TIME'] == '20:00:00') echo 'selected=\'selected\'' ?>>8:00 PM</option>
                                            <option value="20:15" <?php if ($row['TOUR_TIME'] == '20:15:00') echo 'selected=\'selected\'' ?>>8:15 PM</option>
                                            <option value="20:30" <?php if ($row['TOUR_TIME'] == '20:30:00') echo 'selected=\'selected\'' ?>>8:30 PM</option>
                                            <option value="20:45" <?php if ($row['TOUR_TIME'] == '20:45:00') echo 'selected=\'selected\'' ?>>8:45 PM</option>

                                            <option value="21:00" <?php if ($row['TOUR_TIME'] == '21:00:00') echo 'selected=\'selected\'' ?>>9:00 PM</option>

                                        </select>
                                    </td>
                                    <td align="center" valign="center">
                                        <table border="0" cellpadding="2">
                                            <tr style="border:0px;">
                                                <td style="border:0px;"><input class="button poplight" type="button" style="width:140px;" href="#?w=700" rel="appointment_popup" onClick="document.sendAppointmentForm.property_id.value =<?= $property_id ?>" value="Send Appointment" /></td>
                                                <td style="border:0px;"><input class="button" type="button" style="width:140px;" onClick="mapIt(<?= $property_id ?>, '<?= $row['CENTER_NAME'] ?>', '<?= $row['CONTACT_NAME'] ?>', '<?= $row['CONTACT_EMAIL'] ?>', '<?= $row['OFFICE_PHONE'] ?>', '<?= $row['ADDRESS_1'] ?>', '<?= $row['ADDRESS_2'] ?>', '<?= $row['CITY'] ?>', '<?= $row['STATE'] ?>', '<?= $row['ZIP'] ?>', '<?= "photo_" . $row['PRIMARY_PHOTO'] ?>', <?= $row['PROP_LAT'] ?>, <?= $row['PROP_LONG'] ?>);" value="Show On Map" /></td>
                                            </tr>
                                            <tr style="border:0px;">
                                                <td style="border:0px;"><input class="button" type="button" style="width:140px;" onClick="callHelper('searchReportHelper.php?action=sendLead&lead_id=<?= $lead_id ?>&property_id=<?= $property_id ?>');" value="Resend Lead" /></td>
                                                <td style="border:0px;"><input class="button" type="button" style="width:140px;" onClick="if (confirm_remove()) { window.location.href = 'searchReportHelper.php?action=remove&property_id=<?= $property_id ?>&lead_id=<?= $lead_id ?>'; } else { return false; }" value="Remove" /></td>
                                            </tr>
                                            <tr style="border:0px;">
                                                <td style="border:0px;"><input class="button poplight" type="button" style="width:140px;" href="#?w=700" rel="update_popup" onClick="document.sendUpdateForm.property_id.value =<?= $property_id ?>" value="Resend Update" /></td>
                                                <td style="border:0px;"></td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
        <?php
    }
}
?>
                    </table>
                </form>

            </div>
            <br />

            <div id="appointment_popup" class="popup_block">
                <p />
                Appointment Confirmation Email:
                <form name="sendAppointmentForm" method="post" action="searchReportHelper.php?action=sendAppointment&lead_id=<?= $lead_id ?>">
                    <input type="hidden" name="property_id" id="property_id" value="" />
                    <textarea id="message" name="message" rows="27" cols="84" ></textarea>
                    <p />
                    <input class="button" type="submit" id="sendAppointmentSubmit" name="sendAppointmentSubmit" value="Send Email" />
                </form>
            </div>

            <div id="update_popup" class="popup_block">
                <p />
                Update Email:
                <form name="sendUpdateForm" method="post" action="searchReportHelper.php?action=sendUpdate&lead_id=<?= $lead_id ?>">
                    <input type="hidden" name="property_id" id="property_id" value="" />
                    <textarea id="update_message" name="update_message" rows="27" cols="98" ></textarea>
                    <p />
                    <input class="button" type="submit" id="sendUpdateSubmit" name="sendUpdateSubmit" value="Send Email" />
                </form>
            </div>

            <div id="intro_popup" class="popup_block">
                <p />
                Did you contact the client?
                <form name="sendIntroForm">
                    <select id="made_contact" name="made_contact">
                        <option value="yes">Yes</option>
                        <option value="no">No</option>
                    </select>
                    <p />
                    <input class="button" type="button" id="sendIntroSubmit" name="sendIntroSubmit" value="Send Email"
                           onClick="callHelper('searchReportHelper.php?action=sendIntro&lead_id=<?= $lead_id ?>&made_contact=' + document.sendIntroForm.made_contact.value);
                                                        $('.popup_block').hide();
                                                        $('#fade, a.close').remove();" />
                </form>
            </div>

            <!-- End Content -->
        </div>
    </body>
</html>