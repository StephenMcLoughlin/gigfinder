function getElement(x) {
	return document.getElementById(x);
}

function toggleElement(x) {
	var x = getElement(x);
	if(x.style.display == 'block') {
		x.style.display = 'none';
	} else {
		x.style.display = 'block';
	}
}