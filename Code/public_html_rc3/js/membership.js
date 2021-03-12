/*
  M E M B E R S H I P 
*/

// - - - - - - - - - - - - - - - - - - - - - - - - - 
//
function signupBasic(){
	var block = window.parent.document.getElementById("loginScreener");
	var lcard = window.parent.document.getElementById("loginCard");
	// show the parts
	block.style.display = 'block';
	lcard.style.display = 'block';
	loadSignupForm('basic',lcard);

	return;	
}

// - - - - - - - - - - - - - - - - - - - - - - - - - 
//
function signupCancel(){
	var block = window.parent.document.getElementById("loginScreener");
	var lcard = window.parent.document.getElementById("loginCard");

 	/* empty the divs */
	lcard.innerHTML = '';

	/* remove from display */	
	block.style.display = 'none';
	lcard.style.display = 'none';
}

// - - - - - - - - - - - - - - - - - - - - - - - - -
//
function loadSignupForm(type,lcard){
        var xmlhttp = new XMLHttpRequest();
        var url = '/Membership/_su'+type+'.php';

        $.ajax({
                url: url,
                context: document.body,
        }).done(function(data) {
		lcard.innerHTML = data;
                $( this ).addClass( "done" );
        }).fail(function(data) {
                console.log('LOGIN LOAD FAILED '+url);
                $( this ).addClass( "fail" );
        });
	return '--';
}

// - - - - - - - - - - - - - - - - - - - - - - - - -
//
function loginFacebook(form,status){


}
