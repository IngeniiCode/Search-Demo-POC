
// - - - - - - - - - - - - - - - - - - - - - - -
//
function setVisibility(id, visibility) {
	document.getElementById(id).style.display = visibility;
}

// - - - - - - - - - - - - - - - - - - - - - - -
//
function ShowSurvey(){
	document.getElementById('survey_main').style.display = 'inline';
}	

// - - - - - - - - - - - - - - - - - - - - - - -
//
function setStars(hidden_id,stars) {
	var unsetStar = '&#9734;';  // default star
	var setStar   = '&#9733;';  // set star
	// check to see if the value is the same as currently set, and if so
	// assume the user wanted to un-set the value by double clicking
	if(document.getElementById(hidden_id).value == stars) {
		stars = 0;  // set to zero, which will in effect disable the setting
	}
	document.getElementById(hidden_id).value = stars;  // set the hidden value
	for(i=1; i<=5; i++) {
		// set all the stars back to default 
		var synth_id = hidden_id + '_' + i;
		document.getElementById(synth_id).innerHTML   = unsetStar;
		document.getElementById(synth_id).style.color = 'GoldenRod';
		// set the selected stars to 'ON' which is solid+red
		if(i <= stars) {
			document.getElementById(synth_id).innerHTML   = setStar;
			document.getElementById(synth_id).style.color = 'red';
		}
	}	
	return stars;
}

// - - - - - - - - - - - - - - - - - - - - - - -
//
function validate_zip(elem) {
	// alert if it's blank
	var zip = trim(elem.value);
	var pat = /^\d{1,5}$/;
	if (zip == '') {
		return alert("ZIP Code shouldn't be Blank.  Please help your fellow Ninjas and enter a zip code. Thanks!");
		
	}
	// alert if it's not a number
	if(!pat.test(zip)){
		alert("OutpsokenNinja is only supporting US Postal / Zip codes at this time. (" + zip + ") was not recognized as a valid US zip code.");
		zip = zip.replace(/\D/gi,'');  // remove non numeric parts
	}
	// set back the sanitized value if it's good
	elem.value = zip.substring(0,5);   // trim it down to max of 5 chars

	return true;
}

// - - - - - - - - - - - - - - - - - - - - - - -
//
//  trimming function because it's awesome
function trim (str) {
	return str.replace (/^\s+|\s+$/g, '');
}

// - - - - - - - - - - - - - - - - - - - - - - -
//
//  trimming function because it's awesome
function normalize (str) {
	return trim(str).toLowerCase(); 
}

// - - - - - - - - - - - - - - - - - - - - - - -
//
function readCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for(var i=0;i < ca.length;i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1,c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
    }
    return null;
}

// - - - - - - - - - - - - - - - - - - - - - - -
//    hack to convert a form into a serialized 
//    JSON object, which can be passed to the API
//    for processing
//
function implodeForm(form_id){
	var fdata = FormToObject(form_id);
	return encodeURIComponent(JSON.stringify(fdata, null, 1));
}

// - - - - - - - - - - - - - - - - - - - - - - -
//    hack to convert a form into a serialized 
//    JSON string 
//
function jsonForm(form_id){
	var fdata = FormToObject(form_id); 
	return JSON.stringify(fdata, null, 1);
}

// - - - - - - - - - - - - - - - - - - - - - - -
//    hack to convert a form into an object 
//
function FormToObject(form_id){
	var elems = document.getElementById(form_id).elements;
	var fdata = {};
	for(var i = 0; i < elems.length; i++){
		var name    = elems[i].name;
		switch(elems[i].type) {
			case 'checkbox':
				if(elems[i].checked) { fdata[name] = true; }
				break;
			case 'radio':
				if(elems[i].checked) { fdata[name] = elems[i].value; }
				break;
			default :
				 fdata[name] = elems[i].value;
		}
	}
	return fdata;
}

// - - - - - - - - - - - - - - - - - - - - - - -
//    check to see if e-mail looks legit
function legit_email(str){
	var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
	return re.test(str);
} 

// - - - - - - - - - - - - - - - - - - - - - - - - -
//
function loadFeedbackForm(fcard){
        var xmlhttp = new XMLHttpRequest();
        var url = '/_fdb.php';

        $.ajax({
                url: url,
                context: document.body,
        }).done(function(data) {
		fcard.innerHTML = data;
                $( this ).addClass( "done" );
        }).fail(function(data) {
                console.log('LOAD FEEDBACK '+url);
                $( this ).addClass( "fail" );
        });
	return '--';
}

// - - - - - - - - - - - - - - - - - - - - - - - - - 
//
function launch_feedback(){
	block = window.parent.document.getElementById("feedbackScreener");
	fback = window.parent.document.getElementById("feedbackCard");
	// show the parts
	block.style.display = 'block';
	fback.style.display = 'block';
	loadFeedbackForm(fback);
	return;	
}

// - - - - - - - - - - - - - - - - - - - - - - - - - 
//
function hide_feedback(){
	block = window.parent.document.getElementById("feedbackScreener");
	fback = window.parent.document.getElementById("feedbackCard");
	// hide the parts
	block.style.display = 'none';
	fback.style.display = 'none';
	fback.innerHTML = '';
	return;	
}

// - - - - - - - - - - - - - - - - - - - - - - - - -
//  toggle a div up/down
function toggleDiv(div){
	// check to see if it's visible
	$(div).fadeToggle();
	return false;
}

// - - - - - - - - - - - - - - - - - - - - - - - - -
//
function send_feedback(form_id){

	/* translate data into object */	
	var feedback = FormToObject(form_id);  // THIS IS HOW YOU DO IT!!

	/* passed validations -- post to controller */
	var xmlhttp = new XMLHttpRequest();
	var url = '/_fdbr.php';
	$.ajax({
		url: url,
		type: 'POST',
		data: feedback,
		context: document.body,
	}).done(function(data) {
		console.log('FEEDBACK SENT.  RESPONSE: ' + data);
		$( this ).addClass( "done" );
	}).fail(function(data) {
		console.log('FEEDBACK SAVE FAILED '+data);
		$( this ).addClass( "fail" );
	});
	hide_feedback();

	return;
}

// - - - - - - - - - - - - - - - - - - - - - - - - -
//   cookie writer function
//
function bakeCookie (key,value,hours) {
	
	var date = new Date();
	// set some defaults
	key   = key   || 'x-ninja';
	value = value || 'n-generic';
	hours = hours || 4;

	// Get unix milliseconds at current time plus number of days
	date.setTime(+ date + (hours * 60 * 60 * 1000)); //4 * 60 * 60 * 1000

	// set Ninja Cookie
	window.document.cookie = key + "=" + value + "; expires=" + date.toGMTString() + "; path=/";
};

// - - - - - - - - - - - - - - - - - - - - - - - - -
//   reader runction
//
function getCookie(key) {
	var ckey = key + "=";
	var ca = document.cookie.split(';');

	// loop cookies, find what we're looking for    
	for(var i=0;i < ca.length;i++) {
		var c = ca[i];
		while (c.charAt(0)==' ') { 
			c = c.substring(1,c.length);
		}
		if (c.indexOf(ckey) == 0) {
			return c.substring(ckey.length,c.length);
		}
	}
	return null;
}

// - - - - - - - - - - - - - - - - - - - - - - - - -
//  cookie monster function 
//
function cookieMonster(key){
	return writeCookie (key,'',-1);
}
