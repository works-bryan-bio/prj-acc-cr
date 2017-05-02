<?php
require_once("include/checklogin.php");
require_once("include/session.php");
require_once("include/db_connect.php");

// store the lead_id if set
$lead_id = null;
if (isset($_GET['lead_id'])) {
	$lead_id = $_GET['lead_id'];
}

$action = "";
if (isset($_GET['action'])) {
	$action = $_GET['action'];
}

$username = $_SESSION['username'];

// check if the form has been submitted. If it has, start to process the form and save it to the database
if (isset($_POST["submit"]) && $lead_id!=null) {

	// check to make sure that all required fields are available
	if ($lead_id != '') {

		$query = "SELECT * FROM invoices WHERE LEAD_ID=" . $lead_id;
		$result = $mysqli->query($query)	or die(mysql_error());
		if ($result->num_rows==0) {

			$stmt = $mysqli->prepare("INSERT INTO invoices (
												AGREED_FEE, FLAT_RATE, SUITE_NUMBER, 
												NUMBER_WS, SQFT_TAKEN, MOVE_IN_DATE, CLOSED_DATE, PIV_REFERENCE, 
												MONTHS_SIGNED, BILLING_FREQ, INVOICE_TOTAL,	
												MONTH_1, MONTH_2, MONTH_3, MONTH_4, MONTH_5, MONTH_6, MONTH_7, MONTH_8, MONTH_9, MONTH_10,
												MONTH_11, MONTH_12, MONTH_13, MONTH_14, MONTH_15, MONTH_16, MONTH_17, MONTH_18, MONTH_19, MONTH_20, 
												MONTH_21, MONTH_22, MONTH_23, MONTH_24, MONTH_25, MONTH_26, MONTH_27, MONTH_28, MONTH_29, MONTH_30,
												MONTH_31, MONTH_32, MONTH_33, MONTH_34, MONTH_35, MONTH_36, MONTH_37, MONTH_38, MONTH_39, MONTH_40,
												MONTH_41, MONTH_42, MONTH_43, MONTH_44, MONTH_45, MONTH_46, MONTH_47, MONTH_48, MONTH_49, MONTH_50,
												MONTH_51, MONTH_52, MONTH_53, MONTH_54, MONTH_55, MONTH_56, MONTH_57, MONTH_58, MONTH_59, MONTH_60,
												MONTH_61, MONTH_62, MONTH_63, MONTH_64, MONTH_65, MONTH_66, MONTH_67, MONTH_68, MONTH_69, MONTH_70,
												MONTH_71, MONTH_72, MONTH_73, MONTH_74, MONTH_75, MONTH_76, MONTH_77, MONTH_78, MONTH_79, MONTH_80,
												MONTH_81, MONTH_82, MONTH_83, MONTH_84, MONTH_85, MONTH_86, MONTH_87, MONTH_88, MONTH_89, MONTH_90,
												MONTH_91, MONTH_92, MONTH_93, MONTH_94, MONTH_95, MONTH_96, MONTH_97, MONTH_98, MONTH_99, MONTH_100,
												MONTH_101, MONTH_102, MONTH_103, MONTH_104, MONTH_105, MONTH_106, MONTH_107, MONTH_108, MONTH_109, MONTH_110,
												MONTH_111, MONTH_112, MONTH_113, MONTH_114, MONTH_115, MONTH_116, MONTH_117, MONTH_118, MONTH_119, MONTH_120,
												LEAD_ID, PROPERTY_ID, DATE_ADDED) 
												VALUES (
												?, ?, ?, ?, ?, ?, ?, ?, ?, ?,	?, ?,
												?, ?, ?, ?, ?, ?, ?, ?, ?, ?,	?, ?,
												?, ?, ?, ?, ?, ?, ?, ?, ?, ?,	?, ?,
												?, ?, ?, ?, ?, ?, ?, ?, ?, ?,	?, ?,
												?, ?, ?, ?, ?, ?, ?, ?, ?, ?,	?, ?,
												?, ?, ?, ?, ?, ?, ?, ?, ?, ?,	?, ?,
												?, ?, ?, ?, ?, ?, ?, ?, ?, ?,	?, ?,
												?, ?, ?, ?, ?, ?, ?, ?, ?, ?,	?, ?,
												?, ?, ?, ?, ?, ?, ?, ?, ?, ?,	?, ?,
												?, ?, ?, ?, ?, ?, ?, ?, ?, ?,	?, ?,
												?, ?, ?, ?, ?, ?, ?, ?, ?, ?,	?, ?,
												?, ?)
												") or die($mysqli->error);
				$stmt->bind_param("ddssssssisdssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssiis",
				$mysqli->real_escape_string($_POST["agreed_fee"]),
				$mysqli->real_escape_string($_POST["flat_rate"]),
				stripslashes($mysqli->real_escape_string($_POST["suite_number"])),
				stripslashes($mysqli->real_escape_string($_POST["number_ws"])),
				stripslashes($mysqli->real_escape_string($_POST["sqft_taken"])),
				$mysqli->real_escape_string($_POST["move_in_date"]),
				$mysqli->real_escape_string($_POST["closed_date"]),
				stripslashes($mysqli->real_escape_string($_POST["piv_reference"])),
				$mysqli->real_escape_string($_POST["months_signed"]),
				$mysqli->real_escape_string($_POST["billing_freq"]),
				stripslashes($mysqli->real_escape_string($_POST["invoice_total"])),
				stripslashes($mysqli->real_escape_string($_POST["month_1"])),
				stripslashes($mysqli->real_escape_string($_POST["month_2"])),
				stripslashes($mysqli->real_escape_string($_POST["month_3"])),
				stripslashes($mysqli->real_escape_string($_POST["month_4"])),
				stripslashes($mysqli->real_escape_string($_POST["month_5"])),
				stripslashes($mysqli->real_escape_string($_POST["month_6"])),
				stripslashes($mysqli->real_escape_string($_POST["month_7"])),
				stripslashes($mysqli->real_escape_string($_POST["month_8"])),
				stripslashes($mysqli->real_escape_string($_POST["month_9"])),
				stripslashes($mysqli->real_escape_string($_POST["month_10"])),
				stripslashes($mysqli->real_escape_string($_POST["month_11"])),
				stripslashes($mysqli->real_escape_string($_POST["month_12"])),
				stripslashes($mysqli->real_escape_string($_POST["month_13"])),
				stripslashes($mysqli->real_escape_string($_POST["month_14"])),
				stripslashes($mysqli->real_escape_string($_POST["month_15"])),
				stripslashes($mysqli->real_escape_string($_POST["month_16"])),
				stripslashes($mysqli->real_escape_string($_POST["month_17"])),
				stripslashes($mysqli->real_escape_string($_POST["month_18"])),
				stripslashes($mysqli->real_escape_string($_POST["month_19"])),
				stripslashes($mysqli->real_escape_string($_POST["month_20"])),
				stripslashes($mysqli->real_escape_string($_POST["month_21"])),
				stripslashes($mysqli->real_escape_string($_POST["month_22"])),
				stripslashes($mysqli->real_escape_string($_POST["month_23"])),
				stripslashes($mysqli->real_escape_string($_POST["month_24"])),
				stripslashes($mysqli->real_escape_string($_POST["month_25"])),
				stripslashes($mysqli->real_escape_string($_POST["month_26"])),
				stripslashes($mysqli->real_escape_string($_POST["month_27"])),
				stripslashes($mysqli->real_escape_string($_POST["month_28"])),
				stripslashes($mysqli->real_escape_string($_POST["month_29"])),
				stripslashes($mysqli->real_escape_string($_POST["month_30"])),
				stripslashes($mysqli->real_escape_string($_POST["month_31"])),
				stripslashes($mysqli->real_escape_string($_POST["month_32"])),
				stripslashes($mysqli->real_escape_string($_POST["month_33"])),
				stripslashes($mysqli->real_escape_string($_POST["month_34"])),
				stripslashes($mysqli->real_escape_string($_POST["month_35"])),
				stripslashes($mysqli->real_escape_string($_POST["month_36"])),
				stripslashes($mysqli->real_escape_string($_POST["month_37"])),
				stripslashes($mysqli->real_escape_string($_POST["month_38"])),
				stripslashes($mysqli->real_escape_string($_POST["month_39"])),
				stripslashes($mysqli->real_escape_string($_POST["month_40"])),
				stripslashes($mysqli->real_escape_string($_POST["month_41"])),
				stripslashes($mysqli->real_escape_string($_POST["month_42"])),
				stripslashes($mysqli->real_escape_string($_POST["month_43"])),
				stripslashes($mysqli->real_escape_string($_POST["month_44"])),
				stripslashes($mysqli->real_escape_string($_POST["month_45"])),
				stripslashes($mysqli->real_escape_string($_POST["month_46"])),
				stripslashes($mysqli->real_escape_string($_POST["month_47"])),
				stripslashes($mysqli->real_escape_string($_POST["month_48"])),
				stripslashes($mysqli->real_escape_string($_POST["month_49"])),
				stripslashes($mysqli->real_escape_string($_POST["month_50"])),
				stripslashes($mysqli->real_escape_string($_POST["month_51"])),
				stripslashes($mysqli->real_escape_string($_POST["month_52"])),
				stripslashes($mysqli->real_escape_string($_POST["month_53"])),
				stripslashes($mysqli->real_escape_string($_POST["month_54"])),
				stripslashes($mysqli->real_escape_string($_POST["month_55"])),
				stripslashes($mysqli->real_escape_string($_POST["month_56"])),
				stripslashes($mysqli->real_escape_string($_POST["month_57"])),
				stripslashes($mysqli->real_escape_string($_POST["month_58"])),
				stripslashes($mysqli->real_escape_string($_POST["month_59"])),
				stripslashes($mysqli->real_escape_string($_POST["month_60"])),
				stripslashes($mysqli->real_escape_string($_POST["month_61"])),
				stripslashes($mysqli->real_escape_string($_POST["month_62"])),
				stripslashes($mysqli->real_escape_string($_POST["month_63"])),
				stripslashes($mysqli->real_escape_string($_POST["month_64"])),
				stripslashes($mysqli->real_escape_string($_POST["month_65"])),
				stripslashes($mysqli->real_escape_string($_POST["month_66"])),
				stripslashes($mysqli->real_escape_string($_POST["month_67"])),
				stripslashes($mysqli->real_escape_string($_POST["month_68"])),
				stripslashes($mysqli->real_escape_string($_POST["month_69"])),
				stripslashes($mysqli->real_escape_string($_POST["month_70"])),
				stripslashes($mysqli->real_escape_string($_POST["month_71"])),
				stripslashes($mysqli->real_escape_string($_POST["month_72"])),
				stripslashes($mysqli->real_escape_string($_POST["month_73"])),
				stripslashes($mysqli->real_escape_string($_POST["month_74"])),
				stripslashes($mysqli->real_escape_string($_POST["month_75"])),
				stripslashes($mysqli->real_escape_string($_POST["month_76"])),
				stripslashes($mysqli->real_escape_string($_POST["month_77"])),
				stripslashes($mysqli->real_escape_string($_POST["month_78"])),
				stripslashes($mysqli->real_escape_string($_POST["month_79"])),
				stripslashes($mysqli->real_escape_string($_POST["month_80"])),
				stripslashes($mysqli->real_escape_string($_POST["month_81"])),
				stripslashes($mysqli->real_escape_string($_POST["month_82"])),
				stripslashes($mysqli->real_escape_string($_POST["month_83"])),
				stripslashes($mysqli->real_escape_string($_POST["month_84"])),
				stripslashes($mysqli->real_escape_string($_POST["month_85"])),
				stripslashes($mysqli->real_escape_string($_POST["month_86"])),
				stripslashes($mysqli->real_escape_string($_POST["month_87"])),
				stripslashes($mysqli->real_escape_string($_POST["month_88"])),
				stripslashes($mysqli->real_escape_string($_POST["month_89"])),
				stripslashes($mysqli->real_escape_string($_POST["month_90"])),
				stripslashes($mysqli->real_escape_string($_POST["month_91"])),
				stripslashes($mysqli->real_escape_string($_POST["month_92"])),
				stripslashes($mysqli->real_escape_string($_POST["month_93"])),
				stripslashes($mysqli->real_escape_string($_POST["month_94"])),
				stripslashes($mysqli->real_escape_string($_POST["month_95"])),
				stripslashes($mysqli->real_escape_string($_POST["month_96"])),
				stripslashes($mysqli->real_escape_string($_POST["month_97"])),
				stripslashes($mysqli->real_escape_string($_POST["month_98"])),
				stripslashes($mysqli->real_escape_string($_POST["month_99"])),
				stripslashes($mysqli->real_escape_string($_POST["month_100"])),
				stripslashes($mysqli->real_escape_string($_POST["month_101"])),
				stripslashes($mysqli->real_escape_string($_POST["month_102"])),
				stripslashes($mysqli->real_escape_string($_POST["month_103"])),
				stripslashes($mysqli->real_escape_string($_POST["month_104"])),
				stripslashes($mysqli->real_escape_string($_POST["month_105"])),
				stripslashes($mysqli->real_escape_string($_POST["month_106"])),
				stripslashes($mysqli->real_escape_string($_POST["month_107"])),
				stripslashes($mysqli->real_escape_string($_POST["month_108"])),
				stripslashes($mysqli->real_escape_string($_POST["month_109"])),
				stripslashes($mysqli->real_escape_string($_POST["month_110"])),
				stripslashes($mysqli->real_escape_string($_POST["month_111"])),
				stripslashes($mysqli->real_escape_string($_POST["month_112"])),
				stripslashes($mysqli->real_escape_string($_POST["month_113"])),
				stripslashes($mysqli->real_escape_string($_POST["month_114"])),
				stripslashes($mysqli->real_escape_string($_POST["month_115"])),
				stripslashes($mysqli->real_escape_string($_POST["month_116"])),
				stripslashes($mysqli->real_escape_string($_POST["month_117"])),
				stripslashes($mysqli->real_escape_string($_POST["month_118"])),
				stripslashes($mysqli->real_escape_string($_POST["month_119"])),
				stripslashes($mysqli->real_escape_string($_POST["month_120"])),
				$mysqli->real_escape_string($_POST["lead_id"]),
				$mysqli->real_escape_string($_POST["property_id"]),
				$mysqli->real_escape_string($_POST["date_added"])
			) or die($mysqli->error);

			/* Execute the statement */
			$stmt->execute() or die("Error: Could not execute statement");

			/* close statement */
			$stmt->close() or die("Error: Could not close statement");

		} else if ($result->num_rows>0) {
			
			$stmt = $mysqli->prepare("UPDATE invoices SET 
												AGREED_FEE=?, FLAT_RATE=?, SUITE_NUMBER=?, 
												NUMBER_WS=?, SQFT_TAKEN=?, MOVE_IN_DATE=?, CLOSED_DATE=?, PIV_REFERENCE=?, 
												MONTHS_SIGNED=?, BILLING_FREQ=?, INVOICE_TOTAL=?,	
												MONTH_1=?, MONTH_2=?, MONTH_3=?, MONTH_4=?, MONTH_5=?, MONTH_6=?, MONTH_7=?, MONTH_8=?, MONTH_9=?, MONTH_10=?,
												MONTH_11=?, MONTH_12=?, MONTH_13=?, MONTH_14=?, MONTH_15=?, MONTH_16=?, MONTH_17=?, MONTH_18=?, MONTH_19=?, MONTH_20=?, 
												MONTH_21=?, MONTH_22=?, MONTH_23=?, MONTH_24=?, MONTH_25=?, MONTH_26=?, MONTH_27=?, MONTH_28=?, MONTH_29=?, MONTH_30=?,
												MONTH_31=?, MONTH_32=?, MONTH_33=?, MONTH_34=?, MONTH_35=?, MONTH_36=?, MONTH_37=?, MONTH_38=?, MONTH_39=?, MONTH_40=?,
												MONTH_41=?, MONTH_42=?, MONTH_43=?, MONTH_44=?, MONTH_45=?, MONTH_46=?, MONTH_47=?, MONTH_48=?, MONTH_49=?, MONTH_50=?,
												MONTH_51=?, MONTH_52=?, MONTH_53=?, MONTH_54=?, MONTH_55=?, MONTH_56=?, MONTH_57=?, MONTH_58=?, MONTH_59=?, MONTH_60=?,
												MONTH_61=?, MONTH_62=?, MONTH_63=?, MONTH_64=?, MONTH_65=?, MONTH_66=?, MONTH_67=?, MONTH_68=?, MONTH_69=?, MONTH_70=?,
												MONTH_71=?, MONTH_72=?, MONTH_73=?, MONTH_74=?, MONTH_75=?, MONTH_76=?, MONTH_77=?, MONTH_78=?, MONTH_79=?, MONTH_80=?,
												MONTH_81=?, MONTH_82=?, MONTH_83=?, MONTH_84=?, MONTH_85=?, MONTH_86=?, MONTH_87=?, MONTH_88=?, MONTH_89=?, MONTH_90=?,
												MONTH_91=?, MONTH_92=?, MONTH_93=?, MONTH_94=?, MONTH_95=?, MONTH_96=?, MONTH_97=?, MONTH_98=?, MONTH_99=?, MONTH_100=?,
												MONTH_101=?, MONTH_102=?, MONTH_103=?, MONTH_104=?, MONTH_105=?, MONTH_106=?, MONTH_107=?, MONTH_108=?, MONTH_109=?, MONTH_110=?,
												MONTH_111=?, MONTH_112=?, MONTH_113=?, MONTH_114=?, MONTH_115=?, MONTH_116=?, MONTH_117=?, MONTH_118=?, MONTH_119=?, MONTH_120=?,
												PROPERTY_ID=? 
												WHERE LEAD_ID=" . $lead_id) or die($mysqli->error);
			$stmt->bind_param("ddssssssisdssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssi",
			$mysqli->real_escape_string($_POST["agreed_fee"]),
			$mysqli->real_escape_string($_POST["flat_rate"]),
			stripslashes($mysqli->real_escape_string($_POST["suite_number"])),
			stripslashes($mysqli->real_escape_string($_POST["number_ws"])),
			stripslashes($mysqli->real_escape_string($_POST["sqft_taken"])),
			$mysqli->real_escape_string($_POST["move_in_date"]),
			$mysqli->real_escape_string($_POST["closed_date"]),
			stripslashes($mysqli->real_escape_string($_POST["piv_reference"])),
			$mysqli->real_escape_string($_POST["months_signed"]),
			$mysqli->real_escape_string($_POST["billing_freq"]),
			stripslashes($mysqli->real_escape_string($_POST["invoice_total"])),
			stripslashes($mysqli->real_escape_string($_POST["month_1"])),
			stripslashes($mysqli->real_escape_string($_POST["month_2"])),
			stripslashes($mysqli->real_escape_string($_POST["month_3"])),
			stripslashes($mysqli->real_escape_string($_POST["month_4"])),
			stripslashes($mysqli->real_escape_string($_POST["month_5"])),
			stripslashes($mysqli->real_escape_string($_POST["month_6"])),
			stripslashes($mysqli->real_escape_string($_POST["month_7"])),
			stripslashes($mysqli->real_escape_string($_POST["month_8"])),
			stripslashes($mysqli->real_escape_string($_POST["month_9"])),
			stripslashes($mysqli->real_escape_string($_POST["month_10"])),
			stripslashes($mysqli->real_escape_string($_POST["month_11"])),
			stripslashes($mysqli->real_escape_string($_POST["month_12"])),
			stripslashes($mysqli->real_escape_string($_POST["month_13"])),
			stripslashes($mysqli->real_escape_string($_POST["month_14"])),
			stripslashes($mysqli->real_escape_string($_POST["month_15"])),
			stripslashes($mysqli->real_escape_string($_POST["month_16"])),
			stripslashes($mysqli->real_escape_string($_POST["month_17"])),
			stripslashes($mysqli->real_escape_string($_POST["month_18"])),
			stripslashes($mysqli->real_escape_string($_POST["month_19"])),
			stripslashes($mysqli->real_escape_string($_POST["month_20"])),
			stripslashes($mysqli->real_escape_string($_POST["month_21"])),
			stripslashes($mysqli->real_escape_string($_POST["month_22"])),
			stripslashes($mysqli->real_escape_string($_POST["month_23"])),
			stripslashes($mysqli->real_escape_string($_POST["month_24"])),
			stripslashes($mysqli->real_escape_string($_POST["month_25"])),
			stripslashes($mysqli->real_escape_string($_POST["month_26"])),
			stripslashes($mysqli->real_escape_string($_POST["month_27"])),
			stripslashes($mysqli->real_escape_string($_POST["month_28"])),
			stripslashes($mysqli->real_escape_string($_POST["month_29"])),
			stripslashes($mysqli->real_escape_string($_POST["month_30"])),
			stripslashes($mysqli->real_escape_string($_POST["month_31"])),
			stripslashes($mysqli->real_escape_string($_POST["month_32"])),
			stripslashes($mysqli->real_escape_string($_POST["month_33"])),
			stripslashes($mysqli->real_escape_string($_POST["month_34"])),
			stripslashes($mysqli->real_escape_string($_POST["month_35"])),
			stripslashes($mysqli->real_escape_string($_POST["month_36"])),
			stripslashes($mysqli->real_escape_string($_POST["month_37"])),
			stripslashes($mysqli->real_escape_string($_POST["month_38"])),
			stripslashes($mysqli->real_escape_string($_POST["month_39"])),
			stripslashes($mysqli->real_escape_string($_POST["month_40"])),
			stripslashes($mysqli->real_escape_string($_POST["month_41"])),
			stripslashes($mysqli->real_escape_string($_POST["month_42"])),
			stripslashes($mysqli->real_escape_string($_POST["month_43"])),
			stripslashes($mysqli->real_escape_string($_POST["month_44"])),
			stripslashes($mysqli->real_escape_string($_POST["month_45"])),
			stripslashes($mysqli->real_escape_string($_POST["month_46"])),
			stripslashes($mysqli->real_escape_string($_POST["month_47"])),
			stripslashes($mysqli->real_escape_string($_POST["month_48"])),
			stripslashes($mysqli->real_escape_string($_POST["month_49"])),
			stripslashes($mysqli->real_escape_string($_POST["month_50"])),
			stripslashes($mysqli->real_escape_string($_POST["month_51"])),
			stripslashes($mysqli->real_escape_string($_POST["month_52"])),
			stripslashes($mysqli->real_escape_string($_POST["month_53"])),
			stripslashes($mysqli->real_escape_string($_POST["month_54"])),
			stripslashes($mysqli->real_escape_string($_POST["month_55"])),
			stripslashes($mysqli->real_escape_string($_POST["month_56"])),
			stripslashes($mysqli->real_escape_string($_POST["month_57"])),
			stripslashes($mysqli->real_escape_string($_POST["month_58"])),
			stripslashes($mysqli->real_escape_string($_POST["month_59"])),
			stripslashes($mysqli->real_escape_string($_POST["month_60"])),
			stripslashes($mysqli->real_escape_string($_POST["month_61"])),
			stripslashes($mysqli->real_escape_string($_POST["month_62"])),
			stripslashes($mysqli->real_escape_string($_POST["month_63"])),
			stripslashes($mysqli->real_escape_string($_POST["month_64"])),
			stripslashes($mysqli->real_escape_string($_POST["month_65"])),
			stripslashes($mysqli->real_escape_string($_POST["month_66"])),
			stripslashes($mysqli->real_escape_string($_POST["month_67"])),
			stripslashes($mysqli->real_escape_string($_POST["month_68"])),
			stripslashes($mysqli->real_escape_string($_POST["month_69"])),
			stripslashes($mysqli->real_escape_string($_POST["month_70"])),
			stripslashes($mysqli->real_escape_string($_POST["month_71"])),
			stripslashes($mysqli->real_escape_string($_POST["month_72"])),
			stripslashes($mysqli->real_escape_string($_POST["month_73"])),
			stripslashes($mysqli->real_escape_string($_POST["month_74"])),
			stripslashes($mysqli->real_escape_string($_POST["month_75"])),
			stripslashes($mysqli->real_escape_string($_POST["month_76"])),
			stripslashes($mysqli->real_escape_string($_POST["month_77"])),
			stripslashes($mysqli->real_escape_string($_POST["month_78"])),
			stripslashes($mysqli->real_escape_string($_POST["month_79"])),
			stripslashes($mysqli->real_escape_string($_POST["month_80"])),
			stripslashes($mysqli->real_escape_string($_POST["month_81"])),
			stripslashes($mysqli->real_escape_string($_POST["month_82"])),
			stripslashes($mysqli->real_escape_string($_POST["month_83"])),
			stripslashes($mysqli->real_escape_string($_POST["month_84"])),
			stripslashes($mysqli->real_escape_string($_POST["month_85"])),
			stripslashes($mysqli->real_escape_string($_POST["month_86"])),
			stripslashes($mysqli->real_escape_string($_POST["month_87"])),
			stripslashes($mysqli->real_escape_string($_POST["month_88"])),
			stripslashes($mysqli->real_escape_string($_POST["month_89"])),
			stripslashes($mysqli->real_escape_string($_POST["month_90"])),
			stripslashes($mysqli->real_escape_string($_POST["month_91"])),
			stripslashes($mysqli->real_escape_string($_POST["month_92"])),
			stripslashes($mysqli->real_escape_string($_POST["month_93"])),
			stripslashes($mysqli->real_escape_string($_POST["month_94"])),
			stripslashes($mysqli->real_escape_string($_POST["month_95"])),
			stripslashes($mysqli->real_escape_string($_POST["month_96"])),
			stripslashes($mysqli->real_escape_string($_POST["month_97"])),
			stripslashes($mysqli->real_escape_string($_POST["month_98"])),
			stripslashes($mysqli->real_escape_string($_POST["month_99"])),
			stripslashes($mysqli->real_escape_string($_POST["month_100"])),
			stripslashes($mysqli->real_escape_string($_POST["month_101"])),
			stripslashes($mysqli->real_escape_string($_POST["month_102"])),
			stripslashes($mysqli->real_escape_string($_POST["month_103"])),
			stripslashes($mysqli->real_escape_string($_POST["month_104"])),
			stripslashes($mysqli->real_escape_string($_POST["month_105"])),
			stripslashes($mysqli->real_escape_string($_POST["month_106"])),
			stripslashes($mysqli->real_escape_string($_POST["month_107"])),
			stripslashes($mysqli->real_escape_string($_POST["month_108"])),
			stripslashes($mysqli->real_escape_string($_POST["month_109"])),
			stripslashes($mysqli->real_escape_string($_POST["month_110"])),
			stripslashes($mysqli->real_escape_string($_POST["month_111"])),
			stripslashes($mysqli->real_escape_string($_POST["month_112"])),
			stripslashes($mysqli->real_escape_string($_POST["month_113"])),
			stripslashes($mysqli->real_escape_string($_POST["month_114"])),
			stripslashes($mysqli->real_escape_string($_POST["month_115"])),
			stripslashes($mysqli->real_escape_string($_POST["month_116"])),
			stripslashes($mysqli->real_escape_string($_POST["month_117"])),
			stripslashes($mysqli->real_escape_string($_POST["month_118"])),
			stripslashes($mysqli->real_escape_string($_POST["month_119"])),
			stripslashes($mysqli->real_escape_string($_POST["month_120"])),
			$mysqli->real_escape_string($_POST["property_id"])
			) or die($mysqli->error);

			/* Execute the statement */
			$stmt->execute() or die("Error: Could not execute statement");

			/* close statement */
			$stmt->close() or die("Error: Could not close statement");

		}
		header("Location: invoiceDetails.php?lead_id=" . $lead_id);
	}
	else	{
		echo "Error: Lead ID is required";
	}
	
} else if (isset($_POST["sisubmit"]) && $lead_id!=null) {
	
	if ($lead_id != '') {
	
		$query = "UPDATE invoices SET date_submitted=CURRENT_TIMESTAMP WHERE LEAD_ID=" . $lead_id;
		$result = $mysqli->query($query)	or die(mysql_error());
		header("Location: invoiceDetails.php?lead_id=" . $lead_id);
		
	} else	{
		echo "Error: Lead ID is required";
	}

} else {
// if the form hasn't been submitted, display the form)
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>OfficeNegotiator.com - Dashboard</title>
<link rel="icon" href="http://www.officenegotiator.com/favicon.ico" type="image/x-icon" />
<link rel="shortcut icon" href="http://www.officenegotiator.com/favicon.ico" type="image/x-icon" />
<link rel="stylesheet" type="text/css" href="css/dashboard.css"/>
<link rel="stylesheet" type="text/css" href="css/dashboard_menu.css"/>
<link rel="stylesheet" type="text/css" href="js/tigra_calendar/calendar.css">
<script type="text/javascript" src="js/tigra_calendar/calendar_db.js"></script>
<script type="text/javascript" src="js/site.js"></script>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
<script type="text/javascript">



function selectYears(months) {
	if (months <= 12) {
		$(".year1").css({"display":"table-cell"});
		$(".year2").css({"display":"none"});
		$(".year3").css({"display":"none"});
		$(".year4").css({"display":"none"});
		$(".year5").css({"display":"none"});
		$(".year6").css({"display":"none"});
		$(".year7").css({"display":"none"});
		$(".year8").css({"display":"none"});
		$(".year9").css({"display":"none"});
		$(".year10").css({"display":"none"});
		return;
	}
	if (months <= 24) {
		$(".year1").css({"display":"table-cell"});
		$(".year2").css({"display":"table-cell"});
		$(".year3").css({"display":"none"});
		$(".year4").css({"display":"none"});
		$(".year5").css({"display":"none"});
		$(".year6").css({"display":"none"});
		$(".year7").css({"display":"none"});
		$(".year8").css({"display":"none"});
		$(".year9").css({"display":"none"});
		$(".year10").css({"display":"none"});
		return;
	}
	if (months <= 36) {
		$(".year1").css({"display":"table-cell"});
		$(".year2").css({"display":"table-cell"});
		$(".year3").css({"display":"table-cell"});
		$(".year4").css({"display":"none"});
		$(".year5").css({"display":"none"});
		$(".year6").css({"display":"none"});
		$(".year7").css({"display":"none"});
		$(".year8").css({"display":"none"});
		$(".year9").css({"display":"none"});
		$(".year10").css({"display":"none"});
		return;
	}
	if (months <= 48) {
		$(".year1").css({"display":"table-cell"});
		$(".year2").css({"display":"table-cell"});
		$(".year3").css({"display":"table-cell"});
		$(".year4").css({"display":"table-cell"});
		$(".year5").css({"display":"none"});
		$(".year6").css({"display":"none"});
		$(".year7").css({"display":"none"});
		$(".year8").css({"display":"none"});
		$(".year9").css({"display":"none"});
		$(".year10").css({"display":"none"});
		return;
	}
	if (months <= 60) {
		$(".year1").css({"display":"table-cell"});
		$(".year2").css({"display":"table-cell"});
		$(".year3").css({"display":"table-cell"});
		$(".year4").css({"display":"table-cell"});
		$(".year5").css({"display":"table-cell"});
		$(".year6").css({"display":"none"});
		$(".year7").css({"display":"none"});
		$(".year8").css({"display":"none"});
		$(".year9").css({"display":"none"});
		$(".year10").css({"display":"none"});
		return;
	}
	if (months <= 72) {
		$(".year1").css({"display":"table-cell"});
		$(".year2").css({"display":"table-cell"});
		$(".year3").css({"display":"table-cell"});
		$(".year4").css({"display":"table-cell"});
		$(".year5").css({"display":"table-cell"});
		$(".year6").css({"display":"table-cell"});
		$(".year7").css({"display":"none"});
		$(".year8").css({"display":"none"});
		$(".year9").css({"display":"none"});
		$(".year10").css({"display":"none"});
		return;
	}
	if (months <= 84) {
		$(".year1").css({"display":"table-cell"});
		$(".year2").css({"display":"table-cell"});
		$(".year3").css({"display":"table-cell"});
		$(".year4").css({"display":"table-cell"});
		$(".year5").css({"display":"table-cell"});
		$(".year6").css({"display":"table-cell"});
		$(".year7").css({"display":"table-cell"});
		$(".year8").css({"display":"none"});
		$(".year9").css({"display":"none"});
		$(".year10").css({"display":"none"});
		return;
	}
	if (months <= 96) {
		$(".year1").css({"display":"table-cell"});
		$(".year2").css({"display":"table-cell"});
		$(".year3").css({"display":"table-cell"});
		$(".year4").css({"display":"table-cell"});
		$(".year5").css({"display":"table-cell"});
		$(".year6").css({"display":"table-cell"});
		$(".year7").css({"display":"table-cell"});
		$(".year8").css({"display":"table-cell"});
		$(".year9").css({"display":"none"});
		$(".year10").css({"display":"none"});
		return;
	}
	if (months <= 108) {
		$(".year1").css({"display":"table-cell"});
		$(".year2").css({"display":"table-cell"});
		$(".year3").css({"display":"table-cell"});
		$(".year4").css({"display":"table-cell"});
		$(".year5").css({"display":"table-cell"});
		$(".year6").css({"display":"table-cell"});
		$(".year7").css({"display":"table-cell"});
		$(".year8").css({"display":"table-cell"});
		$(".year9").css({"display":"table-cell"});
		$(".year10").css({"display":"none"});
		return;
	}
	if (months <= 120) {
		$(".year1").css({"display":"table-cell"});
		$(".year2").css({"display":"table-cell"});
		$(".year3").css({"display":"table-cell"});
		$(".year4").css({"display":"table-cell"});
		$(".year5").css({"display":"table-cell"});
		$(".year6").css({"display":"table-cell"});
		$(".year7").css({"display":"table-cell"});
		$(".year8").css({"display":"table-cell"});
		$(".year9").css({"display":"table-cell"});
		$(".year10").css({"display":"table-cell"});
		return;
	}
}

function sameRate(rate) {
	if (rate!="") {
		resetRate();
		$("input[id^=month]:visible").each(function() {
			$(this).val(rate);
		});
		$("#same_rate").val("");
		updateTotal();
	}
}

function resetRate() {
	$("input[id^=month]").each(function() {
		$(this).val("0");
	});
	$("#invoice_total").val("0");
}

function updateTotal() {
	var total = 0;
	$("input[id^=month]:visible").each(function() {
		if($(this).val()!='') {
			total += parseFloat($(this).val());
		} else {
			$(this).val("0");
		}
	});
	var agreed = parseFloat($("#agreed_fee").val()) / 100;
	var invoice = total * agreed;
	$("#invoice_total").val(invoice.toFixed(2));
	$("#deal_total").val(total.toFixed(2));
}

function callHelper(uri) {
    if (uri=="") {
      return;
		}
    if (window.XMLHttpRequest) { // code for IE7+, Firefox, Chrome, Opera, Safari
			xmlhttp=new XMLHttpRequest();
		} else { // code for IE6, IE5
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange=function() {
			if (xmlhttp.readyState==4 && xmlhttp.status==200) {
				var serverResponse = xmlhttp.responseText;
				if (serverResponse!="") {
					if(uri.indexOf("getPropertyInfo")!=-1) {
						if(serverResponse.indexOf("Error:")!=-1) {
							alert(serverResponse);
						} else {
							if (serverResponse=="Not Found") {
								$("#agreed_fee").attr("value","");
								$("#flat_rate").attr("value","");
							} else {
								var json = JSON.parse(serverResponse);
								$("#agreed_fee").attr("value",json.AGREED_FEE);
								$("#flat_rate").attr("value",json.FLAT_RATE);
							}
						}
					}
				}
			}
		};
    xmlhttp.open("GET", uri);
    xmlhttp.send();
}

function checkEnter(e, form){
	var key = e.keyCode || e.which;
	if (key == 13){
		sameRate(document.getElementById('same_rate').value);
	}
}

$(document).ready(function() {
	selectYears($("#months_signed").val());
	updateTotal();
});

</script>
</head>
<body>
<div id="header"><?php require "header.inc.php"; ?></div>
<div id="menu"><?php require "menu.inc.php"; ?></div>
<div id="content">
<!-- Begin Content-->

<?php
if ($lead_id!=null) {
	
$inv = null;

if($lead_id!=null) {
	$result = $mysqli->query("SELECT * FROM invoices WHERE lead_id=" . $lead_id)
		or die(mysqli_error());
	$inv = mysqli_fetch_array($result);
}
?>
<div align="center">
<form name="invoice_form" method="post" action="<?=$PHP_SELF?>" onshange="updateTotal();">
<input type="hidden" name="date_added" value="<?=date("Y-m-d H:i:s")?>">
<input type="hidden" name="lead_id" value="<?=$lead_id?>">
<input class="button" type="submit" name="submit" value="Save Changes" <?if($inv['DATE_SUBMITTED']!=null) echo "disabled=\"disabled\""?>/>
<input class="button" type="submit" name="sisubmit" value="Submit Invoice" <?if($inv['DATE_SUBMITTED']!=null) echo "disabled=\"disabled\""?>/>

<table class="input" width="100%">
<tr>
<th valign="bottom">
<a href="editLead.php?lead_id=<?=$lead_id?>">Client Information</a>&nbsp;|&nbsp;
<a href="searchReport.php?lead_id=<?=$lead_id?>">Search Report</a>&nbsp;|&nbsp;
<a href="invoiceDetails.php?lead_id=<?=$lead_id?>">Invoice Details</a>
</th>
<th style="text-align:right;">
<?php if ($lead_id!=null) { ?>
Date Submitted: <?php if ($inv['DATE_SUBMITTED']!=null) echo date("Y-m-d h:i A", strtotime($inv['DATE_SUBMITTED'])); else echo "Invoice Not Submitted"; ?><br />
Last Update: <?=date("Y-m-d h:i A", strtotime($inv['LAST_UPDATED']))?>
<?php } ?>
</th>
</tr>

<tr><td colspan="2">
<table class="input">

<tr>
<td align="right">Location Selected:</td>
<td align="left">
<select id="property_id" name="property_id" onchange="callHelper('invoiceDetailsHelper.php?action=getPropertyInfo&property_id=' + this.value)">
<option value=""></option>
<?php
		$result = $mysqli->query("SELECT * FROM search_report JOIN properties ON search_report.property_id=properties.property_id WHERE lead_id=" . $lead_id . " ORDER BY properties.CENTER_NAME ASC") or die(mysql_error());
		while($row = mysqli_fetch_array($result)){
  		foreach($row AS $key => $value) {
				$row[$key] = stripslashes($value);
			}
?>
<option value="<?=$row['PROPERTY_ID']?>" <?if($row['PROPERTY_ID']==$inv['PROPERTY_ID']) echo "selected=\"selected\""?>><?=$row['CENTER_NAME']?></option>
<?php
		}
?>
</select>
</td>
<td align="right">Agreed Fee (%):</td>
<td align="left">
<input id="agreed_fee" name="agreed_fee" size="15" value="<?=stripslashes($inv['AGREED_FEE'])?>" <?php if (!$session->isAdmin()) echo "readonly='true' style='color:#777'" ?> onchange="updateTotal();" />
</td>
<td align="right">Flat Rate ($):</td>
<td align="left">
<input id="flat_rate" name="flat_rate" size="15" value="<?=stripslashes($inv['FLAT_RATE'])?>" <?php if (!$session->isAdmin()) echo "readonly='true' style='color:#777'" ?> />
</td>
</tr>

<tr>
<td align="right">Suite Number:</td>
<td align="left">
<input id="suite_number" name="suite_number" type="text" size="10" value="<?=stripslashes($inv['SUITE_NUMBER'])?>" />
</td>
<td align="right">Workstations:</td>
<td align="left">
<input id="number_ws" name="number_ws" type="text" size="10" value="<?=stripslashes($inv['NUMBER_WS'])?>" />
</td>
<td align="right">Sq/Ft Taken:</td>
<td align="left">
<input id="sqft_taken" name="sqft_taken" type="text" size="10" value="<?=stripslashes($inv['SQFT_TAKEN'])?>" />
</td>
</tr>

<tr>
<td align="right">Move In Date:</td>
<td align="left" valign="top">
<input name="move_in_date" id="move_in_date" size="10" value="<?php if ($inv['MOVE_IN_DATE']=="0000-00-00") echo ""; else echo $inv['MOVE_IN_DATE'];?>" />
<script type="text/javascript">
	var t_cal = new tcal ({
		'controlname': 'move_in_date'
	});
</script>
</td>
<td align="right">Closed Date:</td>
<td align="left" valign="top">
<input name="closed_date" id="closed_date" size="10" value="<?php if ($inv['CLOSED_DATE']=="0000-00-00") echo ""; else echo $inv['CLOSED_DATE'];?>" />
<script type="text/javascript">
	var t_cal = new tcal ({
		'controlname': 'closed_date'
	});
</script>
</td>
<td align="right">PIV# or Reference#:</td>
<td align="left">
<input id="piv_reference" name="piv_reference" type="text" size="20" value="<?=stripslashes($inv['PIV_REFERENCE'])?>" />
</td>
</tr>

<tr>
<td align="right">Months Signed:</td>
<td align="left">
<select id="months_signed" name="months_signed" onchange="selectYears(this.value);updateTotal();">
<option value="1" <?if($inv['MONTHS_SIGNED']=="1") echo "selected=\"selected\""?>>1</option>
<option value="2" <?if($inv['MONTHS_SIGNED']=="2") echo "selected=\"selected\""?>>2</option>
<option value="3" <?if($inv['MONTHS_SIGNED']=="3") echo "selected=\"selected\""?>>3</option>
<option value="4" <?if($inv['MONTHS_SIGNED']=="4") echo "selected=\"selected\""?>>4</option>
<option value="5" <?if($inv['MONTHS_SIGNED']=="5") echo "selected=\"selected\""?>>5</option>
<option value="6" <?if($inv['MONTHS_SIGNED']=="6") echo "selected=\"selected\""?>>6</option>
<option value="7" <?if($inv['MONTHS_SIGNED']=="7") echo "selected=\"selected\""?>>7</option>
<option value="8" <?if($inv['MONTHS_SIGNED']=="8") echo "selected=\"selected\""?>>8</option>
<option value="9" <?if($inv['MONTHS_SIGNED']=="9") echo "selected=\"selected\""?>>9</option>
<option value="10" <?if($inv['MONTHS_SIGNED']=="10") echo "selected=\"selected\""?>>10</option>
<option value="11" <?if($inv['MONTHS_SIGNED']=="11") echo "selected=\"selected\""?>>11</option>
<option value="12" <?if($inv['MONTHS_SIGNED']=="12") echo "selected=\"selected\""?>>12</option>
<option value="13" <?if($inv['MONTHS_SIGNED']=="13") echo "selected=\"selected\""?>>13</option>
<option value="14" <?if($inv['MONTHS_SIGNED']=="14") echo "selected=\"selected\""?>>14</option>
<option value="15" <?if($inv['MONTHS_SIGNED']=="15") echo "selected=\"selected\""?>>15</option>
<option value="16" <?if($inv['MONTHS_SIGNED']=="16") echo "selected=\"selected\""?>>16</option>
<option value="17" <?if($inv['MONTHS_SIGNED']=="17") echo "selected=\"selected\""?>>17</option>
<option value="18" <?if($inv['MONTHS_SIGNED']=="18") echo "selected=\"selected\""?>>18</option>
<option value="19" <?if($inv['MONTHS_SIGNED']=="19") echo "selected=\"selected\""?>>19</option>
<option value="20" <?if($inv['MONTHS_SIGNED']=="20") echo "selected=\"selected\""?>>20</option>
<option value="21" <?if($inv['MONTHS_SIGNED']=="21") echo "selected=\"selected\""?>>21</option>
<option value="22" <?if($inv['MONTHS_SIGNED']=="22") echo "selected=\"selected\""?>>22</option>
<option value="23" <?if($inv['MONTHS_SIGNED']=="23") echo "selected=\"selected\""?>>23</option>
<option value="24" <?if($inv['MONTHS_SIGNED']=="24") echo "selected=\"selected\""?>>24</option>
<option value="25" <?if($inv['MONTHS_SIGNED']=="25") echo "selected=\"selected\""?>>25</option>
<option value="26" <?if($inv['MONTHS_SIGNED']=="26") echo "selected=\"selected\""?>>26</option>
<option value="27" <?if($inv['MONTHS_SIGNED']=="27") echo "selected=\"selected\""?>>27</option>
<option value="28" <?if($inv['MONTHS_SIGNED']=="28") echo "selected=\"selected\""?>>28</option>
<option value="29" <?if($inv['MONTHS_SIGNED']=="29") echo "selected=\"selected\""?>>29</option>
<option value="30" <?if($inv['MONTHS_SIGNED']=="30") echo "selected=\"selected\""?>>30</option>
<option value="31" <?if($inv['MONTHS_SIGNED']=="31") echo "selected=\"selected\""?>>31</option>
<option value="32" <?if($inv['MONTHS_SIGNED']=="32") echo "selected=\"selected\""?>>32</option>
<option value="33" <?if($inv['MONTHS_SIGNED']=="33") echo "selected=\"selected\""?>>33</option>
<option value="34" <?if($inv['MONTHS_SIGNED']=="34") echo "selected=\"selected\""?>>34</option>
<option value="35" <?if($inv['MONTHS_SIGNED']=="35") echo "selected=\"selected\""?>>35</option>
<option value="36" <?if($inv['MONTHS_SIGNED']=="36") echo "selected=\"selected\""?>>36</option>
<option value="37" <?if($inv['MONTHS_SIGNED']=="37") echo "selected=\"selected\""?>>37</option>
<option value="38" <?if($inv['MONTHS_SIGNED']=="38") echo "selected=\"selected\""?>>38</option>
<option value="39" <?if($inv['MONTHS_SIGNED']=="39") echo "selected=\"selected\""?>>39</option>
<option value="40" <?if($inv['MONTHS_SIGNED']=="40") echo "selected=\"selected\""?>>40</option>
<option value="41" <?if($inv['MONTHS_SIGNED']=="41") echo "selected=\"selected\""?>>41</option>
<option value="42" <?if($inv['MONTHS_SIGNED']=="42") echo "selected=\"selected\""?>>42</option>
<option value="43" <?if($inv['MONTHS_SIGNED']=="43") echo "selected=\"selected\""?>>43</option>
<option value="44" <?if($inv['MONTHS_SIGNED']=="44") echo "selected=\"selected\""?>>44</option>
<option value="45" <?if($inv['MONTHS_SIGNED']=="45") echo "selected=\"selected\""?>>45</option>
<option value="46" <?if($inv['MONTHS_SIGNED']=="46") echo "selected=\"selected\""?>>46</option>
<option value="47" <?if($inv['MONTHS_SIGNED']=="47") echo "selected=\"selected\""?>>47</option>
<option value="48" <?if($inv['MONTHS_SIGNED']=="48") echo "selected=\"selected\""?>>48</option>
<option value="49" <?if($inv['MONTHS_SIGNED']=="49") echo "selected=\"selected\""?>>49</option>
<option value="50" <?if($inv['MONTHS_SIGNED']=="50") echo "selected=\"selected\""?>>50</option>
<option value="51" <?if($inv['MONTHS_SIGNED']=="51") echo "selected=\"selected\""?>>51</option>
<option value="52" <?if($inv['MONTHS_SIGNED']=="52") echo "selected=\"selected\""?>>52</option>
<option value="53" <?if($inv['MONTHS_SIGNED']=="53") echo "selected=\"selected\""?>>53</option>
<option value="54" <?if($inv['MONTHS_SIGNED']=="54") echo "selected=\"selected\""?>>54</option>
<option value="55" <?if($inv['MONTHS_SIGNED']=="55") echo "selected=\"selected\""?>>55</option>
<option value="56" <?if($inv['MONTHS_SIGNED']=="56") echo "selected=\"selected\""?>>56</option>
<option value="57" <?if($inv['MONTHS_SIGNED']=="57") echo "selected=\"selected\""?>>57</option>
<option value="58" <?if($inv['MONTHS_SIGNED']=="58") echo "selected=\"selected\""?>>58</option>
<option value="59" <?if($inv['MONTHS_SIGNED']=="59") echo "selected=\"selected\""?>>59</option>
<option value="60" <?if($inv['MONTHS_SIGNED']=="60") echo "selected=\"selected\""?>>60</option>
<option value="61" <?if($inv['MONTHS_SIGNED']=="61") echo "selected=\"selected\""?>>61</option>
<option value="62" <?if($inv['MONTHS_SIGNED']=="62") echo "selected=\"selected\""?>>62</option>
<option value="63" <?if($inv['MONTHS_SIGNED']=="63") echo "selected=\"selected\""?>>63</option>
<option value="64" <?if($inv['MONTHS_SIGNED']=="64") echo "selected=\"selected\""?>>64</option>
<option value="65" <?if($inv['MONTHS_SIGNED']=="65") echo "selected=\"selected\""?>>65</option>
<option value="66" <?if($inv['MONTHS_SIGNED']=="66") echo "selected=\"selected\""?>>66</option>
<option value="67" <?if($inv['MONTHS_SIGNED']=="67") echo "selected=\"selected\""?>>67</option>
<option value="68" <?if($inv['MONTHS_SIGNED']=="68") echo "selected=\"selected\""?>>68</option>
<option value="69" <?if($inv['MONTHS_SIGNED']=="69") echo "selected=\"selected\""?>>69</option>
<option value="70" <?if($inv['MONTHS_SIGNED']=="70") echo "selected=\"selected\""?>>70</option>
<option value="71" <?if($inv['MONTHS_SIGNED']=="71") echo "selected=\"selected\""?>>71</option>
<option value="72" <?if($inv['MONTHS_SIGNED']=="72") echo "selected=\"selected\""?>>72</option>
<option value="73" <?if($inv['MONTHS_SIGNED']=="73") echo "selected=\"selected\""?>>73</option>
<option value="74" <?if($inv['MONTHS_SIGNED']=="74") echo "selected=\"selected\""?>>74</option>
<option value="75" <?if($inv['MONTHS_SIGNED']=="75") echo "selected=\"selected\""?>>75</option>
<option value="76" <?if($inv['MONTHS_SIGNED']=="76") echo "selected=\"selected\""?>>76</option>
<option value="77" <?if($inv['MONTHS_SIGNED']=="77") echo "selected=\"selected\""?>>77</option>
<option value="78" <?if($inv['MONTHS_SIGNED']=="78") echo "selected=\"selected\""?>>78</option>
<option value="79" <?if($inv['MONTHS_SIGNED']=="79") echo "selected=\"selected\""?>>79</option>
<option value="80" <?if($inv['MONTHS_SIGNED']=="80") echo "selected=\"selected\""?>>80</option>
<option value="81" <?if($inv['MONTHS_SIGNED']=="81") echo "selected=\"selected\""?>>81</option>
<option value="82" <?if($inv['MONTHS_SIGNED']=="82") echo "selected=\"selected\""?>>82</option>
<option value="83" <?if($inv['MONTHS_SIGNED']=="83") echo "selected=\"selected\""?>>83</option>
<option value="84" <?if($inv['MONTHS_SIGNED']=="84") echo "selected=\"selected\""?>>84</option>
<option value="85" <?if($inv['MONTHS_SIGNED']=="85") echo "selected=\"selected\""?>>85</option>
<option value="86" <?if($inv['MONTHS_SIGNED']=="86") echo "selected=\"selected\""?>>86</option>
<option value="87" <?if($inv['MONTHS_SIGNED']=="87") echo "selected=\"selected\""?>>87</option>
<option value="88" <?if($inv['MONTHS_SIGNED']=="88") echo "selected=\"selected\""?>>88</option>
<option value="89" <?if($inv['MONTHS_SIGNED']=="89") echo "selected=\"selected\""?>>89</option>
<option value="90" <?if($inv['MONTHS_SIGNED']=="90") echo "selected=\"selected\""?>>90</option>
<option value="91" <?if($inv['MONTHS_SIGNED']=="91") echo "selected=\"selected\""?>>91</option>
<option value="92" <?if($inv['MONTHS_SIGNED']=="92") echo "selected=\"selected\""?>>92</option>
<option value="93" <?if($inv['MONTHS_SIGNED']=="93") echo "selected=\"selected\""?>>93</option>
<option value="94" <?if($inv['MONTHS_SIGNED']=="94") echo "selected=\"selected\""?>>94</option>
<option value="95" <?if($inv['MONTHS_SIGNED']=="95") echo "selected=\"selected\""?>>95</option>
<option value="96" <?if($inv['MONTHS_SIGNED']=="96") echo "selected=\"selected\""?>>96</option>
<option value="97" <?if($inv['MONTHS_SIGNED']=="97") echo "selected=\"selected\""?>>97</option>
<option value="98" <?if($inv['MONTHS_SIGNED']=="98") echo "selected=\"selected\""?>>98</option>
<option value="99" <?if($inv['MONTHS_SIGNED']=="99") echo "selected=\"selected\""?>>99</option>
<option value="100" <?if($inv['MONTHS_SIGNED']=="100") echo "selected=\"selected\""?>>100</option>
<option value="101" <?if($inv['MONTHS_SIGNED']=="101") echo "selected=\"selected\""?>>101</option>
<option value="102" <?if($inv['MONTHS_SIGNED']=="102") echo "selected=\"selected\""?>>102</option>
<option value="103" <?if($inv['MONTHS_SIGNED']=="103") echo "selected=\"selected\""?>>103</option>
<option value="104" <?if($inv['MONTHS_SIGNED']=="104") echo "selected=\"selected\""?>>104</option>
<option value="105" <?if($inv['MONTHS_SIGNED']=="105") echo "selected=\"selected\""?>>105</option>
<option value="106" <?if($inv['MONTHS_SIGNED']=="106") echo "selected=\"selected\""?>>106</option>
<option value="107" <?if($inv['MONTHS_SIGNED']=="107") echo "selected=\"selected\""?>>107</option>
<option value="108" <?if($inv['MONTHS_SIGNED']=="108") echo "selected=\"selected\""?>>108</option>
<option value="109" <?if($inv['MONTHS_SIGNED']=="109") echo "selected=\"selected\""?>>109</option>
<option value="110" <?if($inv['MONTHS_SIGNED']=="110") echo "selected=\"selected\""?>>110</option>
<option value="111" <?if($inv['MONTHS_SIGNED']=="111") echo "selected=\"selected\""?>>111</option>
<option value="112" <?if($inv['MONTHS_SIGNED']=="112") echo "selected=\"selected\""?>>112</option>
<option value="113" <?if($inv['MONTHS_SIGNED']=="113") echo "selected=\"selected\""?>>113</option>
<option value="114" <?if($inv['MONTHS_SIGNED']=="114") echo "selected=\"selected\""?>>114</option>
<option value="115" <?if($inv['MONTHS_SIGNED']=="115") echo "selected=\"selected\""?>>115</option>
<option value="116" <?if($inv['MONTHS_SIGNED']=="116") echo "selected=\"selected\""?>>116</option>
<option value="117" <?if($inv['MONTHS_SIGNED']=="117") echo "selected=\"selected\""?>>117</option>
<option value="118" <?if($inv['MONTHS_SIGNED']=="118") echo "selected=\"selected\""?>>118</option>
<option value="119" <?if($inv['MONTHS_SIGNED']=="119") echo "selected=\"selected\""?>>119</option>
<option value="120" <?if($inv['MONTHS_SIGNED']=="120") echo "selected=\"selected\""?>>120</option>
</select>
</td>
<td align="right">Billing Frequency:</td>
<td align="left">
<select id="billing_freq" name="billing_freq">
<option value="Up-Front" <?if($inv['BILLING_FREQ']=="Up-Front") echo "selected=\"selected\""?>>Up-Front</option>
<option value="Quarterly" <?if($inv['BILLING_FREQ']=="Quarterly") echo "selected=\"selected\""?>>Quarterly</option>
<option value="Semi-Annually" <?if($inv['BILLING_FREQ']=="Semi-Annually") echo "selected=\"selected\""?>>Semi-Annually</option>
<option value="Annually" <?if($inv['BILLING_FREQ']=="Annually") echo "selected=\"selected\""?>>Annually</option>
</select>
</td>
</tr>

<tr>
<td align="right">Deal Total:</td>
<td align="left">
<input class="orange" id="deal_total" name="deal_total" size="15" value="" />
</td>
<td align="right">Invoice Total:</td>
<td align="left">
<input class="Good" id="invoice_total" name="invoice_total" size="15" value="<?=stripslashes($inv['INVOICE_TOTAL'])?>" />
</td>
</tr>

<tr>
<td colspan="2"><br /></td>
</tr>

<tr>
<td align="right">Same Rate:</td>
<td align="left" colspan="5">
<input id="same_rate" name="same_rate" type="text" size="15" onkeypress="checkEnter(event, this.form)" />
<input class="button" type="button" value="Apply" onclick="sameRate(document.getElementById('same_rate').value);" />
<input class="button" type="button" value="Reset" onclick="resetRate();" />
</td>
</tr>

</td></tr>
</table>

<tr><td colspan="6">
<br />
<table class="input">

<tr>
<td class="year1" align="right">M1:</td>
<td class="year1"><input id="month_1" name="month_1" type="text" size="10" value="<?php if ($inv['MONTH_1']==null) echo "0"; else echo $inv['MONTH_1'];?>" onchange="updateTotal();" /></td>
<td class="year2" align="right">M13:</td>
<td class="year2"><input id="month_13" name="month_13" type="text" size="10" value="<?php if ($inv['MONTH_13']==null) echo "0"; else echo $inv['MONTH_13'];?>" onchange="updateTotal();" /></td>
<td class="year3" align="right">M25:</td>
<td class="year3"><input id="month_25" name="month_25" type="text" size="10" value="<?php if ($inv['MONTH_25']==null) echo "0"; else echo $inv['MONTH_25'];?>" onchange="updateTotal();" /></td>
<td class="year4" align="right">M37:</td>
<td class="year4"><input id="month_37" name="month_37" type="text" size="10" value="<?php if ($inv['MONTH_37']==null) echo "0"; else echo $inv['MONTH_37'];?>" onchange="updateTotal();" /></td>
<td class="year5" align="right">M49:</td>
<td class="year5"><input id="month_49" name="month_49" type="text" size="10" value="<?php if ($inv['MONTH_49']==null) echo "0"; else echo $inv['MONTH_49'];?>" onchange="updateTotal();" /></td>
<td class="year6" align="right">M61:</td>
<td class="year6"><input id="month_61" name="month_61" type="text" size="10" value="<?php if ($inv['MONTH_61']==null) echo "0"; else echo $inv['MONTH_61'];?>" onchange="updateTotal();" /></td>
<td class="year7" align="right">M73:</td>
<td class="year7"><input id="month_73" name="month_73" type="text" size="10" value="<?php if ($inv['MONTH_73']==null) echo "0"; else echo $inv['MONTH_73'];?>" onchange="updateTotal();" /></td>
<td class="year8" align="right">M85:</td>
<td class="year8"><input id="month_85" name="month_85" type="text" size="10" value="<?php if ($inv['MONTH_85']==null) echo "0"; else echo $inv['MONTH_85'];?>" onchange="updateTotal();" /></td>
<td class="year9" align="right">M97:</td>
<td class="year9"><input id="month_97" name="month_97" type="text" size="10" value="<?php if ($inv['MONTH_97']==null) echo "0"; else echo $inv['MONTH_97'];?>" onchange="updateTotal();" /></td>
<td class="year10" align="right">M109:</td>
<td class="year10"><input id="month_109" name="month_109" type="text" size="10" value="<?php if ($inv['MONTH_109']==null) echo "0"; else echo $inv['MONTH_109'];?>" onchange="updateTotal();" /></td>
</tr>

<tr>
<td class="year1" align="right">M2:</td>
<td class="year1"><input id="month_2" name="month_2" type="text" size="10" value="<?php if ($inv['MONTH_2']==null) echo "0"; else echo $inv['MONTH_2'];?>" onchange="updateTotal();" /></td>
<td class="year2" align="right">M14:</td>
<td class="year2"><input id="month_14" name="month_14" type="text" size="10" value="<?php if ($inv['MONTH_14']==null) echo "0"; else echo $inv['MONTH_14'];?>" onchange="updateTotal();" /></td>
<td class="year3" align="right">M26:</td>
<td class="year3"><input id="month_26" name="month_26" type="text" size="10" value="<?php if ($inv['MONTH_26']==null) echo "0"; else echo $inv['MONTH_26'];?>" onchange="updateTotal();" /></td>
<td class="year4" align="right">M38:</td>
<td class="year4"><input id="month_38" name="month_38" type="text" size="10" value="<?php if ($inv['MONTH_38']==null) echo "0"; else echo $inv['MONTH_38'];?>" onchange="updateTotal();" /></td>
<td class="year5" align="right">M50:</td>
<td class="year5"><input id="month_50" name="month_50" type="text" size="10" value="<?php if ($inv['MONTH_50']==null) echo "0"; else echo $inv['MONTH_50'];?>" onchange="updateTotal();" /></td>
<td class="year6" align="right">M62:</td>
<td class="year6"><input id="month_62" name="month_62" type="text" size="10" value="<?php if ($inv['MONTH_62']==null) echo "0"; else echo $inv['MONTH_62'];?>" onchange="updateTotal();" /></td>
<td class="year7" align="right">M74:</td>
<td class="year7"><input id="month_74" name="month_74" type="text" size="10" value="<?php if ($inv['MONTH_74']==null) echo "0"; else echo $inv['MONTH_74'];?>" onchange="updateTotal();" /></td>
<td class="year8" align="right">M86:</td>
<td class="year8"><input id="month_86" name="month_86" type="text" size="10" value="<?php if ($inv['MONTH_86']==null) echo "0"; else echo $inv['MONTH_86'];?>" onchange="updateTotal();" /></td>
<td class="year9" align="right">M98:</td>
<td class="year9"><input id="month_98" name="month_98" type="text" size="10" value="<?php if ($inv['MONTH_98']==null) echo "0"; else echo $inv['MONTH_98'];?>" onchange="updateTotal();" /></td>
<td class="year10" align="right">M110:</td>
<td class="year10"><input id="month_110" name="month_110" type="text" size="10" value="<?php if ($inv['MONTH_110']==null) echo "0"; else echo $inv['MONTH_110'];?>" onchange="updateTotal();" /></td>
</tr>

<tr>
<td class="year1" align="right">M3:</td>
<td class="year1"><input id="month_3" name="month_3" type="text" size="10" value="<?php if ($inv['MONTH_3']==null) echo "0"; else echo $inv['MONTH_3'];?>" onchange="updateTotal();" /></td>
<td class="year2" align="right">M15:</td>
<td class="year2"><input id="month_15" name="month_15" type="text" size="10" value="<?php if ($inv['MONTH_15']==null) echo "0"; else echo $inv['MONTH_15'];?>" onchange="updateTotal();" /></td>
<td class="year3" align="right">M27:</td>
<td class="year3"><input id="month_27" name="month_27" type="text" size="10" value="<?php if ($inv['MONTH_27']==null) echo "0"; else echo $inv['MONTH_27'];?>" onchange="updateTotal();" /></td>
<td class="year4" align="right">M39:</td>
<td class="year4"><input id="month_39" name="month_39" type="text" size="10" value="<?php if ($inv['MONTH_39']==null) echo "0"; else echo $inv['MONTH_39'];?>" onchange="updateTotal();" /></td>
<td class="year5" align="right">M51:</td>
<td class="year5"><input id="month_51" name="month_51" type="text" size="10" value="<?php if ($inv['MONTH_51']==null) echo "0"; else echo $inv['MONTH_51'];?>" onchange="updateTotal();" /></td>
<td class="year6" align="right">M63:</td>
<td class="year6"><input id="month_63" name="month_63" type="text" size="10" value="<?php if ($inv['MONTH_63']==null) echo "0"; else echo $inv['MONTH_63'];?>" onchange="updateTotal();" /></td>
<td class="year7" align="right">M75:</td>
<td class="year7"><input id="month_75" name="month_75" type="text" size="10" value="<?php if ($inv['MONTH_75']==null) echo "0"; else echo $inv['MONTH_75'];?>" onchange="updateTotal();" /></td>
<td class="year8" align="right">M87:</td>
<td class="year8"><input id="month_87" name="month_87" type="text" size="10" value="<?php if ($inv['MONTH_87']==null) echo "0"; else echo $inv['MONTH_87'];?>" onchange="updateTotal();" /></td>
<td class="year9" align="right">M99:</td>
<td class="year9"><input id="month_99" name="month_99" type="text" size="10" value="<?php if ($inv['MONTH_99']==null) echo "0"; else echo $inv['MONTH_99'];?>" onchange="updateTotal();" /></td>
<td class="year10" align="right">M111:</td>
<td class="year10"><input id="month_111" name="month_111" type="text" size="10" value="<?php if ($inv['MONTH_111']==null) echo "0"; else echo $inv['MONTH_111'];?>" onchange="updateTotal();" /></td>
</tr>

<tr>
<td class="year1" align="right">M4:</td>
<td class="year1"><input id="month_4" name="month_4" type="text" size="10" value="<?php if ($inv['MONTH_4']==null) echo "0"; else echo $inv['MONTH_4'];?>" onchange="updateTotal();" /></td>
<td class="year2" align="right">M16:</td>
<td class="year2"><input id="month_16" name="month_16" type="text" size="10" value="<?php if ($inv['MONTH_16']==null) echo "0"; else echo $inv['MONTH_16'];?>" onchange="updateTotal();" /></td>
<td class="year3" align="right">M28:</td>
<td class="year3"><input id="month_28" name="month_28" type="text" size="10" value="<?php if ($inv['MONTH_28']==null) echo "0"; else echo $inv['MONTH_28'];?>" onchange="updateTotal();" /></td>
<td class="year4" align="right">M40:</td>
<td class="year4"><input id="month_40" name="month_40" type="text" size="10" value="<?php if ($inv['MONTH_40']==null) echo "0"; else echo $inv['MONTH_40'];?>" onchange="updateTotal();" /></td>
<td class="year5" align="right">M52:</td>
<td class="year5"><input id="month_52" name="month_52" type="text" size="10" value="<?php if ($inv['MONTH_52']==null) echo "0"; else echo $inv['MONTH_52'];?>" onchange="updateTotal();" /></td>
<td class="year6" align="right">M64:</td>
<td class="year6"><input id="month_64" name="month_64" type="text" size="10" value="<?php if ($inv['MONTH_64']==null) echo "0"; else echo $inv['MONTH_64'];?>" onchange="updateTotal();" /></td>
<td class="year7" align="right">M76:</td>
<td class="year7"><input id="month_76" name="month_76" type="text" size="10" value="<?php if ($inv['MONTH_76']==null) echo "0"; else echo $inv['MONTH_76'];?>" onchange="updateTotal();" /></td>
<td class="year8" align="right">M88:</td>
<td class="year8"><input id="month_88" name="month_88" type="text" size="10" value="<?php if ($inv['MONTH_88']==null) echo "0"; else echo $inv['MONTH_88'];?>" onchange="updateTotal();" /></td>
<td class="year9" align="right">M100:</td>
<td class="year9"><input id="month_100" name="month_100" type="text" size="10" value="<?php if ($inv['MONTH_100']==null) echo "0"; else echo $inv['MONTH_100'];?>" onchange="updateTotal();" /></td>
<td class="year10" align="right">M112:</td>
<td class="year10"><input id="month_112" name="month_112" type="text" size="10" value="<?php if ($inv['MONTH_112']==null) echo "0"; else echo $inv['MONTH_112'];?>" onchange="updateTotal();" /></td>
</tr>

<tr>
<td class="year1" align="right">M5:</td>
<td class="year1"><input id="month_5" name="month_5" type="text" size="10" value="<?php if ($inv['MONTH_5']==null) echo "0"; else echo $inv['MONTH_5'];?>" onchange="updateTotal();" /></td>
<td class="year2" align="right">M17:</td>
<td class="year2"><input id="month_17" name="month_17" type="text" size="10" value="<?php if ($inv['MONTH_17']==null) echo "0"; else echo $inv['MONTH_17'];?>" onchange="updateTotal();" /></td>
<td class="year3" align="right">M29:</td>
<td class="year3"><input id="month_29" name="month_29" type="text" size="10" value="<?php if ($inv['MONTH_29']==null) echo "0"; else echo $inv['MONTH_29'];?>" onchange="updateTotal();" /></td>
<td class="year4" align="right">M41:</td>
<td class="year4"><input id="month_41" name="month_41" type="text" size="10" value="<?php if ($inv['MONTH_41']==null) echo "0"; else echo $inv['MONTH_41'];?>" onchange="updateTotal();" /></td>
<td class="year5" align="right">M53:</td>
<td class="year5"><input id="month_53" name="month_53" type="text" size="10" value="<?php if ($inv['MONTH_53']==null) echo "0"; else echo $inv['MONTH_53'];?>" onchange="updateTotal();" /></td>
<td class="year6" align="right">M65:</td>
<td class="year6"><input id="month_65" name="month_65" type="text" size="10" value="<?php if ($inv['MONTH_65']==null) echo "0"; else echo $inv['MONTH_65'];?>" onchange="updateTotal();" /></td>
<td class="year7" align="right">M77:</td>
<td class="year7"><input id="month_77" name="month_77" type="text" size="10" value="<?php if ($inv['MONTH_77']==null) echo "0"; else echo $inv['MONTH_77'];?>" onchange="updateTotal();" /></td>
<td class="year8" align="right">M89:</td>
<td class="year8"><input id="month_89" name="month_89" type="text" size="10" value="<?php if ($inv['MONTH_89']==null) echo "0"; else echo $inv['MONTH_89'];?>" onchange="updateTotal();" /></td>
<td class="year9" align="right">M101:</td>
<td class="year9"><input id="month_101" name="month_101" type="text" size="10" value="<?php if ($inv['MONTH_101']==null) echo "0"; else echo $inv['MONTH_101'];?>" onchange="updateTotal();" /></td>
<td class="year10" align="right">M113:</td>
<td class="year10"><input id="month_113" name="month_113" type="text" size="10" value="<?php if ($inv['MONTH_113']==null) echo "0"; else echo $inv['MONTH_113'];?>" onchange="updateTotal();" /></td>
</tr>

<tr>
<td class="year1" align="right">M6:</td>
<td class="year1"><input id="month_6" name="month_6" type="text" size="10" value="<?php if ($inv['MONTH_6']==null) echo "0"; else echo $inv['MONTH_6'];?>" onchange="updateTotal();" /></td>
<td class="year2" align="right">M18:</td>
<td class="year2"><input id="month_18" name="month_18" type="text" size="10" value="<?php if ($inv['MONTH_18']==null) echo "0"; else echo $inv['MONTH_18'];?>" onchange="updateTotal();" /></td>
<td class="year3" align="right">M30:</td>
<td class="year3"><input id="month_30" name="month_30" type="text" size="10" value="<?php if ($inv['MONTH_30']==null) echo "0"; else echo $inv['MONTH_30'];?>" onchange="updateTotal();" /></td>
<td class="year4" align="right">M42:</td>
<td class="year4"><input id="month_42" name="month_42" type="text" size="10" value="<?php if ($inv['MONTH_42']==null) echo "0"; else echo $inv['MONTH_42'];?>" onchange="updateTotal();" /></td>
<td class="year5" align="right">M54:</td>
<td class="year5"><input id="month_54" name="month_54" type="text" size="10" value="<?php if ($inv['MONTH_54']==null) echo "0"; else echo $inv['MONTH_54'];?>" onchange="updateTotal();" /></td>
<td class="year6" align="right">M66:</td>
<td class="year6"><input id="month_66" name="month_66" type="text" size="10" value="<?php if ($inv['MONTH_66']==null) echo "0"; else echo $inv['MONTH_66'];?>" onchange="updateTotal();" /></td>
<td class="year7" align="right">M78:</td>
<td class="year7"><input id="month_78" name="month_78" type="text" size="10" value="<?php if ($inv['MONTH_78']==null) echo "0"; else echo $inv['MONTH_78'];?>" onchange="updateTotal();" /></td>
<td class="year8" align="right">M90:</td>
<td class="year8"><input id="month_90" name="month_90" type="text" size="10" value="<?php if ($inv['MONTH_90']==null) echo "0"; else echo $inv['MONTH_90'];?>" onchange="updateTotal();" /></td>
<td class="year9" align="right">M102:</td>
<td class="year9"><input id="month_102" name="month_102" type="text" size="10" value="<?php if ($inv['MONTH_102']==null) echo "0"; else echo $inv['MONTH_102'];?>" onchange="updateTotal();" /></td>
<td class="year10" align="right">M114:</td>
<td class="year10"><input id="month_114" name="month_114" type="text" size="10" value="<?php if ($inv['MONTH_114']==null) echo "0"; else echo $inv['MONTH_114'];?>" onchange="updateTotal();" /></td>
</tr>

<tr>
<td class="year1" align="right">M7:</td>
<td class="year1"><input id="month_7" name="month_7" type="text" size="10" value="<?php if ($inv['MONTH_7']==null) echo "0"; else echo $inv['MONTH_7'];?>" onchange="updateTotal();" /></td>
<td class="year2" align="right">M19:</td>
<td class="year2"><input id="month_19" name="month_19" type="text" size="10" value="<?php if ($inv['MONTH_19']==null) echo "0"; else echo $inv['MONTH_19'];?>" onchange="updateTotal();" /></td>
<td class="year3" align="right">M31:</td>
<td class="year3"><input id="month_31" name="month_31" type="text" size="10" value="<?php if ($inv['MONTH_31']==null) echo "0"; else echo $inv['MONTH_31'];?>" onchange="updateTotal();" /></td>
<td class="year4" align="right">M43:</td>
<td class="year4"><input id="month_43" name="month_43" type="text" size="10" value="<?php if ($inv['MONTH_43']==null) echo "0"; else echo $inv['MONTH_43'];?>" onchange="updateTotal();" /></td>
<td class="year5" align="right">M55:</td>
<td class="year5"><input id="month_55" name="month_55" type="text" size="10" value="<?php if ($inv['MONTH_55']==null) echo "0"; else echo $inv['MONTH_55'];?>" onchange="updateTotal();" /></td>
<td class="year6" align="right">M67:</td>
<td class="year6"><input id="month_67" name="month_67" type="text" size="10" value="<?php if ($inv['MONTH_67']==null) echo "0"; else echo $inv['MONTH_67'];?>" onchange="updateTotal();" /></td>
<td class="year7" align="right">M79:</td>
<td class="year7"><input id="month_79" name="month_79" type="text" size="10" value="<?php if ($inv['MONTH_79']==null) echo "0"; else echo $inv['MONTH_79'];?>" onchange="updateTotal();" /></td>
<td class="year8" align="right">M91:</td>
<td class="year8"><input id="month_91" name="month_91" type="text" size="10" value="<?php if ($inv['MONTH_91']==null) echo "0"; else echo $inv['MONTH_91'];?>" onchange="updateTotal();" /></td>
<td class="year9" align="right">M103:</td>
<td class="year9"><input id="month_103" name="month_103" type="text" size="10" value="<?php if ($inv['MONTH_103']==null) echo "0"; else echo $inv['MONTH_103'];?>" onchange="updateTotal();" /></td>
<td class="year10" align="right">M115:</td>
<td class="year10"><input id="month_115" name="month_115" type="text" size="10" value="<?php if ($inv['MONTH_115']==null) echo "0"; else echo $inv['MONTH_115'];?>" onchange="updateTotal();" /></td>
</tr>

<tr>
<td class="year1" align="right">M8:</td>
<td class="year1"><input id="month_8" name="month_8" type="text" size="10" value="<?php if ($inv['MONTH_8']==null) echo "0"; else echo $inv['MONTH_8'];?>" onchange="updateTotal();" /></td>
<td class="year2" align="right">M20:</td>
<td class="year2"><input id="month_20" name="month_20" type="text" size="10" value="<?php if ($inv['MONTH_20']==null) echo "0"; else echo $inv['MONTH_20'];?>" onchange="updateTotal();" /></td>
<td class="year3" align="right">M32:</td>
<td class="year3"><input id="month_32" name="month_32" type="text" size="10" value="<?php if ($inv['MONTH_32']==null) echo "0"; else echo $inv['MONTH_32'];?>" onchange="updateTotal();" /></td>
<td class="year4" align="right">M44:</td>
<td class="year4"><input id="month_44" name="month_44" type="text" size="10" value="<?php if ($inv['MONTH_44']==null) echo "0"; else echo $inv['MONTH_44'];?>" onchange="updateTotal();" /></td>
<td class="year5" align="right">M56:</td>
<td class="year5"><input id="month_56" name="month_56" type="text" size="10" value="<?php if ($inv['MONTH_56']==null) echo "0"; else echo $inv['MONTH_56'];?>" onchange="updateTotal();" /></td>
<td class="year6" align="right">M68:</td>
<td class="year6"><input id="month_68" name="month_68" type="text" size="10" value="<?php if ($inv['MONTH_68']==null) echo "0"; else echo $inv['MONTH_68'];?>" onchange="updateTotal();" /></td>
<td class="year7" align="right">M80:</td>
<td class="year7"><input id="month_80" name="month_80" type="text" size="10" value="<?php if ($inv['MONTH_80']==null) echo "0"; else echo $inv['MONTH_80'];?>" onchange="updateTotal();" /></td>
<td class="year8" align="right">M92:</td>
<td class="year8"><input id="month_92" name="month_92" type="text" size="10" value="<?php if ($inv['MONTH_92']==null) echo "0"; else echo $inv['MONTH_92'];?>" onchange="updateTotal();" /></td>
<td class="year9" align="right">M104:</td>
<td class="year9"><input id="month_104" name="month_104" type="text" size="10" value="<?php if ($inv['MONTH_104']==null) echo "0"; else echo $inv['MONTH_104'];?>" onchange="updateTotal();" /></td>
<td class="year10" align="right">M116:</td>
<td class="year10"><input id="month_116" name="month_116" type="text" size="10" value="<?php if ($inv['MONTH_116']==null) echo "0"; else echo $inv['MONTH_116'];?>" onchange="updateTotal();" /></td>
</tr>

<tr>
<td class="year1" align="right">M9:</td>
<td class="year1"><input id="month_9" name="month_9" type="text" size="10" value="<?php if ($inv['MONTH_9']==null) echo "0"; else echo $inv['MONTH_9'];?>" onchange="updateTotal();" /></td>
<td class="year2" align="right">M21:</td>
<td class="year2"><input id="month_21" name="month_21" type="text" size="10" value="<?php if ($inv['MONTH_21']==null) echo "0"; else echo $inv['MONTH_21'];?>" onchange="updateTotal();" /></td>
<td class="year3" align="right">M33:</td>
<td class="year3"><input id="month_33" name="month_33" type="text" size="10" value="<?php if ($inv['MONTH_33']==null) echo "0"; else echo $inv['MONTH_33'];?>" onchange="updateTotal();" /></td>
<td class="year4" align="right">M45:</td>
<td class="year4"><input id="month_45" name="month_45" type="text" size="10" value="<?php if ($inv['MONTH_45']==null) echo "0"; else echo $inv['MONTH_45'];?>" onchange="updateTotal();" /></td>
<td class="year5" align="right">M57:</td>
<td class="year5"><input id="month_57" name="month_57" type="text" size="10" value="<?php if ($inv['MONTH_57']==null) echo "0"; else echo $inv['MONTH_57'];?>" onchange="updateTotal();" /></td>
<td class="year6" align="right">M69:</td>
<td class="year6"><input id="month_69" name="month_69" type="text" size="10" value="<?php if ($inv['MONTH_69']==null) echo "0"; else echo $inv['MONTH_69'];?>" onchange="updateTotal();" /></td>
<td class="year7" align="right">M81:</td>
<td class="year7"><input id="month_81" name="month_81" type="text" size="10" value="<?php if ($inv['MONTH_81']==null) echo "0"; else echo $inv['MONTH_81'];?>" onchange="updateTotal();" /></td>
<td class="year8" align="right">M93:</td>
<td class="year8"><input id="month_93" name="month_93" type="text" size="10" value="<?php if ($inv['MONTH_93']==null) echo "0"; else echo $inv['MONTH_93'];?>" onchange="updateTotal();" /></td>
<td class="year9" align="right">M105:</td>
<td class="year9"><input id="month_105" name="month_105" type="text" size="10" value="<?php if ($inv['MONTH_105']==null) echo "0"; else echo $inv['MONTH_105'];?>" onchange="updateTotal();" /></td>
<td class="year10" align="right">M117:</td>
<td class="year10"><input id="month_117" name="month_117" type="text" size="10" value="<?php if ($inv['MONTH_117']==null) echo "0"; else echo $inv['MONTH_117'];?>" onchange="updateTotal();" /></td>
</tr>

<tr>
<td class="year1" align="right">M10:</td>
<td class="year1"><input id="month_10" name="month_10" type="text" size="10" value="<?php if ($inv['MONTH_10']==null) echo "0"; else echo $inv['MONTH_10'];?>" onchange="updateTotal();" /></td>
<td class="year2" align="right">M22:</td>
<td class="year2"><input id="month_22" name="month_22" type="text" size="10" value="<?php if ($inv['MONTH_22']==null) echo "0"; else echo $inv['MONTH_22'];?>" onchange="updateTotal();" /></td>
<td class="year3" align="right">M34:</td>
<td class="year3"><input id="month_34" name="month_34" type="text" size="10" value="<?php if ($inv['MONTH_34']==null) echo "0"; else echo $inv['MONTH_34'];?>" onchange="updateTotal();" /></td>
<td class="year4" align="right">M46:</td>
<td class="year4"><input id="month_46" name="month_46" type="text" size="10" value="<?php if ($inv['MONTH_46']==null) echo "0"; else echo $inv['MONTH_46'];?>" onchange="updateTotal();" /></td>
<td class="year5" align="right">M58:</td>
<td class="year5"><input id="month_58" name="month_58" type="text" size="10" value="<?php if ($inv['MONTH_58']==null) echo "0"; else echo $inv['MONTH_58'];?>" onchange="updateTotal();" /></td>
<td class="year6" align="right">M70:</td>
<td class="year6"><input id="month_70" name="month_70" type="text" size="10" value="<?php if ($inv['MONTH_70']==null) echo "0"; else echo $inv['MONTH_70'];?>" onchange="updateTotal();" /></td>
<td class="year7" align="right">M82:</td>
<td class="year7"><input id="month_82" name="month_82" type="text" size="10" value="<?php if ($inv['MONTH_82']==null) echo "0"; else echo $inv['MONTH_82'];?>" onchange="updateTotal();" /></td>
<td class="year8" align="right">M94:</td>
<td class="year8"><input id="month_94" name="month_94" type="text" size="10" value="<?php if ($inv['MONTH_94']==null) echo "0"; else echo $inv['MONTH_94'];?>" onchange="updateTotal();" /></td>
<td class="year9" align="right">M106:</td>
<td class="year9"><input id="month_106" name="month_106" type="text" size="10" value="<?php if ($inv['MONTH_106']==null) echo "0"; else echo $inv['MONTH_106'];?>" onchange="updateTotal();" /></td>
<td class="year10" align="right">M118:</td>
<td class="year10"><input id="month_118" name="month_118" type="text" size="10" value="<?php if ($inv['MONTH_118']==null) echo "0"; else echo $inv['MONTH_118'];?>" onchange="updateTotal();" /></td>
</tr>

<tr>
<td class="year1" align="right">M11:</td>
<td class="year1"><input id="month_11" name="month_11" type="text" size="10" value="<?php if ($inv['MONTH_11']==null) echo "0"; else echo $inv['MONTH_11'];?>" onchange="updateTotal();" /></td>
<td class="year2" align="right">M23:</td>
<td class="year2"><input id="month_23" name="month_23" type="text" size="10" value="<?php if ($inv['MONTH_23']==null) echo "0"; else echo $inv['MONTH_23'];?>" onchange="updateTotal();" /></td>
<td class="year3" align="right">M35:</td>
<td class="year3"><input id="month_35" name="month_35" type="text" size="10" value="<?php if ($inv['MONTH_35']==null) echo "0"; else echo $inv['MONTH_35'];?>" onchange="updateTotal();" /></td>
<td class="year4" align="right">M47:</td>
<td class="year4"><input id="month_47" name="month_47" type="text" size="10" value="<?php if ($inv['MONTH_47']==null) echo "0"; else echo $inv['MONTH_47'];?>" onchange="updateTotal();" /></td>
<td class="year5" align="right">M59:</td>
<td class="year5"><input id="month_59" name="month_59" type="text" size="10" value="<?php if ($inv['MONTH_59']==null) echo "0"; else echo $inv['MONTH_59'];?>" onchange="updateTotal();" /></td>
<td class="year6" align="right">M71:</td>
<td class="year6"><input id="month_71" name="month_71" type="text" size="10" value="<?php if ($inv['MONTH_71']==null) echo "0"; else echo $inv['MONTH_71'];?>" onchange="updateTotal();" /></td>
<td class="year7" align="right">M83:</td>
<td class="year7"><input id="month_83" name="month_83" type="text" size="10" value="<?php if ($inv['MONTH_83']==null) echo "0"; else echo $inv['MONTH_83'];?>" onchange="updateTotal();" /></td>
<td class="year8" align="right">M95:</td>
<td class="year8"><input id="month_95" name="month_95" type="text" size="10" value="<?php if ($inv['MONTH_95']==null) echo "0"; else echo $inv['MONTH_95'];?>" onchange="updateTotal();" /></td>
<td class="year9" align="right">M107:</td>
<td class="year9"><input id="month_107" name="month_107" type="text" size="10" value="<?php if ($inv['MONTH_107']==null) echo "0"; else echo $inv['MONTH_107'];?>" onchange="updateTotal();" /></td>
<td class="year10" align="right">M119:</td>
<td class="year10"><input id="month_119" name="month_119" type="text" size="10" value="<?php if ($inv['MONTH_119']==null) echo "0"; else echo $inv['MONTH_119'];?>" onchange="updateTotal();" /></td>
</tr>

<tr>
<td class="year1" align="right">M12:</td>
<td class="year1"><input id="month_12" name="month_12" type="text" size="10" value="<?php if ($inv['MONTH_12']==null) echo "0"; else echo $inv['MONTH_12'];?>" onchange="updateTotal();" /></td>
<td class="year2" align="right">M24:</td>
<td class="year2"><input id="month_24" name="month_24" type="text" size="10" value="<?php if ($inv['MONTH_24']==null) echo "0"; else echo $inv['MONTH_24'];?>" onchange="updateTotal();" /></td>
<td class="year3" align="right">M36:</td>
<td class="year3"><input id="month_36" name="month_36" type="text" size="10" value="<?php if ($inv['MONTH_36']==null) echo "0"; else echo $inv['MONTH_36'];?>" onchange="updateTotal();" /></td>
<td class="year4" align="right">M48:</td>
<td class="year4"><input id="month_48" name="month_48" type="text" size="10" value="<?php if ($inv['MONTH_48']==null) echo "0"; else echo $inv['MONTH_48'];?>" onchange="updateTotal();" /></td>
<td class="year5" align="right">M60:</td>
<td class="year5"><input id="month_60" name="month_60" type="text" size="10" value="<?php if ($inv['MONTH_60']==null) echo "0"; else echo $inv['MONTH_60'];?>" onchange="updateTotal();" /></td>
<td class="year6" align="right">M72:</td>
<td class="year6"><input id="month_72" name="month_72" type="text" size="10" value="<?php if ($inv['MONTH_72']==null) echo "0"; else echo $inv['MONTH_72'];?>" onchange="updateTotal();" /></td>
<td class="year7" align="right">M84:</td>
<td class="year7"><input id="month_84" name="month_84" type="text" size="10" value="<?php if ($inv['MONTH_84']==null) echo "0"; else echo $inv['MONTH_84'];?>" onchange="updateTotal();" /></td>
<td class="year8" align="right">M96:</td>
<td class="year8"><input id="month_96" name="month_96" type="text" size="10" value="<?php if ($inv['MONTH_96']==null) echo "0"; else echo $inv['MONTH_96'];?>" onchange="updateTotal();" /></td>
<td class="year9" align="right">M108:</td>
<td class="year9"><input id="month_108" name="month_108" type="text" size="10" value="<?php if ($inv['MONTH_108']==null) echo "0"; else echo $inv['MONTH_108'];?>" onchange="updateTotal();" /></td>
<td class="year10" align="right">M120:</td>
<td class="year10"><input id="month_120" name="month_120" type="text" size="10" value="<?php if ($inv['MONTH_120']==null) echo "0"; else echo $inv['MONTH_120'];?>" onchange="updateTotal();" /></td>
</tr>

</td></tr>
</table>

</table>
</form>
</div>
<?php
} else {
?>
<div align="center"><h3>Error: Lead ID is required</h3></div>
<?php
}
?>

<!-- End Content -->
</div>
</body>
</html>
<?php
}
?>