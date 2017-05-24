<?php

require_once("include/db_connect.php");
require_once ("include/swiftmailer/lib/swift_required.php");




if($_POST['search_leads_auto_complete']){ 
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

if(is_array($_POST['lead_type'])){ 

	 $lead_type = "'". implode("','",$_POST['lead_type']) ."'" ; 
	$email1 =  array();
	$result = $mysqli->query("SELECT COMPANY_NAME,CLIENT_EMAIL FROM leads WHERE LEAD_TYPE IN(".$lead_type.")  ") or die(mysql_error());
				while($row = mysqli_fetch_array($result)){
					foreach($row AS $key => $value) {
						$row[$key] = stripslashes($value);
					}
					$email1[] = $row;
				}
	if($email1){ 
		foreach ($email1 as $key => $value) {
			if($value['CLIENT_EMAIL'] != ""){
			   $bcc1[$value['CLIENT_EMAIL']] = $value['COMPANY_NAME']; 
			}
		}
	}

	
}




$subject = $_POST['subject'];
$content = strip_tags($_POST['messageMassEmail'], '<br>');  

// Create the Transport
$transport = Swift_SmtpTransport::newInstance('mail.simplehousesolutionscrm.com', 26)
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
->setBcc($bcc1)
// Give it a body
->setBody($content)
;
// Send the message
$result = $mailer->send($message);



header("Location: thank_you.php");

?>