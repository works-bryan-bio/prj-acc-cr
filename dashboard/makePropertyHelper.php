<?php
require_once("include/checklogin.php");
// connect to the database
require_once("include/db_connect.php");
require_once("include/simpleimage.php");
require_once("include/convertAddress.php");

/*require_once("include/checklogin.php");
require_once("include/session.php");
require_once("include/db_connect.php");

require_once("include/simpleimage.php");
require_once("include/convertAddress.php");*/

class MakePropertyHelper {

    function MakePropertyHelper() {
        global $session;
        /* Make sure administrator is accessing page */
        if (!$session->isAdmin() && !$session->isMaster()) {
            header("Location: index.php");
            return;
        }
        /* Admin submitted update user level form */
        if ($_POST['addMakeProperty']){ 
            $this->addMakeProperty();
        }

        /* Should not get here, redirect to home page */ else {
            header("Location: index.php");
        }
    }

    function addMakeProperty() {
        global $session, $database, $form;

        //$data = $_POST;
        if (isset($_POST["submit"]))
        {
            // get form data, making sure it is valid
            $center_name = $mysqli->real_escape_string($_POST["center_name"]);

            // check to make sure that all required fields are available
            if ($center_name != '') {

                $property_type_details = "";
                if (isset($_POST['property_type_details'])) {
                    $property_type_details = implode(" ,", $_POST["property_type_details"]);
                }

                // get the photos from FILES array
                try {
                    $photo1_data = null;
                    if (isset($_FILES["photo_1"])) {
                        $photo1 = $_FILES["photo_1"];
                        if ($photo1['type']=="image/jpeg" || $photo1['type']=="image/pjpeg" ||
                                $photo1['type']=="image/gif" || $photo1['type']=="image/png") {
                            $tmpName = $photo1['tmp_name'];
                            $image = new SimpleImage();
                            $image->load($tmpName);
                            $image->resizeToWidth(320);
                            $image->save($tmpName);
                            $photo1_data = file_get_contents($tmpName);
                            $photo1_mime = $photo1['type'];
                        }
                    }
                    $photo2_data = null;
                    if (isset($_FILES["photo_2"])) {
                        $photo2 = $_FILES["photo_2"];
                        if ($photo2['type']=="image/jpeg" || $photo2['type']=="image/pjpeg" ||
                                $photo2['type']=="image/gif" || $photo2['type']=="image/png") {
                            $tmpName = $photo2['tmp_name'];
                            $image = new SimpleImage();
                            $image->load($tmpName);
                            $image->resizeToWidth(320);
                            $image->save($tmpName);
                            $photo2_data = file_get_contents($tmpName);
                            $photo2_mime = $photo2['type'];
                        }
                    }
                    $photo3_data = null;
                    if (isset($_FILES["photo_3"])) {
                        $photo3 = $_FILES["photo_3"];
                        if ($photo3['type']=="image/jpeg" || $photo3['type']=="image/pjpeg" ||
                                $photo3['type']=="image/gif" || $photo3['type']=="image/png") {
                            $tmpName = $photo3['tmp_name'];
                            $image = new SimpleImage();
                            $image->load($tmpName);
                            $image->resizeToWidth(320);
                            $image->save($tmpName);
                            $photo3_data = file_get_contents($tmpName);
                            $photo3_mime = $photo3['type'];
                        }
                    }
                    $photo4_data = null;
                    if (isset($_FILES["photo_4"])) {
                        $photo4 = $_FILES["photo_4"];
                        if ($photo4['type']=="image/jpeg" || $photo4['type']=="image/pjpeg" ||
                                $photo4['type']=="image/gif" || $photo4['type']=="image/png") {
                            $tmpName = $photo4['tmp_name'];
                            $image = new SimpleImage();
                            $image->load($tmpName);
                            $image->resizeToWidth(320);
                            $image->save($tmpName);
                            $photo4_data = file_get_contents($tmpName);
                            $photo4_mime = $photo4['type'];
                        }
                    }
                    $photo5_data = null;
                    if (isset($_FILES["photo_5"])) {
                        $photo5 = $_FILES["photo_5"];
                        if ($photo5['type']=="image/jpeg" || $photo5['type']=="image/pjpeg" ||
                                $photo5['type']=="image/gif" || $photo5['type']=="image/png") {
                            $tmpName = $photo5['tmp_name'];
                            $image = new SimpleImage();
                            $image->load($tmpName);
                            $image->resizeToWidth(320);
                            $image->save($tmpName);
                            $photo5_data = file_get_contents($tmpName);
                            $photo5_mime = $photo5['type'];
                        }
                    }
                    $photo6_data = null;
                    if (isset($_FILES["photo_6"])) {
                        $photo6 = $_FILES["photo_6"];
                        if ($photo6['type']=="image/jpeg" || $photo6['type']=="image/pjpeg" ||
                                $photo6['type']=="image/gif" || $photo6['type']=="image/png") {
                            $tmpName = $photo6['tmp_name'];
                            $image = new SimpleImage();
                            $image->load($tmpName);
                            $image->resizeToWidth(320);
                            $image->save($tmpName);
                            $photo6_data = file_get_contents($tmpName);
                            $photo6_mime = $photo6['type'];
                        }
                    }
                } catch (Exception $e) {
                    die($e->getMessage());
                }

                $lat = null;
                $long = null;
                $address = $_POST['address_1'].','.$_POST['city'].','.$_POST['state'].','.$_POST['zip'];
                if ($address!="") {
                    $geo = convertAddress2Geo($address);
                    $lat = $geo[0];
                    $long = $geo[1];
                }

                // increase the maximum packet size to handle photo uploads
                mysqli_options($mysqli,MYSQLI_READ_DEFAULT_GROUP,"max_allowed_packet=5M");
                // save the data to the database
                $stmt = $mysqli->prepare("INSERT INTO properties (
                                        PROVIDER_ID, CENTER_NAME, LEAD_COUNTIES, TITLE, CONTACT_NAME, CONTACT_EMAIL,
                                        OFFICE_PHONE, CELL_PHONE, OTHER_PHONE, FAX, WEBSITE, ADDRESS_1, ADDRESS_2, CITY, STATE, ZIP, COUNTRY,
                                        YEAR_BUILT, SQUARE_FEET, GARAGE_TYPE, GARAGES, CONVERTED_GARAGE, BEDROOMS, BATHROOMS, STORIES, POOL,
                                        PROPERTY_TYPE, PROPERTY_TYPE_DETAILS, RATING, YOUTUBE_ID, TERM_END_DATE, TERM_TIER, ACCEPTED_TERMS, BUYER_DESCRIPTION,
                                        LENDER_DESCRIPTION, REPRESENTATIVE_DESCRIPTION, BUYER_SALES_PRICE, LENDER_SALES_PRICE, REPRESENTATIVE_SALES_PRICE,
                                        PHOTO_1, PHOTO_2, PHOTO_3, PHOTO_4, PHOTO_5, PHOTO_6,
                                        PHOTO_1_MIME, PHOTO_2_MIME, PHOTO_3_MIME, PHOTO_4_MIME, PHOTO_5_MIME, PHOTO_6_MIME, PRIMARY_PHOTO,
                                        LEASED, NEEDS_WORK, FULLY_RENOVATED, RENTAL_GRADE_FINISH,
                                        PROP_LAT, PROP_LONG, DATE_ADDED)
                                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?,
                                                ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,
                                                ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,
                                                ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,
                                                ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,
                                                ?, ?, ?, ?, ?, ?, ?, ?, ?)
                                        ") or die($mysqli->error);
                $stmt->bind_param("ssssssssssssssssssssssssssssissssssssssbbbbbbssssssiiiiidds",
                    $mysqli->real_escape_string($_POST["provider_id"]),
                    $mysqli->real_escape_string($_POST["center_name"]),
                    $mysqli->real_escape_string($_POST["lead_counties"]),
                    $mysqli->real_escape_string($_POST["title"]),
                    $mysqli->real_escape_string($_POST["contact_name"]),
                    $mysqli->real_escape_string($_POST["contact_email"]),
                    $mysqli->real_escape_string($_POST["office_phone"]),
                    $mysqli->real_escape_string($_POST["cell_phone"]),
                    $mysqli->real_escape_string($_POST["other_phone"]),
                    $mysqli->real_escape_string($_POST["fax"]),
                    $mysqli->real_escape_string($_POST["website"]),
                    $mysqli->real_escape_string($_POST["address_1"]),
                    $mysqli->real_escape_string($_POST["address_2"]),
                    $mysqli->real_escape_string($_POST["city"]),
                    $mysqli->real_escape_string($_POST["state"]),
                    $mysqli->real_escape_string($_POST["zip"]),
                    $mysqli->real_escape_string($_POST["country"]),
                    $mysqli->real_escape_string($_POST["year_built"]),
                    $mysqli->real_escape_string($_POST["square_feet"]),
                    $mysqli->real_escape_string($_POST["garage_type"]),
                    $mysqli->real_escape_string($_POST["garages"]),
                    $mysqli->real_escape_string($_POST["converted_garage"]),
                    $mysqli->real_escape_string($_POST["bedrooms"]),
                    $mysqli->real_escape_string($_POST["bathrooms"]),
                    $mysqli->real_escape_string($_POST["stories"]),
                    $mysqli->real_escape_string($_POST["pool"]),
                    $mysqli->real_escape_string($_POST["property_type"]),
                    $property_type_details,
                    $mysqli->real_escape_string($_POST["rating"]),
                    $mysqli->real_escape_string($_POST["youtube_id"]),
                    $mysqli->real_escape_string($_POST["term_end_date"]),
                    $mysqli->real_escape_string($_POST["term_tier"]),
                    $mysqli->real_escape_string($_POST["accepted_terms"]),
                    stripslashes(str_replace('\r\n', ' ', $mysqli->real_escape_string($_POST["buyer_description"]))),
                    stripslashes(str_replace('\r\n', ' ', $mysqli->real_escape_string($_POST["lender_description"]))),
                    stripslashes(str_replace('\r\n', ' ', $mysqli->real_escape_string($_POST["representative_description"]))),
                    $mysqli->real_escape_string($_POST["buyer_sales_price"]),
                    $mysqli->real_escape_string($_POST["lender_sales_price"]),
                    $mysqli->real_escape_string($_POST["representative_sales_price"]),
                    $mysqli->real_escape_string(null),
                    $mysqli->real_escape_string(null),
                    $mysqli->real_escape_string(null),
                    $mysqli->real_escape_string(null),
                    $mysqli->real_escape_string(null),
                    $mysqli->real_escape_string(null),
                    $mysqli->real_escape_string($photo1_mime),
                    $mysqli->real_escape_string($photo2_mime),
                    $mysqli->real_escape_string($photo3_mime),
                    $mysqli->real_escape_string($photo4_mime),
                    $mysqli->real_escape_string($photo5_mime),
                    $mysqli->real_escape_string($photo6_mime),
                    $mysqli->real_escape_string($_POST["primary_photo"]),
                    $mysqli->real_escape_string($_POST["leased"]),
                    $mysqli->real_escape_string($_POST["needs_work"]),
                    $mysqli->real_escape_string($_POST["fully_renovated"]),
                    $mysqli->real_escape_string($_POST["rental_grade_finish"]),
                    $lat,
                    $long,
                    $mysqli->real_escape_string($_POST["date_added"])
                ) or die($mysqli->error);

                /* Send large image data */
                $stmt->send_long_data(39, $photo1_data);
                $stmt->send_long_data(40, $photo2_data);
                $stmt->send_long_data(41, $photo3_data);
                $stmt->send_long_data(42, $photo4_data);
                $stmt->send_long_data(43, $photo5_data);
                $stmt->send_long_data(44, $photo6_data);

                /* Execute the statement */
                $stmt->execute() or die("Error: Could not execute statement");

                /* close statement */
                $stmt->close() or die("Error: Could not close statement");

                // once saved, redirect back to the view page
                header("Location: listProperties.php");
            }
            else    {
                echo "Error: Center name is required";
            }
        } else {
                $form->setError("", "Cannot save record<br>");
                //header("Location: " . $session->referrer);                
        }
    }
   

    function updateDripCampaign() {
        global $session, $database, $form;
    }

    function deleteLead() {
        global $session, $database, $form;

    }

}

/* Initialize process */
$MakePropertyHelper = new MakePropertyHelper;
?>
