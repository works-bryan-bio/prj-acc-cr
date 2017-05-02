function ChangeColor(tableRow, highLight) {
	if (highLight) {
		tableRow.style.cursor = "pointer";
	} else {
		tableRow.style.cursor = "hand";
	}
}

function DoNav(theUrl) {
	document.location.href = theUrl;
}
