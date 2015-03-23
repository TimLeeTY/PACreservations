function showForm(x,room) {
   	window.alert("select" + x +"_"+room); 
	document.getElementByID("select" + x +"_"+room).selected = true;
	document.getElementById("form_" + room).style.display="visible";
}