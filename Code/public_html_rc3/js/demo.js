/*
  D E M O    J A V A S C R I P T    A W E S O M E 
*/

/* 
  G E N E R A L I Z E D   V A R S 
*/
var apibase   = location.protocol + '//' + location.hostname + "/api7";
var PROVIDERS = {};  // list of providers will be stuffed in here
var isSrvc    = '/^srvc_/';

/*
  M E T H O D S 
*/

function searchTerms(d){
	
	payload = implode_form(d);

	d.getElementById('demo_results').style.display = "block";
	rdiv = d.getElementById('results_status');   // locate the output div 
	rdiv.innerHTML = '';  // clear out the block, prepare for data!

	launchEngines(rdiv,apibase+'/terms.',payload);
	//console.log('APIBASE: ' + apibase);

}

// - - - - - - - - - - - - - - - - - - - - - - -
//
function searchServices(d){
	
	var payload = implode_form(d);

	d.getElementById('demo_results').style.display = "block";
	rdiv = d.getElementById('results_status');   // locate the output div 
	rdiv.innerHTML = '';  // clear out the block, prepare for data!

	searchDatabase(rdiv,payload);
}

// - - - - - - - - - - - - - - - - - - - - - - -
//
function searchDatabase(rdiv,payload){
	setStatus(rdiv,'providers','Provider Database');
	url = apibase + '/providers.php?ep=' + payload;
	console.log('P: ' + url);
	launch(url);
}

// - - - - - - - - - - - - - - - - - - - - - - -
//
function launchEngines(rdiv,api,payload){
	searchGoogle(rdiv,api,payload);
	searchYahoo(rdiv,api,payload);
	searchBing(rdiv,api,payload);
	searchDDGo(rdiv,api,payload);
}


// - - - - - - - - - - - - - - - - - - - - - - -
//
function searchGoogle(rdiv,api,payload){
	setStatus(rdiv,'google','Google');
	launch(api + 'google.php?ep=' + payload);
}

// - - - - - - - - - - - - - - - - - - - - - - -
//
function searchYahoo(rdiv,api,payload){
	setStatus(rdiv,'yahoo','Yahoo');
	launch(api + 'yahoo.php?ep=' + payload);
}

// - - - - - - - - - - - - - - - - - - - - - - -
//
function searchBing(rdiv,api,payload){
	setStatus(rdiv,'bing','Bing');
	launch(api + 'bing.php?ep=' + payload);
}

// - - - - - - - - - - - - - - - - - - - - - - -
//
function searchDDGo(rdiv,api,payload){
	setStatus(rdiv,'ddgo','Duc Duc Go');
	launch(api + 'ddgo.php?ep=' + payload);
}

// - - - - - - - - - - - - - - - - - - - - - - -
//
function setStatus(rdiv,engine,title){
	rdiv.innerHTML += '<div id="status_'+engine+'" class="engine_search">'+title+'...<br><img src="/igx/searching.gif"></div>';
}

// - - - - - - - - - - - - - - - - - - - - - - -
//
function launch(url){
	
	var xmlhttp = new XMLHttpRequest();
        //console.log('** SEARCH: ' + url);
        
        $.ajax({
                url: url,
                context: document.body,
        }).done(function(data) {
                $( this ).addClass( "done" );
		displayProviders(data);	
        }).fail(function(data) {
                console.log('SEARCH FAILED ' + url);
                console.log('SEARCH FAILED');
                $( this ).addClass( "fail" );
        });

        return;

}	

// - - - - - - - - - - - - - - - - - - - - - - -
//
function cbx_activate(input){
	if(input.checked == true){
		input.value = "ON";
		input.parentNode.className = 'boldish';
	}
	else {
		input.value = "";
		input.parentNode.className = '';
	}
	return true;
}

