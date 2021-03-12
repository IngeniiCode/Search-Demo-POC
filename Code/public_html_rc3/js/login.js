/* JavaScript Library */

// - - - - - - - - - - - - - - - - - - - - - - - - - 
//
function launch_login(){
	var block = window.parent.document.getElementById("loginScreener");
	var lcard = window.parent.document.getElementById("loginCard");
	// show the parts
	block.style.display = 'block';
	lcard.style.display = 'block';
	loadLoginForm(lcard);
	checkFBlogin();	

	return;	
}

// - - - - - - - - - - - - - - - - - - - - - - - - - 
//
function logout(){
        var xmlhttp = new XMLHttpRequest();
        var url = '/Profile/_lout.php';

        $.ajax({
                url: url,
                context: document.body,
        }).done(function(data) {
                $( this ).addClass( "done" );
		updateMenu('loggggeeddoouuuutttt');
		refreshPage('logout');
        }).fail(function(data) {
                console.log('FAILED '+url);
                $( this ).addClass( "fail" );
        });
}

// - - - - - - - - - - - - - - - - - - - - - - - - - 
//
function login_member(){
	loginDestroy();
}

// - - - - - - - - - - - - - - - - - - - - - - - - - 
//
function loginStatus(divid,msg){
	return window.parent.document.getElementById(divid).innerHTML = msg;
}

// - - - - - - - - - - - - - - - - - - - - - - - - - 
//
function refreshPage(action){
	// look for specific IDs, and when found
	// trigger a page refresh
	if($('#DASH').length){
		//alert("REFRESH DASHBOARD");	
		location.reload(true);
	}
	if(action == 'logout'){
		if($('#searchBody').length){
			location.reload(true);
		}
	}
}

// - - - - - - - - - - - - - - - - - - - - - - - - - 
//
function updateMenu(mode){
	switch(mode){
		case 'in':
			//window.parent.document.getElementById('menu_profile').className = 'btn_menu menu_visible';
			window.parent.document.getElementById('menu_logout').className  = 'btn_menu menu_visible';
			window.parent.document.getElementById('menu_logon').className   = 'btn_menu menu_hidden';
			// update search menus if they are available
			try {
				loadSearchTools('searchTools');
				loadSavedSearches('savedSearches');
				loadMemberAdv('member_adv');
			} catch(err) {} 

			break;
		default:
			//window.parent.document.getElementById('menu_profile').className = 'btn_menu menu_hidden';
			window.parent.document.getElementById('menu_logout').className  = 'btn_menu menu_hidden';
			window.parent.document.getElementById('menu_logon').className   = 'btn_menu menu_visible';
	}
}

// - - - - - - - - - - - - - - - - - - - - - - - - - 
//
function loginCancel(){
        var xmlhttp = new XMLHttpRequest();
        var url = '/Profile/_locn.php';

        $.ajax({
                url: url,
                context: document.body,
        }).done(function(data) {
                $( this ).addClass( "done" );
        }).fail(function(data) {
                console.log('FAILED '+url);
                $( this ).addClass( "fail" );
        });
	loginDestroy();
}

// - - - - - - - - - - - - - - - - - - - - - - - - - 
//
function loginDestroy(){
	var block = window.parent.document.getElementById("loginScreener");
	var lcard = window.parent.document.getElementById("loginCard");

 	/* empty the divs */
	lcard.innerHTML = '';

	/* remove from display */	
	block.style.display = 'none';
	lcard.style.display     = 'none';
}

// - - - - - - - - - - - - - - - - - - - - - - - - -
//
function loadLoginForm(lcard){
        var xmlhttp = new XMLHttpRequest();
        var url = '/Membership/_lof.php';

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
function loginMember(form,status){
	var sblock = window.parent.document.getElementById(status);
	sblock.innerHTML = '';

	/* translate data into object */	
	var data = FormToObject(form);  // THIS IS HOW YOU DO IT!!

	/* validate before submitting */
	var uname = normalize(data['user']);
	var pwd   = trim(data['pwd']);

	/* check to see if the e-mail looks legit */
	if(!legit_email(uname)){
		sblock.innerHTML += '<li>E-mail does not look valid.  Valid e-mail required for signin.</li>';
		//console.log('E-mail does not look valid.  Valid e-mail required for signin.');
	}

	if(sblock.innerHTML == ''){
		/* passed validations -- post to controller */
		var xmlhttp = new XMLHttpRequest();
		var url = '/Profile/_lofm.php';
		$.ajax({
			url: url,
			type: 'POST',
			data: data,
			context: document.body,
		}).done(function(data) {
			form.innerHTML = data;
			console.log('SIGNUP SENT.  RESPONSE: ' + data);
			$( this ).addClass( "done" );
		}).fail(function(data) {
			status.innerHTML = 'Login Failed';
			alert('Data: ' + JSON.stringify(data));
			console.log('LOGIN LOAD FAILED '+url);
			$( this ).addClass( "fail" );
		});
	}
	return;

}

// - - - - - - - - - - - - - - - - - - - - - - - - -
//
function signupMember(form,status){
	var sblock = window.parent.document.getElementById(status);
	sblock.innerHTML = '';

	/* translate data into object */	
	var data = FormToObject(form);  // THIS IS HOW YOU DO IT!!

	/* validate before submitting */
	var uname = normalize(data['new_user']);
	var pwd1  = trim(data['pwd']);
	var pwd2  = trim(data['pwdconf']);

	/* check to make sure the passwords match */
	if(pwd1 != pwd2) {
		sblock.innerHTML += '<li>Passwords do not match.</li>';
	}

	/* check to see if the e-mail looks legit */
	if(!legit_email(uname)){
		sblock.innerHTML += '<li>E-mail does not look valid.  Valid e-mail required for signin.</li>';
	}

	if(sblock.innerHTML == ''){
		/* passed validations -- post to controller */
		var xmlhttp = new XMLHttpRequest();
		var url = '/Profile/_losu.php';
		$.ajax({
			url: url,
			type: 'POST',
			data: data,
			context: document.body,
		}).done(function(data) {
			console.log('SIGNUP SENT.  RESPONSE: ' + JSON.stringify(data));
			$( this ).addClass( "done" );
		}).fail(function(data) {
			sblock.innerHTML += 'SIGNUP FAILED<br>' + JSON.stringify(data);
			console.log('LOGIN LOAD FAILED '+url);
			$( this ).addClass( "fail" );
		});
	}
	return;
}

// - - - - - - - - - - - - - - - - - - - - - - - - -
//
function loginFacebook(form,status){


}
