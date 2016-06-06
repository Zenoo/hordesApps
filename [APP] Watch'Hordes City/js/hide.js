function unhide(divID) {
	var item = document.getElementById(divID);
	if (item) {
		item.className=(item.className=='hidden')?'unhidden':'hidden';
	}
}
function unhideTr(divID) {
	var item = document.getElementById(divID);
	if (item) {
		item.className=(item.className=='hidden')?'unhiddenTr':'hidden';
	}
}
function unhideTbody(divID) {
	var item = document.getElementById(divID);
	if (item) {
		item.className=(item.className=='hidden')?'unhiddenTbody':'hidden';
	}
}
function changeText() {
	var element = document.getElementById('changeT');
	if (element.innerHTML === 'Voir plus') element.innerHTML = 'Voir moins';
	else {
		element.innerHTML = 'Voir plus';
	}
}