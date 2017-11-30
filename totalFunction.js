
/**
* Enables all the checkboxes when "select all" is selected
*/

function toggle(source) { 
	checkboxes = document.getElementsByName('book[]');
		for(var i=0, n=checkboxes.length;i<n;i++) {
			checkboxes[i].checked = source.checked;
		}
		checkTotal();
}

/**
* Calculates the total by using price and quantity values in the checkboxes.
*/

function checkTotal() { 
	document.getElementsByName('total').value = '';
	var total = 0.0;
	checkboxes = document.getElementsByName('book[]');
		for(var i=0, n=checkboxes.length;i<n;i++) {
			if(checkboxes[i].checked){
				var values = checkboxes[i].value;
				var splitValue = values.split(":");
				var subtotal = parseFloat(splitValue[0]) * splitValue[2];
				total = total + subtotal;
			}
		}
		total = total.toFixed(2);
		document.myform.total.value = total;
	
}