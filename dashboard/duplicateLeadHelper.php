<?php
require_once("include/checklogin.php");
require_once("include/session.php");
require_once("include/db_connect.php");

class DuplicateLeadHelper {

    function DuplicateLeadHelper() {
        global $session;
        /* Make sure administrator is accessing page */
        if (!$session->isAdmin() && !$session->isMaster()) {
            header("Location: index.php");
            return;
        }
        /* Admin submitted update user level form */
        if (isset($_POST['addlead'])) {
            $this->addDripCampaign();
        }
        elseif( isset($_POST['updatelead']) ) {
            $this->updateDripCampaign();
        }
        else if (isset($_POST['dellead'])) { /* Admin submitted add user form */ 
            $this->deleteLead();
        }
        else if( isset($_GET['del']) ) {
            if($_GET['del'] == 'dellead') {
                $this->deleteLead();
                exit;
            }
        }

        /* Should not get here, redirect to home page */ else {
            header("Location: index.php");
        }
    }

    function addDripCampaign() {
        global $session, $database, $form;

        $data = $_POST;
        if (isset($data["submit"]) || isset($data['dsubmit'])) {

            $financing_available = "";
            if (isset($data["financing_available"])) {
                $financing_available = implode(" ,", $data["financing_available"]);
            }

            // get form data, making sure it is valid
            $first_name = stripslashes(str_replace('\r\n', ' ', $_POST["first_name"]));
            $last_name  = stripslashes(str_replace('\r\n', ' ', $_POST["last_name"]));

            if ($first_name != '' || $last_name !='') {

                if ($data['add_notes']!="") {
                } else {
                    $notes = $data['notes'];
                }

                $q = "INSERT INTO leads (
                                    USERNAME, COMPANY_NAME, TITLE, FIRST_NAME, LAST_NAME, CLIENT_EMAIL,
                                    POSITION, EXTRA_TITLE, EXTRA_FIRST_NAME, EXTRA_LAST_NAME, EXTRA_CLIENT_EMAIL,
                                    OFFICE_PHONE, CELL_PHONE, OTHER_PHONE, FAX, WEBSITE, ADDRESS_1, ADDRESS_2,
                                    CITY, STATE, ZIP, OWNERS_ON_TITLE, SECOND_CHANCE, FUNDS_FOR_PURCHASE,
                                    FINANCING_AVAILABLE, NEED_LENDER, CLOSER, PRIORITY, TITLE_COMPANY, 
                                    STATUS, PROPERTY_TYPE, YEAR_BUILT, SQUARE_FEET, GARAGE_TYPE, GARAGES, 
                                    GARAGE_CONVERTED, BEDROOMS, BATHROOMS, STORIES, POOL, RENTED, ARV, ASKING_PRICE, 
                                    CURRENT_MORTGAGE, CURRENT_PAYMENTS, DEAD_REASON, BACKSIDE_CONTRACT,
                                    CLOSED_DATE, EXIT_STRATEGY, FOLLOW_UP_DATE, FOLLOW_UP_TIME, PROVIDER_INFO, 
                                    NOTES, LEAD_TYPE, PREDICTED_AMT, FORECAST_CHANCE, EARNEST_RECEIPT, EXECUTED_DATE, 
                                    END_OF_OPTION, SEARCH_CITY, SEARCH_STATE, AREA_OF_INTEREST, AFFILIATE_ID, 
                                    DATE_ADDED, AS_IS_PRICE, OWNER_OCCUPIED, HOW_LONG_OWNED, ROOF_AGE, HVAC_AGE,
                                    POOL_CONDITION, NEED_FOUNDATION_REPAIR, CABINET_TYPE, COUNTER_TYPE, 
                                    FLOORING_TYPE, MASTER_BATH_AGE, HALF_BATH_AGE, UPGRADES, INSURANCE, RENT_AMT,
                                    TERM, MOVE_DATE, DEPOSIT, LISTED, HOW_LONG, LISTING_PRICE, OFFER_PRICE, 
                                    MOVING_REASON, TIME_FRAME_SELL, PRICE_FLEXIBLE, ASKING_PRICE_REASON, 
                                    CASH_QUICK_CLOSE, ANY_BETTER, DOESNT_SELL, HH_REPAIR_COST, WT_REPAIR_COST, 
                                    RH_LIPSTICK, RH_RENT_COMP
                                )VALUES(
                                    '" . stripslashes(str_replace('\r\n', ' ', $data['username'])) . "',
                                    '" . stripslashes(str_replace('\r\n', ' ', $data['company_name'])) . "',
                                    '" . stripslashes(str_replace('\r\n', ' ', $data['title'])) . "',
                                    '" . stripslashes(str_replace('\r\n', ' ', $data['first_name'])) . "',
                                    '" . stripslashes(str_replace('\r\n', ' ', $data['last_name'])) . "',
                                    '" . stripslashes(str_replace('\r\n', ' ', $data['client_email'])) . "',
                                    '" . stripslashes(str_replace('\r\n', ' ', $data['position'])) . "',
                                    '" . stripslashes(str_replace('\r\n', ' ', $data['extra_title'])) . "',
                                    '" . stripslashes(str_replace('\r\n', ' ', $data['extra_first_name'])) . "',
                                    '" . stripslashes(str_replace('\r\n', ' ', $data['extra_last_name'])) . "',
                                    '" . stripslashes(str_replace('\r\n', ' ', $data['extra_client_email'])) . "',
                                    '" . stripslashes(str_replace('\r\n', ' ', $data['office_phone'])) . "',
                                    '" . stripslashes(str_replace('\r\n', ' ', $data['cell_phone'])) . "',
                                    '" . stripslashes(str_replace('\r\n', ' ', $data['other_phone'])) . "',
                                    '" . stripslashes(str_replace('\r\n', ' ', $data['fax'])) . "',
                                    '" . stripslashes(str_replace('\r\n', ' ', $data['website'])) . "',
                                    '" . stripslashes(str_replace('\r\n', ' ', $data['address_1'])) . "',
                                    '" . stripslashes(str_replace('\r\n', ' ', $data['address_2'])) . "',
                                    '" . stripslashes(str_replace('\r\n', ' ', $data['city'])) . "',
                                    '" . stripslashes(str_replace('\r\n', ' ', $data['state'])) . "',
                                    '" . stripslashes(str_replace('\r\n', ' ', $data['zip'])) . "',
                                    '" . stripslashes(str_replace('\r\n', ' ', $data['owners_on_title'])) . "',
                                    '" . stripslashes(str_replace('\r\n', ' ', $data['second_chance'])) . "',
                                    '" . stripslashes(str_replace('\r\n', ' ', $data['funds_for_purchase'])) . "',
                                    '" . stripslashes(str_replace('\r\n', ' ', $financing_available)) . "',
                                    '" . stripslashes(str_replace('\r\n', ' ', $data['need_lender'])) . "',
                                    '" . stripslashes(str_replace('\r\n', ' ', $data['closer'])) . "',
                                    '" . stripslashes(str_replace('\r\n', ' ', $data['priority'])) . "',
                                    '" . stripslashes(str_replace('\r\n', ' ', $data['title_company'])) . "',
                                    '" . stripslashes(str_replace('\r\n', ' ', $data['status'])) . "',
                                    '" . stripslashes(str_replace('\r\n', ' ', $data['property_type'])) . "',
                                    '" . stripslashes(str_replace('\r\n', ' ', $data['year_built'])) . "',
                                    '" . stripslashes(str_replace('\r\n', ' ', $data['square_feet'])) . "',
                                    '" . stripslashes(str_replace('\r\n', ' ', $data['garage_type'])) . "',
                                    '" . stripslashes(str_replace('\r\n', ' ', $data['garages'])) . "',
                                    '" . stripslashes(str_replace('\r\n', ' ', $data['garage_converted'])) . "',
                                    '" . stripslashes(str_replace('\r\n', ' ', $data['bedrooms'])) . "',
                                    '" . stripslashes(str_replace('\r\n', ' ', $data['bathrooms'])) . "',
                                    '" . stripslashes(str_replace('\r\n', ' ', $data['stories'])) . "',
                                    '" . stripslashes(str_replace('\r\n', ' ', $data['pool'])) . "',
                                    '" . stripslashes(str_replace('\r\n', ' ', $data['rented'])) . "',
                                    '" . stripslashes(str_replace('\r\n', ' ', $data['arv'])) . "',
                                    '" . stripslashes(str_replace('\r\n', ' ', $data['asking_price'])) . "',
                                    '" . stripslashes(str_replace('\r\n', ' ', $data['current_mortgage'])) . "',
                                    '" . stripslashes(str_replace('\r\n', ' ', $data['current_payments'])) . "',
                                    '" . stripslashes(str_replace('\r\n', ' ', $data['dead_reason'])) . "',
                                    '" . stripslashes(str_replace('\r\n', ' ', $data['backside_contract'])) . "',
                                    '" . stripslashes(str_replace('\r\n', ' ', $data['closed_date'])) . "',
                                    '" . stripslashes(str_replace('\r\n', ' ', $data['exit_strategy'])) . "',
                                    '" . stripslashes(str_replace('\r\n', ' ', $data['follow_up_date'])) . "',
                                    '" . stripslashes(str_replace('\r\n', ' ', $data['follow_up_time'])) . "',
                                    '" . stripslashes(str_replace('\r\n', ' ', $data['provider_info'])) . "',
                                    '" . stripslashes(str_replace('\r\n', ' ', $notes)) . "',
                                    '" . stripslashes(str_replace('\r\n', ' ', $data['lead_type'])) . "',
                                    '" . stripslashes(str_replace('\r\n', ' ', $data['predicted_amt'])) . "',
                                    '" . stripslashes(str_replace('\r\n', ' ', $data['forecast_chance'])) . "',
                                    '" . stripslashes(str_replace('\r\n', ' ', $data['earnest_receipt'])) . "',
                                    '" . stripslashes(str_replace('\r\n', ' ', $data['executed_date'])) . "',
                                    '" . stripslashes(str_replace('\r\n', ' ', $data['end_of_option'])) . "',
                                    '" . stripslashes(str_replace('\r\n', ' ', $data['search_city'])) . "',
                                    '" . stripslashes(str_replace('\r\n', ' ', $data['search_state'])) . "',
                                    '" . stripslashes(str_replace('\r\n', ' ', $data['area_of_interest'])) . "',
                                    '" . stripslashes(str_replace('\r\n', ' ', $data['affiliate_id'])) . "',
                                    '" . stripslashes(str_replace('\r\n', ' ', $data['date_added'])) . "',
                                    '" . stripslashes(str_replace('\r\n', ' ', $data['as_is_price'])) . "',
                                    '" . stripslashes(str_replace('\r\n', ' ', $data['owner_occupied'])) . "',
                                    '" . stripslashes(str_replace('\r\n', ' ', $data['how_long_owned'])) . "',
                                    '" . stripslashes(str_replace('\r\n', ' ', $data['roof_age'])) . "',
                                    '" . stripslashes(str_replace('\r\n', ' ', $data['hvac_age'])) . "',
                                    '" . stripslashes(str_replace('\r\n', ' ', $data['pool_condition'])) . "',
                                    '" . stripslashes(str_replace('\r\n', ' ', $data['need_foundation_repair'])) . "',
                                    '" . stripslashes(str_replace('\r\n', ' ', $data['cabinet_type'])) . "',
                                    '" . stripslashes(str_replace('\r\n', ' ', $data['counter_type'])) . "',
                                    '" . stripslashes(str_replace('\r\n', ' ', $data['flooring_type'])) . "',
                                    '" . stripslashes(str_replace('\r\n', ' ', $data['master_bath_age'])) . "',
                                    '" . stripslashes(str_replace('\r\n', ' ', $data['half_bath_age'])) . "',
                                    '" . stripslashes(str_replace('\r\n', ' ', $data['upgrades'])) . "',
                                    '" . stripslashes(str_replace('\r\n', ' ', $data['insurance'])) . "',
                                    '" . stripslashes(str_replace('\r\n', ' ', $data['rent_amt'])) . "',
                                    '" . stripslashes(str_replace('\r\n', ' ', $data['term'])) . "',
                                    '" . stripslashes(str_replace('\r\n', ' ', $data['move_date'])) . "',
                                    '" . stripslashes(str_replace('\r\n', ' ', $data['deposit'])) . "',
                                    '" . stripslashes(str_replace('\r\n', ' ', $data['listed'])) . "',
                                    '" . stripslashes(str_replace('\r\n', ' ', $data['how_long'])) . "',
                                    '" . stripslashes(str_replace('\r\n', ' ', $data['listing_price'])) . "',
                                    '" . stripslashes(str_replace('\r\n', ' ', $data['offer_price'])) . "',
                                    '" . stripslashes(str_replace('\r\n', ' ', $data['moving_reason'])) . "',
                                    '" . stripslashes(str_replace('\r\n', ' ', $data['time_frame_sell'])) . "',
                                    '" . stripslashes(str_replace('\r\n', ' ', $data['price_flexible'])) . "',
                                    '" . stripslashes(str_replace('\r\n', ' ', $data['asking_price_reason'])) . "',
                                    '" . stripslashes(str_replace('\r\n', ' ', $data['cash_quick_close'])) . "',
                                    '" . stripslashes(str_replace('\r\n', ' ', $data['any_better'])) . "',
                                    '" . stripslashes(str_replace('\r\n', ' ', $data['doesnt_sell'])) . "',
                                    '" . stripslashes(str_replace('\r\n', ' ', $data['hh_repair_cost'])) . "',
                                    '" . stripslashes(str_replace('\r\n', ' ', $data['wt_repair_cost'])) . "',
                                    '" . stripslashes(str_replace('\r\n', ' ', $data['rh_lipstick'])) . "',
                                    '" . stripslashes(str_replace('\r\n', ' ', $data['rh_rent_comp'])) . "')";  
                $result = $database->query($q);      
                header("Location: index.php");
            } else {
                $form->setError("", "Cannot save record<br>");
                header("Location: " . $session->referrer);                
            }


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
$DuplicateLeadHelper = new DuplicateLeadHelper;
?>
