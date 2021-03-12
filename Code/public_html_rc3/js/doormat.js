var epc1   = (new Date);
var epc2   = (new Date);
var nd     = new Date/1E3|0;
var expt   = nd + 14400;   // 4 hours in secionds
epc2.setTime(expt);
var next   = epc2.toGMTString();
var ckey   = 'x-welcome-mat';
var geokey = 'x-ninja-geo';

/*  THIS IS THE TEMPORARY GENERIC PROCESS */
	// search form loading
	loadSearchForm('searchForm','find');
	loadSearchTools('searchTools');
	//loadSavedSearches('savedSearches');
	$("#MMH").submit(function(){
		$("#MMHD").val($("#searchResults").html());
	});	
/*  // END  TEMPORARY BLOCK  //  */

/*
if(!getCookie(ckey)){
	// show the test message
	alert('WELCOME TO OUTSPOKEN NINJA');

	bakeCookie(ckey,'welcome mat 1',4);
}
else {
	// search form loading
	loadSearchForm('searchForm','find');
	loadSearchTools('searchTools');
	//loadSavedSearches('savedSearches');
	$("#MMH").submit(function(){
		$("#MMHD").val($("#searchResults").html());
	});	
}
*/
/*
  F U N C T I O N S 
*/

