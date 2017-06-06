sfHover = function() {
  var sfEls = document.getElementById("navbar").getElementsByTagName("li");
  for (var i = 0; i < sfEls.length; i++) {
	sfEls[i].onmouseover = function() {
	  this.className += " hover";
	};
	sfEls[i].onmouseout = function() {
	  this.className = this.className.replace(new RegExp(" hover\\b"), "");
	};
  }
};

if (window.attachEvent) {
  window.attachEvent("onload", sfHover);
}

function confirm_delete() {
  return confirm("Are you sure you want to delete this item?");
}

function confirm_remove() {
  return confirm("Are you sure you want to delete this item?");
}

function formatPhoneNumber(elementRef) {
  var elementValue = elementRef.value;

  // Remove all "(", ")", "-", and spaces...
  elementValue = elementValue.replace(/\(/g, '');
  elementValue = elementValue.replace(/\)/g, '');
  elementValue = elementValue.replace(/\-/g, '');
  elementValue = elementValue.replace(/\s+/g, '');

  if (elementValue.length !== 0 && elementValue.length < 10) {
	alert('Error: The phone number needs to be at least 10 characters');
	elementRef.select();
	elementRef.focus();
	return;
  }

  if (elementValue.length === 10) {
	elementRef.value = (elementValue.substr(0, 3) + '-' + elementValue.substr(3, 3) + '-' + elementValue.substr(6, 4));
  }
}

function calculateExitStrategy() {
	calculateUA();
	calculateHH();
	calculateWT();
	calculateRH();
}

function calculateUA() {

	if (document.getElementById("arv")) {
		var arv = document.getElementById("arv").value;
		var arv_seventy = arv * .70;
		var arv_seventyseven = arv * .77;
		var bathrooms = document.getElementById("bathrooms").value;
		var roof_age = document.getElementById("roof_age").value;
		var insurance = document.getElementById("insurance").value;
		var foundation = document.getElementById("need_foundation_repair").value;
		var hvac = document.getElementById("hvac_age").value;
		var pool = document.getElementById("pool_condition").value;
		var square_feet = document.getElementById("square_feet").value;
	}

	if (arv > 0) {
		document.getElementById("ua_arv").value = arv;
		//document.getElementById("ua_arv_seventy").value = arv_seventy;
		document.getElementById("ua_arv_seventyseven").value = arv_seventyseven;
		

		var repair_cost = 17000;

		var bathrooms_total = 0;
		if (bathrooms && bathrooms > 0) {
			if (bathrooms > 1) {
				bathrooms_total = (bathrooms - 1) * 3000;
			}
		}
		document.getElementById("ua_bath").value = bathrooms_total;
		repair_cost = repair_cost + bathrooms_total;

		var roof_total = 0;
		if (roof_age && roof_age!="1" && insurance=="No") {
			roof_total = 6000;
		}
		document.getElementById("ua_roof").value = roof_total;
		repair_cost = repair_cost + roof_total;

		var foundation_total = 0;
		if (foundation && foundation=="Yes") {
			foundation_total = 6000;
		}
		document.getElementById("ua_foundation").value = foundation_total;
		repair_cost = repair_cost + foundation_total;

		var hvac_total = 0;
		if (hvac) {
			if (hvac=="0-4") {
				hvac_total = 500;
			}
			if (hvac=="5-9") {
				hvac_total = 3000;
			}
			if (hvac=="10+") {
				hvac_total = 6000;
			}
		}
		document.getElementById("ua_hvac").value = hvac_total;
		repair_cost = repair_cost + hvac_total;

		var pool_total = 0;
		if (pool) {
			if (pool=="Good") {
				pool_total = 2000;
			}
			if (pool=="Bad") {
				pool_total = 2000;
			}
		}
		document.getElementById("ua_pool").value = pool_total;
		repair_cost = repair_cost + pool_total;

		var sqft_total = 0;
		if (hvac) {
			if (arv<=175000) {
				sqft_total = square_feet * 7;
			}
			if (arv>175000 && arv<=350000) {
				sqft_total = square_feet * 10;
			}
			if (arv>350000 && arv<=500000) {
				sqft_total = square_feet * 15;
			}
		}
		document.getElementById("ua_sqft").value = sqft_total;
		repair_cost = repair_cost + sqft_total;

		document.getElementById("ua_repair_cost").value = repair_cost;

		var mao_total = 0;
		/*if (arv_seventy && repair_cost) {
			mao_total = arv_seventy - repair_cost;
		}*/
		if (arv_seventyseven && repair_cost) {
			mao_total = arv_seventyseven - repair_cost;
		}
		document.getElementById("ua_mao").value = mao_total;

	}

}

function calculateHH() {

	if (document.getElementById("as_is_price") && document.getElementById("asking_price")) {
		var as_is = document.getElementById("as_is_price").value;
		var asking = document.getElementById("asking_price").value;
	}

	if (as_is > 0 && asking > 0) {

		document.getElementById("hh_asis").value = as_is;
		document.getElementById("hh_asking").value = asking;

		var repair_cost = document.getElementById("hh_repair_cost").value;
		var profit_total = as_is - repair_cost - asking;
		document.getElementById("hh_profit").value = profit_total;

	}

}

function calculateWT() {

	if (document.getElementById("arv") && document.getElementById("asking_price")) {
		var arv = document.getElementById("arv").value;
		var arv_eighty = arv * .80;
		var asking = document.getElementById("asking_price").value;
	}

	if (arv > 0 && asking > 0) {
		document.getElementById("wt_arv").value = arv;
		document.getElementById("wt_arv_eighty").value = arv_eighty;
		document.getElementById("wt_asking").value = asking;

		var repair_cost = document.getElementById("wt_repair_cost").value;
		var fee = 5000;

		var profit_total = 0;
		if (arv_eighty && repair_cost && asking) {
			profit_total = arv_eighty - repair_cost - fee - asking;
		}
		document.getElementById("wt_profit").value = profit_total;

	}

}

function calculateRH() {

	if (document.getElementById("arv") && document.getElementById("year_built") && document.getElementById("asking_price")) {
		var arv = document.getElementById("arv").value;
		var arv_eighty = arv * .80;
		var year_built = document.getElementById("year_built").value;
		var year = parseInt(year_built);
		var asking = document.getElementById("asking_price").value;
	}

	if (arv > 0 && year > 0) {

		if (year && year > 1985) {
			document.getElementById("rh_build").checked = true;
		}

		if (asking && asking <= 175000) {
			document.getElementById("rh_purchase").checked = true;
		}

		document.getElementById("rh_arv").value = arv;
		document.getElementById("rh_arv_eighty").value = arv_eighty;

	}

}