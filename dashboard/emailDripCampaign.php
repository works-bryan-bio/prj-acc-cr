<?php

require_once("include/db_connect.php");
require_once ("include/swiftmailer/lib/swift_required.php");
 $date = date("Y-m-d"); //  	2017-05-05 - Y-m-d
			$result = $mysqli->query("SELECT * FROM drip_campaign_details WHERE date_to_send = '". $date ."' AND status = 0 ") or die(mysql_error());
				while($row = mysqli_fetch_array($result)){
					foreach($row AS $key => $value) {
						$row[$key] = stripslashes($value);
					}
					$email[] = $row;
				}

		  if(isset($email)){
		  	foreach ($email as $key => $value):
		  			  		
		  		$id = $value['id'];
			    $recipient_leads = $value['recipient_leads'];
				$recipients	 =  $value['recipients'];
				$subject     = $value['subject'];	
				$content     = strip_tags($value['body_content'] ,'<br>');


				// This set the email and the name of the recipients from the id of leads 
				if($recipients){ 
				    $email_list = "'". str_replace(",", "','", $recipients) ."'" ;
					$email =  array();
					$result = $mysqli->query("SELECT COMPANY_NAME,CLIENT_EMAIL FROM leads WHERE CLIENT_EMAIL IN(".$email_list.") ") or die(mysql_error());
								while($row = mysqli_fetch_array($result)){
									foreach($row AS $key => $value) {
										$row[$key] = stripslashes($value);
									}
									$email[] = $row;
								}
						
					if($email){
						foreach ($email as $key => $value) {
							$email_bcc1[$value['CLIENT_EMAIL']] = $value['COMPANY_NAME']; 
						}
				    }
				}

				
				// This set the email and the name of the recipients from the lead type
				if($recipient_leads){
					
					$lead_type = "'". str_replace(",", "','", $recipient_leads) ."'"; //$recipients; 
					$email2 =  array();
					$result = $mysqli->query("SELECT COMPANY_NAME,CLIENT_EMAIL FROM leads WHERE LEAD_TYPE IN(".$lead_type.") ") or die(mysql_error());
								while($row = mysqli_fetch_array($result)){
									foreach($row AS $key => $value) {
										$row[$key] = stripslashes($value);
									}
									$email2[] = $row;
								}
								

					if($email2){
						foreach ($email2 as $key => $value) {
							if($value['CLIENT_EMAIL'] != ""){
							   $email_bcc2[$value['CLIENT_EMAIL']] = $value['COMPANY_NAME']; 
							}
						}
					}
				}


		   if( isset($email_bcc1) && isset($email_bcc2)){


		   	$email_bcc = array_merge($email_bcc1,$email_bcc2);
		   	unset($email_bcc1,$email_bcc2);

		   }

		   if( isset($email_bcc1)){
		   	$email_bcc = $email_bcc1;
		   	unset($email_bcc1);
		   }		   

		   if( isset($email_bcc2)){
		   	$email_bcc = $email_bcc2;
		   	unset($email_bcc2);
		   }

	

			// Engine for sending email 
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
			->setBcc($email_bcc)			
			// Give it a body
			->setBody($content)
			;

			// Send the message
			$result = $mailer->send($message);	
			
			//Update the campaign once sent
			$mysqli->query("UPDATE drip_campaign_details SET status = 1  WHERE id = ". $id ." ") or die(mysql_error());
            
		  	endforeach;
		  } 





?>