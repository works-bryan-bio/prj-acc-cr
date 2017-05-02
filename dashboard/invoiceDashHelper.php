<?php
require_once("include/checklogin.php");
require_once("include/session.php");
require_once("include/db_connect.php");
require_once('include/fpdf.php');

//parse URL for variables
$action = $_GET["action"];
$lead_id = $_GET["lead_id"];
if ($lead_id==null) {
	$lead_id = $_POST["lead_id"];
}

if ($action=="approve") {
	if ($lead_id!=null) {
		$query = "UPDATE invoices SET date_approved=CURRENT_TIMESTAMP WHERE LEAD_ID=" . $lead_id;
		$result = $mysqli->query($query)	or die(mysql_error());
		header("Location: invoiceDash.php");
	} else {
		echo "Error: Lead Not Found";
	}
} else if ($action=="reject") {
	if ($lead_id!=null) {
		$query = "UPDATE invoices SET date_submitted=null,date_approved=null WHERE LEAD_ID=" . $lead_id;
		$result = $mysqli->query($query)	or die(mysql_error());
		header("Location: invoiceDash.php");
	} else {
		echo "Error: Lead Not Found";
	}
} else if ($action=="generatePDF") {
	if ($lead_id!=null) {
		//$results = $mysqli->query("SELECT * FROM leads JOIN providers ON properties.PROVIDER_ID=providers.PROVIDER_ID WHERE property_id=" . $property_id) or die($mysqli->error);
		$pdf = new FPDF('P','mm','Letter');
		$pdf->AddPage();
		$pdf->Image('images/logo.png',10,5);
		$pdf->SetFont('Arial','B',12);
		$pdf->Cell(5,25,'SimpleHouseSolutions.com');
		$pdf->Output();
	} else {
		echo "Error: Lead Not Found";
	}
} else {
	echo "Error: Action not specified";
}

?>