<?php

require_once("include/db_connect.php");
require_once ("include/swiftmailer/lib/swift_required.php");

if($_POST['lead_s'] == "search_lead"){ 
	 $email_list = "'". str_replace(",", "','", $_POST['search_leads_auto_complete']) ."'" ;
	$email =  array();
	$result = $mysqli->query("SELECT COMPANY_NAME,CLIENT_EMAIL FROM leads WHERE CLIENT_EMAIL IN(".$email_list.")") or die(mysql_error());
				while($row = mysqli_fetch_array($result)){
					foreach($row AS $key => $value) {
						$row[$key] = stripslashes($value);
					}
					$email[] = $row;
				}
	if($email){ 
		foreach ($email as $key => $value) {
			$bcc[$value['CLIENT_EMAIL']] = $value['COMPANY_NAME']; 
		}
    }

}

if($_POST['lead_s'] == "search_lead_type"){ 

	$lead_type = $_POST['lead_type']; 
	$email =  array();
	$result = $mysqli->query("SELECT COMPANY_NAME,CLIENT_EMAIL FROM leads WHERE LEAD_TYPE = ". '"'.$lead_type.'"' ."  ") or die(mysql_error());
				while($row = mysqli_fetch_array($result)){
					foreach($row AS $key => $value) {
						$row[$key] = stripslashes($value);
					}
					$email[] = $row;
				}
	if($email){ 
		foreach ($email as $key => $value) {
			if($value['CLIENT_EMAIL'] != ""){
			   $bcc[$value['CLIENT_EMAIL']] = $value['COMPANY_NAME']; 
			}
		}
	}
}


$subject = $_POST['subject'];
$content = $_POST['messageMassEmail'];

// Create the Transport
$transport = Swift_SmtpTransport::newInstance('smtp.simplehousesolutionscrm.com', 25)
  ->setUsername('info@simplehousesolutionscrm.com')
  ->setPassword('abc123!xyz')
  ;

// Create the Mailer using your created Transport
$mailer = Swift_Mailer::newInstance($transport);


// Create the message
$message = Swift_Message::newInstance($subject)

// Set the From address with an associative array
->setFrom(array('info@simplehousesolutionscrm.com' => 'Info'))

// Set the To addresses with an associative array
->setTo(array('info@simplehousesolutionscrm.com' => 'Info'))

// Set the BCC for the recipient with multiple email for email blast 
->setBcc($bcc)

// Give it a body
->setBody($content)
;

// Send the message
$result = $mailer->send($message);


header("Location: thank_you.php");

?>