<style>
.table-form td{
	padding:4px;
	vertical-align: top;
}
</style>
<script type="text/javascript">
tinyMCE.init({
	    mode: "exact",
	    elements: "elm1,message,messageMassEmailc",
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
		$("#search_leads_auto_completec").tokenInput("ajax/tokeninput-leads-email.php", {
			theme: "facebook",
        	preventDuplicates: true
        });
        $("#search_lead_types_auto_completec").tokenInput("ajax/tokeninput-lead-types.php", {
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
			//$('#' + popID).fadeIn().css({'width': Number(popWidth)}).prepend('<a href="#" style="float:right" class="close">Close [X]</a>');

			//Define margin for center alignment (vertical   horizontal) - we add 80px to the height/width to accomodate for the padding  and border width defined in the css
			var popMargTop = ($('#' + popID).height() + 80) / 2;
			var popMargLeft = ($('#' + popID).width() + 80) / 2;

			//Apply Margin to Popup
			$('#' + popID).css({
				'margin-top': -popMargTop,
				'margin-left': -popMargLeft
			});

			if (popID === "mass_email_popup") {
				document.getElementById("templateMassEmailc").value = "";
				document.getElementById("subjectMassEmailc").value = "";
				tinyMCE.get("messageMassEmailc").setContent("");
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
<?php $date_to_send = date("Y-m-d", strtotime(date('m').'/01/'.date('Y').' 00:00:00')); ?>
<div id="create_campaign_popup" class="popup_block">
	<h3>Drip Campaign</h3><br/>
	<form id="emailForm" name="emailForm" action="dripCampaignHelper.php" method="post">
	<input type="hidden" name="addripcampaign" value="1">
		<table style="width:100%;" class="table-form">
			<tr>
				<td style="width:100px;">Campaign Name</td>
				<td><input id="dripSubject" name="subject" type="text" style="width:97%" placeholder="" /></td>
			</tr>			
		</table>
	</form>
</div>

<script>
	$(document).ready(function(){
	    $("#lead_sc").change(function(){
	        var optionValue = $(this).attr("value");
	        if(optionValue == 'search_lead'){
	          	$("#leads_containerc").show();
	           	$("#lead_type_containerc").hide();

	        } else{
	           
	        	$("#leads_containerc").hide();
	           	$("#lead_type_containerc").show();

	        }
	    });
	});
</script>