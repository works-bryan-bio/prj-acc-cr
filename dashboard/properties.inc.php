<script type="text/javascript">
	dojo.require("dijit.form.Button");
	dojo.require("dijit.form.Form");
	dojo.require("dijit.form.ValidationTextBox");

	function searchProperties() {
		var output = dojo.byId("propOutput");
		dojo.xhrPost({
		    // The URL of the request
		    url: "searchProperties.php",
		    // No content property -- just send the entire form
		    form: dojo.byId("propForm"),
		    // The success handler
		    load: function(response) {
		    	output.innerHTML = response;
		    },
		    // The error handler
		    error: function(response) {
		    	output.innerHTML = response;
		    }
		});
	}
</script>

<p />
<div align="center">
<div dojoType="dijit.form.Form" name="propForm" id="propForm" encType="multipart/form-data" method="" action="">
<label for="center_name">Center Name:</label>
<input dojoType="dijit.form.ValidationTextBox" type="text" name="center_name" id="center_name" required="true" />
<button dojoType="dijit.form.Button" name="submit" value="Submit" onClick="searchProperties();">Submit</button>
</div>
</div>
<p />
<div id="propOutput">
&nbsp;
</div>
