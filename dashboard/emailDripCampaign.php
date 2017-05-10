<?php

require_once("include/db_connect.php");
require_once ("include/swiftmailer/lib/swift_required.php");
$date = date("2017-05-05"); //  	2017-05-05 - Y-m-d
			$result = $mysqli->query("SELECT * FROM drip_campaign WHERE date_to_send = '". $date ."' ") or die(mysql_error());
				while($row = mysqli_fetch_array($result)){
					foreach($row AS $key => $value) {
						$row[$key] = stripslashes($value);
					}
					$email[] = $row;
				}

		  		

		  if($email){
		  	foreach ($email as $key => $value):
		  		echo "<pre>";
				print_r($value);
				echo "</pre>";

				$recipient_type = $value['recipient_type'];
				$recipients	 =  $value['recipients'];
				$subject     = $value['subject'];	
				$content     = $value['body_content'];


				// This set the email and the name of the recipients from the id of leads 
				if($recipient_type == 1){ 
				    $email_list = "'". str_replace(",", "','", $recipients) ."'" ;
					$email =  array();
					$result = $mysqli->query("SELECT COMPANY_NAME,CLIENT_EMAIL FROM leads WHERE CLIENT_EMAIL IN(".$email_list.")") or die(mysql_error());
								while($row = mysqli_fetch_array($result)){
									foreach($row AS $key => $value) {
										$row[$key] = stripslashes($value);
									}
									$email[] = $row;
								}
					if(is_array($email)){
						
						foreach ($email as $key => $value) {
							$email_bcc[$value['CLIENT_EMAIL']] = $value['COMPANY_NAME']; 
						}
				    }
				}

				// This set the email and the name of the recipients from the lead type
				if($recipient_type == 2){

					$lead_type = $recipients; 
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
							   $email_bcc[$value['CLIENT_EMAIL']] = $value['COMPANY_NAME']; 
							}
						}
					}
				}


			// Engine for sending email 
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
			->setBcc($email_bcc)

			// Give it a body
			->setBody($content)
			;

			// Send the message
			$result = $mailer->send($message);	


			echo "<pre>";
			print_r($result);
			print_r($bcc);
			echo "</pre>";
          
		  	endforeach;
		  } 


exit;
header("Location: thank_you.php");

?>