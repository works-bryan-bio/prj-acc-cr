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

        require_once('include/html2pdf/html2pdf.class.php');

        $content = "
            <page>
                <h1>This is only a test...</h1>
                <br>
                This is <b>only a test</b>
                de etc.... etc.......<br>
            </page>";        

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
