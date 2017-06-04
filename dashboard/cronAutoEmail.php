<?php

class cronAutoEmailHelper {

	function leadsClosing(){
		require_once('include/db_connect.php');
		require_once ("include/swiftmailer/lib/swift_required.php");
		$query_date = date("Y-m-d", strtotime("+5 days"));		
		$result = $mysqli->query("SELECT LEAD_ID, FIRST_NAME, LAST_NAME, CLIENT_EMAIL, CLOSED_DATE FROM leads WHERE CLOSED_DATE = '{$query_date}'") or die(mysql_error());
		if( $result->num_rows > 0 ) {

			$subject = "SimpleHouseSolutions :  Leads Closed Date";
			$to 	 = array('works.bryan.bio@gmail.com' => 'works.bryan.bio@gmail.com');
			$content = "Hi, <Br/>";
			$content .= "<p>Below are the list of leads for closing in 5 days.</p>";
			$leads_list = array();
			while($row = mysqli_fetch_array($result)){				

				$leads_list[] = "<li>" . $row['FIRST_NAME'] . ' ' . $row['LAST_NAME'] . " / " . $row['CLIENT_EMAIL'] . "</li>";
			}

			$content .= "<ul>" . implode("", $leads_list) . "</ul>";
			$content .= "<br/><p>Thank you and have a great day!</p>";			

			//Send Email
			$transport = Swift_SmtpTransport::newInstance('mail.simplehousesolutionscrm.com', 26)
			  ->setUsername('info@simplehousesolutionscrm.com')
			  ->setPassword('abc123!xyz')
			  ;
			$mailer  = Swift_Mailer::newInstance($transport);				
			$message = Swift_Message::newInstance($subject)
			->setFrom(array('info@simplehousesolutionscrm.com' => 'Info'))
			->setTo($to)
			//->setBcc($email_bcc)
			->setBody($content)
			;
			$result = $mailer->send($message);	
			echo "Email Closing Sent! <br />";
		}
	}

	function leadsUnassigned(){
		require_once('include/db_connect.php');
		require_once ("include/swiftmailer/lib/swift_required.php");
		$query_minutes = 5;
		$query_time    = date("Y-m-d H:i:s", strtotime("-5 minutes"));
		$current_date  = date("Y-m-d H:i:s");				
		$result = $mysqli->query("SELECT LEAD_ID, FIRST_NAME, LAST_NAME, CLIENT_EMAIL, CLOSED_DATE FROM leads WHERE USERNAME='Not Assigned' AND ( DATE_ADDED >= '{$query_time}' AND DATE_ADDED <= '{$current_date}') ORDER BY date_added ASC") or die(mysql_error());		

		if( $result->num_rows > 0 ) {
			$subject = "SimpleHouseSolutions :  Leads Unassigned";
			$to 	 = array('works.bryan.bio@gmail.com' => 'works.bryan.bio@gmail.com');
			//$to      = 'newclients@simplehousesolutions.com';
			$content = "Hi, <Br/>";
			$content .= "<p>Below are the list of unassigned leads 5 minutes ago.</p>";
			$leads_list = array();
			while($row = mysqli_fetch_array($result)){				

				$leads_list[] = "<li>" . $row['FIRST_NAME'] . ' ' . $row['LAST_NAME'] . " / " . $row['CLIENT_EMAIL'] . "</li>";
			}

			$content .= "<ul>" . implode("", $leads_list) . "</ul>";
			$content .= "<br/><p>Thank you and have a great day!</p>";			

			//Send Email
			$transport = Swift_SmtpTransport::newInstance('mail.simplehousesolutionscrm.com', 26)
			  ->setUsername('info@simplehousesolutionscrm.com')
			  ->setPassword('abc123!xyz')
			  ;
			$mailer  = Swift_Mailer::newInstance($transport);				
			$message = Swift_Message::newInstance($subject)
			->setFrom(array('info@simplehousesolutionscrm.com' => 'Info'))
			->setTo($to)
			//->setBcc($email_bcc)
			->setBody($content)
			;
			$result = $mailer->send($message);	
			echo "Email Closing Sent! <br />";
		}
	}
}

$autoEmail = new cronAutoEmailHelper();
$autoEmail->leadsClosing();
$autoEmail->leadsUnassigned();
?>