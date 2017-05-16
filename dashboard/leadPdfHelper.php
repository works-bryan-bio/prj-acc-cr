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

        $content = '
            <page style="">
                <table cellspacing="0" style="font-size: 10pt; width: 100%;">
                    <tr>
                        <td style="text-align:center;" align="center" colspan="4">
                            <div>Logo Here</div>
                        </td>
                    </tr>    
                    <tr>
                        <td style="" colspan="4">
                            <h1>Seller Lead - Interview Sheet</h1>
                        </td>
                    </tr>
                </table>    
                <br />
                <table cellspacing="0" style="font-size: 11pt;">
                    <tr>
                        <td>
                            Property Address:
                        </td>
                        <td style="text-decoration:underline;">
                           '.$data['ADDRESS_1'].'
                        </td>
                        <td>
                            Date: 
                        </td>
                        <td style="text-decoration:underline;">
                            '.date("F j, Y", strtotime($data['DATE_ADDED'])).'
                        </td>
                        <td>
                            Lead Source:
                        </td>
                        <td style="text-decoration:underline;">
                            '. $data['affiliate_name'] .'
                        </td>
                    </tr>
                    <tr>
                        <td>
                            City, State, Zip:
                        </td>
                        <td style="text-decoration:underline;">
                           '.$data['CITY'] . ',' . $data['STATE'] . ','. $data['ZIP']. '
                        </td>
                        <td>
                            Phone #: 
                        </td>
                        <td style="text-decoration:underline;">
                            '. $office_phone_other .'
                        </td>
                        <td>
                            Phone Type:
                        </td>
                        <td style="text-decoration:underline;">
                            Home Cell Work
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Contact Person:
                        </td>
                        <td style="text-decoration:underline;">
                           '. $data['FIRST_NAME'] . ' ' . $data['LAST_NAME'] .'
                        </td>
                        <td>
                            
                        </td>
                        <td style="text-decoration:underline;">
                            
                        </td>
                        <td>
                            2nd Chance Prog.:
                        </td>
                        <td style="text-decoration:underline;">
                            Y N Both
                        </td>
                    </tr>  
                    <tr>
                        <td>
                            Owner(s) on Title:
                        </td>
                        <td style="text-decoration:underline;">
                          
                        </td>
                        <td>
                            
                        </td>
                        <td>
                            Email.
                        </td>
                        <td style="text-decoration:underline;">
                            '.$data['CLIENT_EMAIL'].'
                        </td>
                        <td style="text-decoration:underline;">
                           
                        </td>
                    </tr>                                                                
                </table>
                <h3>Property Information</h3>
                <div>Do you currently leave in the home Y / N How long: ______ Bed: ______ Bath: ______ Year Built: ______ </div>
                <div>Garage: _______ 1 / 2 / 3 Car Garage Converted Garage: Y / N Stories: ______ Sqft: ______ </div>
                <div>Age of Roof: _______ Age of HVAC ______ Age of Pool EQPT: ______ Foundation Repaired? ________ </div>
                <div>Age/Type Kitchen Cabinet: ______________________ Age/Type Counter Tops: _____________________</div>
                <div>Flooring Age/Type: ___________________________ Master Bath Age: ______ Half Bath Age: ______</div>
                <div>Upgrades? ____________________________________________________________________________</div>
                <div>Have Insurance: _______ Rent Amount: _______ Term: _______ Move Date: _______ Deposit: _______</div>
                <div>Is the Home Listed: ______ Price: ______ How Long: ______ Offers? / How Much?: ______</div>
                <div>Notes: ____________________________________________________________________</div>
                <div>____________________________________________________________________</div>
                <div>____________________________________________________________________</div>
                <h3>Motivation & Price</h3>
                <div>Is there a particular reason you are looking to sell at this time?</div>
                <div>____________________________________________________________________</div>
                <div>How quickly are you looking to sell? Timeline: __________________________</div>
                <div>____________________________________________________________________</div>
                <div>What are you looking to sell the property for?________________ Is that price flexible? ________</div>
                <div>How did you establish that number?________________________________________________________</div>
                <div>If I can offer you cash and close quickly what is the best you can do? ______________________</div>
                <div>Can you do any better than that?_________________________________________________________</div>
                <div>What are you going to do if the property does not sell? ____________________________</div>
                <h3>Mortgage Information</h3>
                <div>What do you currently owe on the property?_________ Are you current on the payment? _________</div>
                <h3>Exit Strategy</h3>
                <table cellspacing="0" style="font-size: 10pt; width: 100%;">
                    <td style="width:50%" width="50%">
                        <h4>Unlimited Exit</h4>
                        <div>ARV ________ Confident Not Confident</div>
                        <div>70% of  ARV ______________________</div>
                        <div>Repair Cost ________ Confident Not Confident</div>
                        <div>MAO ________ Confident Not Confident</div>
                        <h4>Half-Hab/Make Ready/As-Is on MLS</h4>
                        <div>As-Is Price ________ Confident Not Confident</div>
                        <div>Repair Cost ________ Confident Not Confident</div>
                        <div>Asking Price _________</div>
                        <div>Potential Profit _________ (Must be 30k with no question)</div>
                    </td>
                    <td style="width:50%" width="50%">
                        <h4>Wholesale or Terminate</h4>
                        <div>ARV ________ Confident Not Confident</div>
                        <div>80% of  ARV ______________________</div>
                        <div>Repair Cost ________ Typically 20k-30k</div>
                        <div>Fee: $5,000</div>
                        <div>Asking Price________</div>
                        <h4>Rental or Hedge</h4>
                        <div>Build 1985+ | Lipstick | Purchase $ | Under 170k</div>
                        <div>ARV ________ Confident Not Confident</div>
                        <div>80% of ARV _______ Rent Comp ______</div>
                    </td>
                </table>
            </page>
        ';

        $fName = "leadReport.pdf";
        $html2pdf = new HTML2PDF('P', 'A4', 'fr', true, 'UTF-8', array(15, 15, 15, 5)); //HTML2PDF('P','A4','en');
        $html2pdf->WriteHTML($content, false);      
        $pdfFileName = $fName; 
        $html2pdf->Output("files/$pdfFileName", 'F');           

        $base_folder = '/bigfish/tim/prj-acc-cr/dashboard/';

        $pdf_path = 'https://' . $_SERVER['SERVER_NAME'] . $base_folder . 'files/' . $pdfFileName;
        header('Location: ' . $pdf_path);

    }

}

/* Initialize process */
$leadPdfHelper = new leadPdfHelper;
?>
