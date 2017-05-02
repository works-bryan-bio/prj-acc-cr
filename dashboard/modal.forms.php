<script type="text/javascript" src="js/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>

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
<div id="mass_email_popup" class="popup_block">
	<p />
	<h3>Mass Email Lead Contact</h3>
	<form id="emailForm" name="emailForm">
		<p />
		Template: <select id="templateMassEmail" name="template" onchange="tinymce.get('messageMassEmail').setContent(this.value);">
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
		<p />
		<input id="subjectMassEmail" name="subject" type="text" style="width:96%" placeholder="Subject" />
		<p />
		<textarea id="messageMassEmail" name="messageMassEmail" style="width:98%; height:480px"></textarea>
		<p />
		<input class="button" type="button" id="sendEmail" name="sendEmail" value="Send Email"
		onClick="callHelper('searchReportHelper.php?action=sendEmail&lead_id=<?= $lead_id ?>'); $('.popup_block').hide(); $('#fade, a.close').remove();" />
	</form>
</div>