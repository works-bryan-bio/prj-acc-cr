<?php
require_once("include/checklogin.php");
require_once("include/session.php");
require_once("include/db_connect.php");

class leadPdfHelper {

    function leadPdfHelper() {
        global $session;
        /* Make sure administrator is accessing page */
        if (!$session->isAdmin() && !$session->isMaster()) {
            header("Location: index.php");
            return;
        }

        if (isset($_GET['print'])) {
            $this->printPdfReport();
        } else {
            header("Location: index.php");
        }
    }

    function printPdfReport() {
        global $session, $database, $form;
        $lead_id = $_GET['lead_id'];


        require_once('include/html2pdf/html2pdf.class.php');

        /*$result = $database->query("
                                SELECT * FROM leads WHERE lead_id=" . $lead_id) 
                    or die(mysqli_error()); */       

        $result = $database->query("
                                SELECT leads.*, affiliates.COMPANY_NAME as affiliate_name FROM leads 
                                LEFT JOIN affiliates 
                                ON leads.AFFILIATE_ID = affiliates.AFFILIATE_ID 
                                WHERE lead_id=" . $lead_id) 
                    or die(mysqli_error()); 

        $data = mysqli_fetch_array($result);

        $office_phone_other = !empty($data['OFFICE_PHONE']) ? $data['OFFICE_PHONE'] : $data['CELL_PHONE'];

        if(!empty($data['OFFICE_PHONE'])) {
            $phone_type = "Work";
        }elseif(!empty($data['CELL_PHONE'])) {
            $phone_type = "Cell";
        }elseif(!empty($data['OTHER_PHONE'])){
            $phone_type = "Home";
        }        

        $lipstick = $data['RH_LIPSTICK'] == 1 ? 'OK' : '';
        $arv70percent = $data['ARV'] * 0.70;
        $arv80percent = $data['ARV'] * 0.80;

        $content = '
            <page style="">
                <h1>Seller Lead - Interview Sheet</h1>   
                <table cellspacing="0" style="font-size: 10pt; width: 100%;">
                    <td style="width:45%;" width="45%">
                        <strong>Property Address:</strong> '. $data['ADDRESS_1'] .'
                    </td>
                    <td style="width:20%" width="20%">
                        <strong>Date:</strong> '.date("M j, Y", strtotime($data['DATE_ADDED'])).'
                    </td>
                    <td style="width:35%" width="35%">
                       <strong>Lead Source:</strong> '.$data['affiliate_name'].'
                    </td>
                </table>
                <br /><br />
                <table cellspacing="0" style="font-size: 10pt; width: 100%;">
                    <td style="width:45%;" width="45%">
                        <strong>City, State, Zip:</strong> '. $data['CITY'] . ',' . $data['STATE'] . ','. $data['ZIP'] .'
                    </td>
                    <td style="width:25%" width="25%">
                        <strong>Phone #:</strong> '.$office_phone_other.'
                    </td>
                    <td style="width:30%" width="30%">
                       <strong>Phone Type:</strong> '.$phone_type.'
                    </td>
                </table>
                <br /><br />
                <table cellspacing="0" style="font-size: 10pt; width: 100%;">
                    <td style="width:45%;" width="45%">
                        <strong>Contact Person:</strong> '. $data['FIRST_NAME'] . ' ' . $data['LAST_NAME'] .'
                    </td>
                    <td style="width:50%" width="50%">
                        <strong>2nd Chance Program:</strong> '. $data['SECOND_CHANCE'] .'
                    </td>
                </table>
                <br /><br />     
                <table cellspacing="0" style="font-size: 10pt; width: 100%;">
                    <td style="width:45%;" width="45%">
                        <strong>Owner(s) on Title:</strong> '. '---' .'
                    </td>
                    <td style="width:50%" width="50%">
                        <strong>Email:</strong> '. $data['CLIENT_EMAIL'] .'
                    </td>
                </table>
                <br /><br />
                <h3>Property Information</h3>
                <table cellspacing="0" style="font-size: 10pt; width: 100%;">
                    <td style="width:45%;" width="45%">
                        <strong>Do you currently leave in the home:</strong> '. '___' .'
                    </td>
                    <td style="width:20%" width="20%">
                        <strong>How long:</strong> '. '__' .'
                    </td>
                    <td style="width:10%" width="10%">
                        <strong>Bed:</strong> '. $data['BEDROOMS'] .'
                    </td>
                    <td style="width:10%" width="10%">
                        <strong>Bath:</strong> '. $data['BATHROOMS'] .'
                    </td>
                    <td style="width:15%" width="15%">
                        <strong>Yr Built:</strong> '. $data['YEAR_BUILT'] .'
                    </td>
                </table>
                <br /><br />
                <table cellspacing="0" style="font-size: 10pt; width: 100%;">
                    <td style="width:20%;" width="20%">
                        <strong>Garage:</strong> '. $data['GARAGE_TYPE'] . ' (' . $data['GARAGES'] .')
                    </td>
                    <td style="width:35%" width="35%">
                        <strong>Car Garage Converted Garage:</strong> '. $data['GARAGE_CONVERTED'] .'
                    </td>
                    <td style="width:20%" width="20%">
                        <strong>Stories:</strong> '. $data['STORIES'] .'
                    </td>
                    <td style="width:20%" width="20%">
                        <strong>Sqft:</strong> '. $data['SQUARE_FEET'] .'
                    </td>
                </table>
                <br /><br />
                <table cellspacing="0" style="font-size: 10pt; width: 100%;">
                    <td style="width:25%;" width="25%">
                        <strong>Age of Roof:</strong> '. $data['ROOF_AGE'] . '
                    </td>
                    <td style="width:25%" width="25%">
                        <strong>Age of HVAC:</strong> '. $data['HVAC_AGE'] .'
                    </td>
                    <td style="width:25%" width="25%">
                        <strong>Age of Pool EQPT:</strong> '. '---' .'
                    </td>
                    <td style="width:25%" width="25%">
                        <strong>Foundation Repaired:</strong> '. $data['NEED_FOUNDATION_REPAIR'] .'
                    </td>
                </table>
                <br /><br />
                <table cellspacing="0" style="font-size: 10pt; width: 100%;">
                    <td style="width:50%;" width="50%">
                        <strong>Age/Type Kitchen Cabinet:</strong> '. $data['CABINET_TYPE'] . '
                    </td>
                    <td style="width:50%" width="50%">
                        <strong>Age/Type Counter Tops:</strong> '. $data['COUNTER_TYPE'] .'
                    </td>
                </table>
                <br /><br />
                <table cellspacing="0" style="font-size: 10pt; width: 100%;">
                    <td style="width:33%;" width="33%">
                        <strong>Flooring Age/Type:</strong> '. $data['FLOORING_TYPE'] . '
                    </td>
                    <td style="width:33%" width="33%">
                        <strong>Master Bath Age:</strong> '. $data['MASTER_BATH_AGE'] .'
                    </td>
                    <td style="width:33%" width="33%">
                        <strong>Half Bath Age:</strong> '. $data['HALF_BATH_AGE'] .'
                    </td>
                </table>   
                <br /><br />       
                <table cellspacing="0" style="font-size: 10pt; width: 100%;">
                    <td style="width:100%;" width="100%">
                        <strong>Upgrades:</strong> '. $data['UPGRADES'] . '
                    </td>
                </table>                                                       
                <br /><br />  
                <br />
                <table cellspacing="0" style="font-size: 10pt; width: 100%;">
                    <td style="width:20%;" width="20%">
                        <strong>Have Insurance:</strong> '. $data['INSURANCE'] . '
                    </td>
                    <td style="width:20%;" width="20%">
                        <strong>Rent Amt:</strong> '. $data['RENT_AMT'] . '
                    </td>
                    <td style="width:20%;" width="20%">
                        <strong>Term:</strong> '. $data['TERM'] . '
                    </td>
                    <td style="width:25%;" width="25%">
                        <strong>Move Date:</strong> '. $data['MOVE_DATE'] . '
                    </td>
                    <td style="width:15%;" width="15%">
                        <strong>Deposit:</strong> '. $data['DEPOSIT'] . '
                    </td>
                </table>               
                <br /><br />
                <table cellspacing="0" style="font-size: 10pt; width: 100%;">
                    <td style="width:25%;" width="25%">
                        <strong>Is the Home Listed:</strong> '. $data['LISTED'] . '
                    </td>
                    <td style="width:15%;" width="15%">
                        <strong>Price:</strong> '. $data['LISTING_PRICE'] . '
                    </td>
                    <td style="width:20%;" width="20%">
                        <strong>How Long:</strong> '. $data['HOW_LONG'] . '
                    </td>
                    <td style="width:35%;" width="35%">
                        <strong>Offers?/How Much?:</strong> '. $data['OFFER_PRICE'] . '
                    </td>
                </table>
                <br /><br />
                <table cellspacing="0" style="font-size: 10pt; width: 100%;">
                    <td style="width:100%;" width="100%">
                        <strong>Notes:</strong> '. substr($data['NOTES'],0,500) . '
                    </td>
                </table>
                <br /><br />
                <br /><br />
                <br />
                <h3>Motivation & Price</h3>
                <table cellspacing="0" style="font-size: 10pt; width: 100%;">
                    <td style="width:100%;" width="100%">
                        <strong>Is there a particular reason you are looking to sell at this time?</strong> '. $data['MOVING_REASON'] . '
                    </td>
                </table>                
                <br /><br />
                <br />
                <table cellspacing="0" style="font-size: 10pt; width: 100%;">
                    <td style="width:100%;" width="100%">
                        <strong>How quickly are you looking to sell? Timeline:</strong> '. $data['TIME_FRAME_SELL'] . '
                    </td>
                </table>
                <br /><br />
                <table cellspacing="0" style="font-size: 10pt; width: 100%;">
                    <td style="width:70%;" width="70%">
                        <strong>What are you looking to sell the property for?</strong> '. '--' . '
                    </td>
                    <td style="width:30%;" width="30%">
                        <strong>Is that price flexible?</strong> '. $data['PRICE_FLEXIBLE'] . '
                    </td>
                </table>  
                <br /><br />
                <table cellspacing="0" style="font-size: 10pt; width: 100%;">
                    <td style="width:100%;" width="100%">
                        <strong>How did you establish that number?</strong> '. $data['ASKING_PRICE_REASON'] . '
                    </td>
                </table>  
                <br /><br />

                <table cellspacing="0" style="font-size: 10pt; width: 100%;">
                    <td style="width:100%;" width="100%">
                        <strong>If I can offer you cash and close quickly what is the best you can do?</strong> '. $data['CASH_QUICK_CLOSE'] . '
                    </td>
                </table>  
                <br /><br />
                <table cellspacing="0" style="font-size: 10pt; width: 100%;">
                    <td style="width:100%;" width="100%">
                        <strong>Can you do any better than that?</strong> '. $data['ANY_BETTER'] . '
                    </td>
                </table>  
                <br /><br />
                <table cellspacing="0" style="font-size: 10pt; width: 100%;">
                    <td style="width:100%;" width="100%">
                        <strong>What are you going to do if the property does not sell?</strong> '. $data['DOESNT_SELL'] . '
                    </td>
                </table>  
                <br /><br />
                
                <h3>Mortgage Information</h3>
                <table cellspacing="0" style="font-size: 10pt; width: 100%;">
                    <td style="width:60%;" width="60%">
                        <strong>What do you currently owe on the property?</strong> '. $data['CURRENT_MORTGAGE'] . '
                    </td>
                    <td style="width:40%;" width="40%">
                        <strong>Are you current on the payment?</strong> '. $data['CURRENT_PAYMENTS'] . '
                    </td>
                </table> 
                <br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />
                <h3>Exit Strategy</h3>
                <table cellspacing="0" style="font-size: 10pt; width: 100%;">
                    <td style="width:50%" width="50%">
                       <h4>Unlimited Exit</h4>
                       <table cellspacing="0" style="font-size: 10pt; width: 100%;">
                        <tr>
                            <td style="width:50%" width="50%">ARV</td>
                            <td style="width:50%" width="50%"> ' . $data['ARV'] . ' </td>
                        </tr>
                        <tr>
                            <td style="width:50%" width="50%">70% of ARV: </td>
                            <td style="width:50%" width="50%"> ' . $arv70percent . ' </td>
                        </tr>
                        <tr>
                            <td style="width:50%" width="50%">Repair Cost</td>
                            <td style="width:50%" width="50%">--</td>
                        </tr>
                        <tr>
                            <td style="width:50%" width="50%">MAO</td>
                            <td style="width:50%" width="50%">--</td>
                        </tr>
                       </table>
                       <h4>Half-Hab/Make Ready/As-Is on MLS</h4>
                       <table cellspacing="0" style="font-size: 10pt; width: 100%;">
                        <tr>
                            <td style="width:50%" width="50%">As-Is Price</td>
                            <td style="width:50%" width="50%">' . $data['AS_IS_PRICE'] . '</td>
                        </tr>
                        <tr>
                            <td style="width:50%" width="50%">Repair Cost</td>
                            <td style="width:50%" width="50%"> '. $data['HH_REPAIR_COST'].' (Max 10k)</td>
                        </tr>
                        <tr>
                            <td style="width:50%" width="50%">Asking Price</td>
                            <td style="width:50%" width="50%">' . $data['ASKING_PRICE'] . '</td>
                        </tr>
                        <tr>
                            <td style="width:50%" width="50%">Potential Profit</td>
                            <td style="width:50%" width="50%">(Must be 30k with no question)</td>
                        </tr>
                       </table>
                    </td>
                    <td style="width:50%" width="50%">
                        <h4>Wholesale or Terminate</h4>
                       <table cellspacing="0" style="font-size: 10pt; width: 100%;">
                        <tr>
                            <td style="width:50%" width="50%">ARV</td>
                            <td style="width:50%" width="50%">' . $data['ARV'] . '</td>
                        </tr>
                        <tr>
                            <td style="width:50%" width="50%">80% of ARV: </td>
                            <td style="width:50%" width="50%"> ' . $arv80percent . ' </td>
                        </tr>
                        <tr>
                            <td style="width:50%" width="50%">Repair Cost</td>
                            <td style="width:50%" width="50%"> '.$data['WT_REPAIR_COST'].' (Typically 20-30k)</td>
                        </tr>
                        <tr>
                            <td style="width:50%" width="50%">Fee </td>
                            <td style="width:50%" width="50%">$5000</td>
                        </tr>
                        <tr>
                            <td style="width:50%" width="50%">Asking Price</td>
                            <td style="width:50%" width="50%">' . $data['ASKING_PRICE'] . '</td>
                            
                        </tr>
                        <tr>
                            <td style="width:50%" width="50%">Potential Profit</td>
                            <td style="width:50%" width="50%">(Must be over 10k)</td>
                        </tr>
                       </table>    
                       <h4>Rental or Hedge</h4>     
                       <table cellspacing="0" style="font-size: 10pt; width: 100%;">
                        <tr>
                            <td style="width:50%" width="50%">
                                Built 1985+ Lipstick ' . $lipstick . '
                            </td>
                            <td style="width:50%" width="50%">
                                Purchase $ Under 170k
                            </td>
                        </tr>
                        <tr>
                            <td style="width:50%" width="50%">ARV</td>
                            <td style="width:50%" width="50%">' . $data['ARV'] . '</td>
                        </tr>      
                        <tr>
                            <td style="width:50%" width="50%">80% of ARV: ' . $arv80percent . ' </td>
                            <td style="width:50%" width="50%">Rent Comp. '.$data['RH_RENT_COMP'].' </td>
                        </tr>             
                       </table>                                  
                    </td>
                </table>
            </page>
        ';

        $fName = "leadReport.pdf";
        $html2pdf = new HTML2PDF('P', 'A4', 'fr', true, 'UTF-8', array(15, 15, 15, 5)); //HTML2PDF('P','A4','en');
        $html2pdf->WriteHTML($content, false);      
        $pdfFileName = $fName; 
        $html2pdf->Output("files/$pdfFileName", 'F');           

        //$base_folder = '/bigfish/tim/prj-acc-cr/dashboard/';
        $base_folder = '/tim/prj-acc-cr/dashboard/';

        $pdf_path = 'http://' . $_SERVER['SERVER_NAME'] . $base_folder . 'files/' . $pdfFileName;
        header('Location: ' . $pdf_path);

    }

}

/* Initialize process */
$leadPdfHelper = new leadPdfHelper;
?>
