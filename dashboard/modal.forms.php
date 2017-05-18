<link rel="stylesheet" type="text/css" href="css/tokeninput/token-input.css">
<link rel="stylesheet" type="text/css" href="css/tokeninput/token-input-facebook.css">
<script type="text/javascript" src="js/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
<script type="text/javascript" src="js/tokeninput/jquery.tokeninput.js"></script>
<!-- <script type="text/javascript" src="js/autocomplete.multiselect.js"></script> -->
<style>
.table-form td{
	padding:4px;
	vertical-align: top;
}
</style>
<script type="text/javascript">
tinyMCE.init({
	    mode: "exact",
	    elements: "elm1,message,messageMassEmail",
	    plugins : "spellchecker",
	    theme: "advanced",
	    theme_advanced_buttons1: "bold,italic,underline,strikethrough,separator,justifyleft,justifycenter,justifyright,justifyfull,bullist,numlist,undo,redo,link,unlink,spellchecker",
	    theme_advanced_buttons2: "",
	    theme_advanced_buttons3: "",
	    theme_advanced_buttons4: "",
	    theme_advanced_toolbar_location: "top",
	    theme_advanced_toolbar_align: "left"
	});
$(document).ready(function () {
		//Autocomplete
		$("#search_leads_auto_complete").tokenInput("ajax/tokeninput-leads-email.php", {
           	theme: "facebook",
        	preventDuplicates: true
        });
        $("#search_lead_types_auto_complete").tokenInput("ajax/tokeninput-lead-types.php", {
           	theme: "facebook",
        	preventDuplicates: true
        });
		//When you click on a link with class of poplight and the href starts with a #
		$('a.modal-poplight[href^=#]').click(function () {
			var popID = $(this).attr('rel'); //Get Popup Name
			var popURL = $(this).attr('href'); //Get Popup href to define size

			//Pull Query & Variables from href URL
			var query = popURL.split('?');
			var dim = query[1].split('&');
			var popWidth = dim[0].split('=')[1]; //Gets the first query string value

			//Fade in the Popup and add close button
			$('#' + popID).fadeIn().css({'width': Number(popWidth)}).prepend('<a href="#" style="float:right" class="close">Close [X]</a>');

			//Define margin for center alignment (vertical   horizontal) - we add 80px to the height/width to accomodate for the padding  and border width defined in the css
			var popMargTop = ($('#' + popID).height() + 80) / 2;
			var popMargLeft = ($('#' + popID).width() + 80) / 2;

			//Apply Margin to Popup
			$('#' + popID).css({
				'margin-top': -popMargTop,
				'margin-left': -popMargLeft
			});

			if (popID === "mass_email_popup") {
				document.getElementById("templateMassEmail").value = "";
				document.getElementById("subjectMassEmail").value = "";
				tinyMCE.get("messageMassEmail").setContent("");
			}

			//Fade in Background
			$('body').append('<div id="fade"></div>'); //Add the fade layer to bottom of the body tag.
			$('#fade').css({'filter': 'alpha(opacity=80)'}).fadeIn(); //Fade in the fade layer - .css({'filter' : 'alpha(opacity=80)'}) is used to fix the IE Bug on fading transparencies

			return false;
		});

		//Close Popups and Fade Layer
		$('a.close').live('click', function () { //When clicking on the close or fade layer...
			$('#fade , .popup_block').fadeOut(function () {
				$('#fade, a.close').remove();  //fade them both out
			});
			return false;
		});

	});
</script>
<div id="mass_email_popup"  class="popup_block">
	<h3>Mass Email Lead Contact</h3><br/>
	<form id="emailForm" action="submitEmailBlast.php" name="emailForm" method="post">
		<table style="width:100%;" class="table-form">
			<tr>
				<td style="width:100px;">Subject</td>	
				<td><input id="subjectMassEmail" name="subject" type="text" style="width:97%" placeholder="" /></td>
			</tr>			
			<tr>
				<td>Recipient Type</td>
				<td>
					<select id="lead_s" name="lead_s">
						<option value="search_lead">Leads</option>
						<option value="search_lead_type">Lead Type</option>
					</select>
				</td>
			</tr>
			<tr>
				<td>Recipient</td>
				<td>
					<div id="lead_type_container" style="display:none;">
						<!-- <select id="lead_type" name="lead_type" style="width:99%;">
						<?php
							//$result = $mysqli->query("SELECT LEAD_TYPE FROM leads WHERE LEAD_TYPE <> '' GROUP BY LEAD_TYPE") or die(mysql_error());
							//while($row = mysqli_fetch_array($result)){
								//foreach($row AS $key => $value) {
									//$row[$key] = stripslashes($value);
								//}
						?>			
							<option value="<?php //echo $row['LEAD_TYPE']; ?>"><?php //echo $row['LEAD_TYPE']; ?></option>
						<?php //} ?>
						</select> -->
						<input id="search_lead_types_auto_complete" name="lead_type" type="text" placeholder="Search Lead Types" />						
					</div>
					<div id="leads_container" style="">						
						<input id="search_leads_auto_complete" name="search_leads_auto_complete" type="text" placeholder="Search Leads" />						
					</div>
				</td>
			</tr>
			<tr><td colspan="2">&nbsp;</td></tr>
			<tr>
				<td>Template</td>
				<td>
					<select id="templateMassEmail" name="template" onchange="tinymce.get('messageMassEmail').setContent(this.value);">
					<option value="">None</option>
					<?php
						$result = $mysqli->query("SELECT name,content FROM email_templates") or die(mysql_error());
						while($row = mysqli_fetch_array($result)){
							foreach($row AS $key => $value) {
								$row[$key] = stripslashes($value);
							}
					?>
					<option value="<?=str_replace('"', "'", $row['content'])?>"><?=$row['name']?></option>
					<?php
						}
					?>
					</select>					
				</td>
			</tr>			
			<tr>	
				<td>Email Content</td>			
				<td><textarea id="messageMassEmail" name="messageMassEmail" style="width:98%; height:480px"></textarea></td>
			</tr>
			<tr>
				<td colspan="2" style="text-align:right;"><input class="button" type="submit" id="sendEmail" name="sendEmail" value="Send Email" onClick="callHelper('searchReportHelper.php?action=sendEmail&lead_id='); $('.popup_block').hide(); $('#fade, a.close').remove();" /></td>
			</tr>
		</table>		
		<br />
		
	</form>
</div>

<script>
	$(document).ready(function(){
	    $("#lead_s").change(function(){

	            var optionValue = $(this).attr("value");
	            if(optionValue == 'search_lead'){
	               
	              	$("#leads_container").show();
	               	$("#lead_type_container").hide();

	            } else{
	               
	            	$("#leads_container").hide();
	               	$("#lead_type_container").show();

	            }

	    });


	});

</script>