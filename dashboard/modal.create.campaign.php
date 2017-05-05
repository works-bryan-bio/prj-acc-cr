       


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
	<h3>Drift Campaign</h3>
	<form id="emailForm" name="emailForm" action="submitDriftCampaign.php" method="post" >
		Template: <select id="templateMassEmailc" name="template" onchange="tinymce.get('messageMassEmailc').setContent(this.value);">
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
		<select id="lead_sc" name="lead_sc">
			<option value="search_lead">Leads</option>
			<option value="search_lead_type">Lead Type</option>
		</select>
		<br /><br />

		<div id="lead_type_containerc" style="display:none;">
			<select id="lead_type" name="lead_type" style="width:99%">
			<?php
				$result = $mysqli->query("SELECT LEAD_TYPE FROM leads GROUP BY LEAD_TYPE") or die(mysql_error());
				while($row = mysqli_fetch_array($result)){
					foreach($row AS $key => $value) {
						$row[$key] = stripslashes($value);
					}
			?>			
				<option value="<?php echo $row['LEAD_TYPE']; ?>"><?php echo $row['LEAD_TYPE']; ?></option>
			<?php } ?>
			</select>
			<br /><br />		
		</div>

		<div id="leads_containerc" style="">
			<!-- <input id="search_leads" name="search_leads" type="text" style="width:96%" placeholder="Search Leads" />
			<br /><br /> -->
			<input id="search_leads_auto_completec" name="search_leads_auto_completec" type="text" placeholder="Search Leads" />
			<br /><br />			
		</div>

		<label>Date to send:</label>
		<input type="text" name="date_to_send" id="date_to_send" size="10"
			value="<?php if ($date_to_send!="") echo date("m/d/Y", strtotime($date_to_send)); else echo "" ?>" onChange="" />
		<script type="text/javascript">
			var s_cal = new tcal ({
				'controlname': 'date_to_send'
			});
		</script>
		<br /><br />
		<input id="subjectMassEmailc" name="subject" type="text" style="width:96%" placeholder="Subject" />
		<br /><br />

		<textarea id="messageMassEmailc" name="messageMassEmailc" style="width:98%; height:480px"></textarea>
		<br />
		<input class="button" type="submit" id="sendEmail" name="sendEmail" value="Save Campaign" onClick="" />
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