// - - - - - - - - - - - - - - - - - - - - - - -
//
function displayProviders(DTA){

	PROVIDERS = DTA; // store 
	var categories = {};
	var provider_categories = {};
	var pagect = 0;
	var rdDiv = window.document.getElementById('results_data');
	var spDiv = window.document.getElementById('status_providers');
	
        spDiv.innerHTML = '<p><b>Providers Database Search</b></p><p><em>Completed</em></p><p><b>'+PROVIDERS.length+' Providers Found</b></p>';

	if(PROVIDERS.length >= 250){
		spDiv.innerHTML += '<br><u>NOTE:</u> <em>Max record cound of 250 reached.</em>';
	}

	// update the results data
	rdDiv.style.display   = 'block';
	rdDiv.innerHTML = '<b>Provider Services Breakdown</b><br>';

	// Show the provider data recovered from search
	for(var i = 0; i < PROVIDERS.length; i++){
		var PROV = PROVIDERS[i];
		for(var key in PROV){
			if(key.match(/^srvc_/)){
				//provider_categories[key][PROV.id] = PROV;
				//categories[key] = PROV[key];
			}
		}
		rdDiv.innerHTML += provider_block(key,PROV);
		if(pagect++ > 50){
			i = PROVIDERS.length;  // end the loop early	
		}
	}

/*
	for(var category in categories){
		rdDiv.innerHTML += '<p><b>'+ provider_categories[key].length + ' ' + categories[category] + ' Providers</p>';
	}
*/
	
}

function provider_block(key,PROV){
	var id = PROV['id'];
	var loc   = 'in: '+PROV['location_str'];
	var prov  = '<a href="'+wrap_url(PROV)+'" target="'+id+'" onMouseOver="'+wrap_ajax(PROV)+'"><b>'+PROV['desc_short']+'</b></a><div id="'+id+'_page"></div>';
	var title = '<div class="provider_title">'+prov+'<br clear=all><span class="inloc">'+loc+'</span></div>';
	var srvc  = create_services_block(PROV);
	var rats  = create_ratings_block(PROV);
	var parts = '<table><tr><td valign=top>'+srvc+'</td><td valign=top>'+rats+'</td></tr></table>';

	return '<div id="'+id+'" data-key="'+key+'" class="provider_block">'+title+parts+'</div>';
}

function ison(val){
	if(val) return "class='active'";
	return "class='na'";
}

function wrap_url(PROV){
	if(PROV['home_url']) return PROV['home_url'];	
	return PROV['engine_url']
}

function wrap_ajax(PROV){
	url = (PROV['home_url'])?PROV['home_url']:PROV['engine_url'];
	return "load_provider_page('"+PROV['id']+"_page','"+url+"');";
}

function load_provider_page(id,url){
	$(id).load(url);
}

function gen_stars(val){
	var stars = '<span class="stars">';
	for(i=1;i<=val;i++){
		if(val - i > 0){
			stars += '&#9733';
		}
	}	
	return stars + '</span>';
}

function create_ratings_block(PROV){
	var block     = '<div class="ratings_block"><b>Servcie Provider Ratings</b>:';
	var provStars = gen_stars((Math.floor((Math.random() * 8) + 0) / 2) + 1);
	var cliStars  = gen_stars((Math.floor((Math.random() * 8) + 0) / 2) + 1);
	block += '<br class="rating">Service Rating: <span class="stars">' + provStars + '</span>';
	block += '<br class="rating">Client Rating: <span class="stars">' + cliStars + '</span>';
	block += '</div>';
	return block;
}

function create_services_block(PROV){
	var x = 'x';
	var block = '<div class="services_block"><!-- <b>Services</b>-->';
	block += '<table class="services_table">';
	block += '<tr>';
	block += '<td '+ison(PROV['srvc_dr'])+'>Drug Rehabilitation</td>';
	block += '<td '+ison(PROV['srvc_ar']) +'>Alcohol Treatment</td>';
	block += '</tr>';
	block += '<tr>';
	block += '<td '+ison(PROV['srvc_dc'])+'>Drug counceling</td>';
	block += '<td '+ison(PROV['srvc_ac'])+'>Alcholol Counceling</td>';
	block += '</tr>';
	block += '<tr>';
	block += '<td '+ison(PROV['srvc_t4c'])+'>Thinking for Change</td>';
	block += '<td '+ison(PROV['srvc_co'])+'>Counceling</td>';
	block += '</tr>';
	block += '<tr>';
	block += '<td '+ison(PROV['srvc_mhs'])+'>Mental Health Services</td>';
	block += '<td '+ison(PROV['srvc_mds'])+'>Medical Services</td>';
	block += '</tr>';
	
	block += '</table>';
	block += '</div>';
	return block;
}

// - - - - - - - - - - - - - - - - - - - - - - -
//
function implode_form(d){
	var form  = d.getElementById("search_form").elements;
	var fdata = {};
	for(var i = 0; i < form.length; i++){
		var name  = form[i].name;
		var value = form[i].value;
		fdata[name] = value;
	}

	return encodeURIComponent(JSON.stringify(fdata, null, 1));
}
