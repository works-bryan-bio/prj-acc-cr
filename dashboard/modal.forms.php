<div id="mass_email_popup" class="popup_block">
	<p />
	<h3>Mass Email Lead Contact</h3>
	<form id="emailForm" name="emailForm">
		<p />
		Template: <select id="template" name="template" onchange="tinymce.get('message').setContent(this.value);">
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
		<input id="subject" name="subject" type="text" style="width:96%" placeholder="Subject" />
		<p />
		<textarea id="message" name="message" style="width:98%; height:480px"></textarea>
		<p />
		<input class="button" type="button" id="sendEmail" name="sendEmail" value="Send Email"
		onClick="callHelper('searchReportHelper.php?action=sendEmail&lead_id=<?= $lead_id ?>'); $('.popup_block').hide(); $('#fade, a.close').remove();" />
	</form>
</div>