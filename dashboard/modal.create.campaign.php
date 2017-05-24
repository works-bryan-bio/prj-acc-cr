<style>
.table-form td{
	padding:4px;
	vertical-align: top;
}
</style>
<div id="create_campaign_popup" class="popup_block">
	<h3>Drip Campaign</h3><br/>
	<form id="emailForm" name="emailForm" action="dripCampaignHelper.php" method="post">
	<input type="hidden" name="addripcampaign" value="1">
		<table style="width:100%;" class="table-form">
			<tr>
				<td style="width:180px;">Campaign Name</td>
				<td><input id="dripName" name="name" type="text" style="width:97%" placeholder="" /></td>
			</tr>
			<tr>
				<td colspan="2" style="text-align:right;"><input class="button" type="submit" id="saveCampaign" name="saveCampaign" value="Save Campaign" onClick="callHelper('searchReportHelper.php?action=sendEmail&lead_id='); $('.popup_block').hide(); $('#fade, a.close').remove();" /></td>
			</tr>			
		</table>
	</form>
</div>