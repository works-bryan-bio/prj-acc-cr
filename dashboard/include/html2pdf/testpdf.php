<?php
    $content = "
		<page>
		    <h1>This is only a test...</h1>
		    <br>
		    Ceci est un <b>exemple d'utilisation</b>
		    de <a href='http://html2pdf.fr/'>HTML2PDF</a>.<br>
		</page>";

    require_once('html2pdf.class.php');

    $fName = "testing.pdf";
    $html2pdf = new HTML2PDF('P', 'A4', 'fr', true, 'UTF-8', array(15, 15, 15, 5)); //HTML2PDF('P','A4','en');
    $html2pdf->WriteHTML($content, false);      
    $pName = $fName; 
    $html2pdf->Output("files/$pName", 'F');     

	$pdf_path = 'http://' . $_SERVER['SERVER_NAME'] . BASE_FOLDER . 'files/coe/' . $fName;

    header('Location: ' . $pdf_path);

?>