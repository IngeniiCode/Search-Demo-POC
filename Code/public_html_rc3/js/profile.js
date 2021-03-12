/* Javascript Library */

var apibase     = location.protocol + '//' + location.hostname + "/api3";
var xmlhttp     = new XMLHttpRequest();

// - - - - - - - - - - - - - - - - - - - - - - - - - 
//  
function loadSavedSearch(id,type){
console.log('PROFILE: loadSavedSearch('+id+','+type+')');
	searchId = id;
	// at some point load up the parameters
}

// - - - - - - - - - - - - - - - - - - - - - - - - - 
//  
function loadSavedSearches(outdiv){
	
	var url = '/ast/loadsearches.php?q='+Math.random();
	$.ajax({
		url: url,
		context: document.body,
	}).done(function(data) {
		document.getElementById(outdiv).innerHTML = data;
		$( this ).addClass( "done" );
	}).fail(function(data) {
		console.log('FAILED '+JSON.stringify(data));
		$( this ).addClass( "fail" );
	});
}

/*   E N D   */

