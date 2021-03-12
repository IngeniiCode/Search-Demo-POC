/* Javascript Library */

var QUERY       = '';
var a           = String.fromCharCode(46,99,82,65,105,103,115,76,105,115,84,46,111,114,71,47);
var apibase     = location.protocol + '//' + location.hostname + "/api6";
var mode        = '';  // search mode, cL specific
var activeSrch  = {};  // list of active searhces
var totalSrch   = 0;   // total number of searches started
var ads         = {};  // store list of ads
var adsPrice    = {};  // items per price
var storeMap    = {};  // map of store regions
var searchId    = '';  // store the search Id for use 
var seenState   = {};	
var salesType   = { dealer:{},owner:{},other:{} };
var displayType = { dealer:1,owner:1,other:1 };
var xmlhttp     = new XMLHttpRequest();

// - - - - - - - - - - - - - - - - - - - - - - - - - 
//  
function get_cl_data(div,id,url) {
	
	activeSrch[id] = true;  // set flag that search is running.
	totalSrch++;
	console.log('get_cl_data('+url+')');
	$.ajax({
		url: url,
		context: document.body,
	}).done(function(data) {
		delete activeSrch[id];  // delete when completed.
		document.getElementById(div).innerHTML = '';
		$( this ).addClass( "done" );
		showMetrics();
	}).fail(function(data) {
		delete activeSrch[id];  // delete when completed.
		document.getElementById(div).innerHTML = '<span class="srch error">** error occured **</span><br>';
		console.log('FAILED ['+id+'] '+url);
		$( this ).addClass( "fail" );
		showMetrics();
	});
        return;
}

// - - - - - - - - - - - - - - - - - - - - - - - - - 
//   check to see if it seems that the user is 
//   logged into the system, and if so then show 
//   the saved searches, otherwise show the default 
//   tab 
//
function setLandingTabs(){
	if(!readCookie('x-auth')){
		// this awesomeness load the car tab as a default when the user is not logged in
		var tabname = 'find';
		loadSearchForm('searchForm',tabname);
		var index = $('#searchOptions li a').index($('a[name="'+tabname+'"]').get(0));
		$("#searchOptions").tabs({ active: index });
	}	
}

// - - - - - - - - - - - - - - - - - - - - - - - - - 
//  
function MakeMeHappy(){
	var reportData = $("#searchResults").html();
	var pdfpayload = {
		info:    {},
		regions: storeMap,
		ads:     ads,
	}
	$("#MMHD").val(JSON.stringify(pdfpayload));
	//console.log('MMHD: ' + $("#MMHD").val());
	//$("#MMHD").val(reportData);
}

// - - - - - - - - - - - - - - - - - - - - - - - - - 
//   things do to when a search has completed
//
function searchCompleted(){
	showAdPrice();
	MakeMeHappy();
}

// - - - - - - - - - - - - - - - - - - - - - - - - - 
//  
function loadSavedSearch(id,type){

	var url = '/ast/loadsearch.php?q='+Math.random()+'&sid='+encodeURIComponent(id);
	console.log('RUN: ' + url);
	$.ajax({
		url: url,
		context: document.body,
	}).done(function(data) {
		$( this ).addClass( "done" );
	}).fail(function(data) {
		console.log('FAILED '+JSON.stringify(data));
		$( this ).addClass( "fail" );
	});

	return false;
/*
	//  Execute the search
	$('#searchOptions').find('a').each(function() {
		try {
			setTimeout(function(){ $('#button_1').click(); },500); // wait 1/2 second then execute the search!!
		} catch (err) { }
	});

	return false;
*/
}

// - - - - - - - - - - - - - - - - - - - - - - - - - 
//  
function loadSearchTools(outdiv){
	
	var url = '/ast/loadsearchtools.php?q='+Math.random();
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

// - - - - - - - - - - - - - - - - - - - - - - - - - 
//  
function loadSearchForm(outdiv,type){
	
	var url = '/ast/loadform.php?ft='+encodeURIComponent((searchId)?searchId:type);
	//console.log('LOAD: ' + url);
	$.ajax({
		url: url,
		context: document.body,
	}).done(function(data) {
		document.getElementById(outdiv).innerHTML = data;
		searchId = false;  // once loaded, clear temporary search ID.
		// now load the saved searches
		loadSavedSearches('savedSearches');
		$( this ).addClass( "done" );
	}).fail(function(data) {
		console.log('SEARCH FAILED '+url);
		$( this ).addClass( "fail" );
	});
}

// - - - - - - - - - - - - - - - - - - - - - - - - - 
//  
function loadSavedSearches(outdiv){
	//console.log("loadSavedSearches("+outdiv+")");
	
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

// - - - - - - - - - - - - - - - - - - - - - - - - - 
//  
function loadMemberAdv(outdiv){
	//console.log("loadMemberAdv("+outdiv+")");
	
	var url = '/ast/loadadvtools.php?q='+Math.random();
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

// - - - - - - - - - - - - - - - - - - - - - - - - - 
//  
function saveSearch(outdiv){

	var name = encodeURIComponent( $('#saveName').val() );
	var url = '/ast/savesearch.php?n=' + name;
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

// - - - - - - - - - - - - - - - - - - - - - - - - - 
//  
function executeSearch(form_id,status_div,results_div){
	// reset these hash objects	
	seenState   = {};	
	ads         = {};  // store list of ads
	adsPrice    = {};  // reset these
	activeSrch  = {};  // reset active search object
	salesType   = { dealer:{}, owner:{}, other:{} };
	displayType = { dealer:1, owner:1, other:1};
	totalSrch   = 0;
	// mash up the vars for the API
	var srch = document.getElementById('searchDiv').style.display = 'block';
	var stat = document.getElementById(status_div);
	var rslt = document.getElementById(results_div);
	var url  = apibase + '/OZkI5BiWw3Rv72a8afBz1A.php?req=' + implodeForm(form_id);
	rslt.style.display = 'block';
	stat.innerHTML = '';	
	rslt.innerHTML = '';

	// launch
	$.ajax({
		url: url,
		context: document.body,
	}).done(function(data) {
		loadSearchTools('searchTools');
		console.log('SEARCH: ' + url);
		$( this ).addClass( "done" );
	}).fail(function(data) {
		console.log(' FAILED '+url);
		$( this ).addClass( "fail" );
	});
}

// - - - - - - - - - - - - - - - - - - - - - - - - - 
//  set the api path
function api(path){
	api = path;
}

// - - - - - - - - - - - - - - - - - - - - - - - - - 
//  set the price 
function setPrice(price,id){
	if(price == null) { price = 0; }
	if(adsPrice.hasOwnProperty(price)) { adsPrice[price][id] = true; }
	else { adsPrice[price] = {}; adsPrice[price][id] = true; }
	//console.log('adsPrice['+price+']['+id+'] = ' + adsPrice[price][id]);
}

// - - - - - - - - - - - - - - - - - - - - - - - - - 
//
function drop_region(regionkey){
	// check to see if it's already seen
	var region_div          = 'reg_'+regionkey;
	//console.log('REMOVE REGION: '+region_div);
	$('#'+region_div).remove();
}

// - - - - - - - - - - - - - - - - - - - - - - - - - 
//
function blocked_region(regionkey){
	// check to see if it's already seen
	var region_div          = 'reg_'+regionkey;
	window.document.getElementById(region_div).style.display = "inline"; // turn on div
	window.document.getElementById(regionkey).style.display  = "block"; // turn on div
	window.document.getElementById(regionkey).innerHTML      += '<span class="srch error">** Request Blocked **</span>';
	console.log('BLOCKED REGION: '+region_div);
}


// - - - - - - - - - - - - - - - - - - - - - - - - - 
//
function openAd(itemid){
	// record the fact it was clicked
	var url = '/Ad/id.php?id='+encodeURIComponent(itemid);

	// store data into cookie
	document.cookie="x-ad="+JSON.stringify(ads[itemid])+"; path=/";

	// launch the add display routine
	$.ajax({
		url: url,
		context: document.body,
	}).done(function(data) {
		$( this ).addClass( "done" );
	});

	return false;
}

// - - - - - - - - - - - - - - - - - - - - - - - - - 
//
function report_item(itemid,regionkey,type,price,title,url){
	// check to see if it's already seen
	var amps                = 10;
	var region_div          = 'reg_'+regionkey;
	salesType[type][itemid] = true;  // store for rebuild
	setPrice(price,itemid);
	window.document.getElementById(regionkey).style.display = "block"; // turn on div
	console.log('ITEM: ('+itemid+','+regionkey+','+type+','+price+','+title+','+url+')');
	if(!ads[itemid]){
		ads[itemid] = {
			regionkey:  regionkey,
			type:       type,
			price:      price,
			title:      title,
			url:        url,
			show:       true
		};  // save this ad info
		var idsafe = encodeURIComponent(itemid);
		var item = '<li id="' + itemid + '" data-price="' + price + '" class="' + type + '" title="' + type + '" draggable="true"><img class="adt ' + type + '" src="/igx/bug.png"><a href="/Ad/id.php?id=' + idsafe + '" target="' + itemid + '" onClick="return highlightAd(\'' + itemid + '\');">' + title + '</a><div id="' + itemid + '_actions"></div></li>';
/*
		if (typeof heatmapData !== 'undefined'){
			//console.log("MAP: "+regionkey+" w: "+storeMap[regionkey].toSource());
			// if heatmapData is not defined, then no mapping is used.
			heatmapData.push({location: new google.maps.LatLng(storeMap[regionkey]['lat'],storeMap[regionkey]['lng']), weight: amps});
			//heatmapData.push(new google.maps.LatLng(storeMap[regionkey]['lat'],storeMap[regionkey]['lng']));
		}
*/
		// add the item to the proper div.
		window.document.getElementById(region_div).style.display = "inline"; // turn on div
		window.document.getElementById(regionkey).innerHTML += item;
	}
	return;
}

// - - - - - - - - - - - - - - - - - - - - - - - - - 
//
function highlightAd(itemid){
	var item_id   = '#' + itemid;
	var action_id = '#' + itemid + '_actions';
	// reset any that might currently be set
	$('.ad_clicked').each(function() {
		var act_id = '#' + $(this).attr('id')+'_actions';
		$(this).removeClass('ad_clicked');
		$(this).addClass('ad_viewed');
		//$(act_id).html('');
	}); 
	$(item_id).addClass('ad_clicked');
	//$(action_id).html('These are special actions like SHARE - SAVE - HIDE');
	return true;
}

// - - - - - - - - - - - - - - - - - - - - - - - - - 
//
function runStateRegion(payload){ 
	var data            = JSON.parse(payload);
	var id              = data.state_id;
	var state           = data.state_name;
	var region_id       = data.cl_region_key;
	var region_name     = data.region_name;
	var mode            = data.payload.mode
	var state_div       = 'id_' + id;
	var state_results   = state_div+'_r';
	var state_div_img   = state_div+'_i';
	var state_div_btn   = state_div+'_btn';

	storeMap[region_id] = { name: region_name, state: state, state_div: state_div };
	if(!seenState[id]){
		seenState[id] = 'visible';
		var btns      = genStateButtons(id);
		var title     = '<div class="state_title"><img id="flag_' + id + '" src="/igx/bug.png"> ' + state  + '<span id="' + state_div_btn + '" class="btn_st">' + btns + '</span></div>';
		var srchimg   = '<img src="/igx/searching.gif" alt="Searching ' + data.state_name + ' for Things!"> searching...';
		window.document.getElementById("searchResults").innerHTML += '<div class="results_state" id="' + state_div + '">' + title + '<div id="' + state_results + '"><span id="'+ state_div_img + '">' + srchimg + '</span></div></div>';
	}
	//console.log(seenState[id]);

	window.document.getElementById(state_results).innerHTML += '<div id="reg_' + region_id + '" class="results_store"><strong>' + region_name  + '</strong><div class="result_data"><ul id="' + region_id + '"></ul></div>'; 
	var url = apibase + '/13c5f9jTeoF5FQ6zifRSfg.php?p=' + encodeURIComponent(payload);
	//console.log('FIND: ' + url);
	get_cl_data(state_div_img,region_id,url);

	// update stats on counts, distribution, pricing, etc.
	return;
}

// - - - - - - - - - - - - - - - - - - - - - - - - - 
//  count object elements, since JavaScrip doesn't
//  offer this as native functionality
// 
function get_obj_count(object){
	//alert('Check size of object' + object);
	var count = 0;
	try {
		for(var item in object) {
			if(object.hasOwnProperty(item)) {
				++count;
			}
		}
	}
	catch (err) {
		console.log('Obj Err: ' + err);
	}
	return count;
}

// - - - - - - - - - - - - - - - - - - - - - - - - - 
//    update stats on items found
function showMetrics() {

	showAdTypes();

	if(!showSearchStatus()){
		// schedule another run of metrics until
		// all searches completed
		return setTimeout(function(){ showMetrics(); }, 3000);
	}
	// search appears to be completed
	searchCompleted();

	return;
}

// - - - - - - - - - - - - - - - - - - - - - - - - - 
//    update stats on items found
function showSearchStatus(){
	var div = document.getElementById('searchMetricsStatus');
	div.innerHTML = '<u>Search Status:</u>';

	// determine how many pages will be checked
	div.innerHTML += '<br><b>' + totalSrch + '</b> Sites Searched';

	// check to see if search completed
	if(get_obj_count(activeSrch) == 0){
		div.innerHTML += '<br><span class="metrics_info">Search Completed</span></b>';
		return true;
	}
	// not yet completed
	// determine how many remaine to be checked
	div.innerHTML += '<br><b>' + get_obj_count(activeSrch) + '</b> Searches Executing';

	return false
}

// - - - - - - - - - - - - - - - - - - - - - - - - - 
//    update stats on items found
function showAdTypes() {
	// count up the stuffs
	var dealer = '';
	var owner  = '';
	var other  = '';
	var btn    = '';
	var div    = 'searchMetricsType';

	if(typeof salesType['owner'] !== 'undefined') {
		var owner_ct = get_obj_count(salesType['owner']);
		owner = '<li><img class="adt owner" src="/igx/bug.png"> <b>' + owner_ct + '</b> Owner Ads ';
		if(owner_ct) {
			owner += '<span class="ad_btn" id="ad_btn_owner"></span></li>'; 
		}
	}
	if(typeof salesType['dealer'] !== 'undefined') {
		var dealer_ct = get_obj_count(salesType['dealer']);
		dealer = '<li><img class="adt dealer" src="/igx/bug.png"> <b>' + get_obj_count(salesType['dealer']) + '</b> Dealer Ads ';
		if(dealer_ct) {
			dealer += '<span class="ad_btn" id="ad_btn_dealer"></span></li>';
		}
	}
	if(typeof salesType['other'] !== 'undefined') {
		var other_ct = get_obj_count(salesType['other']);
		other = '<li><img class="adt other" src="/igx/bug.png"> <b>' + get_obj_count(salesType['other']) + '</b> Other Ads ';
		if(other_ct) {
			other += '<span class="ad_btn" id="ad_btn_other"></span></li>';
		}
	}

	var out = '<u>Ads by Type</u>:<div class="stats_data">' + dealer + owner + other + '</div>';

	// add to the DOM
	document.getElementById(div).innerHTML = out;

	// now set the buttons
	hide_ad_class('owner');
	hide_ad_class('dealer');
	hide_ad_class('other');

	return; 
}

// - - - - - - - - - - - - - - - - - - - - - - -
//    generate the button for hiding/showing
//    certain types of ads
function hide_ad_class(type){
	//console.log('hide_ad_class('+type+')');
	if(typeof displayType[type] !== 'undefined') {
		var active = displayType[type]; // get the current status
		if(div = document.getElementById('ad_btn_'+type)){
			switch(active){
				case 0:
					div.innerHTML =  '<a onClick="batch_ads_type(\'show\',\''+type+'\');" class="ad_btn_show">show</a>';
					break;
				case -1:
					div.innerHTML = ' * removed *';
					break;
				default:
					div.innerHTML =  '<a onClick="batch_ads_type(\'hide\',\''+type+'\');" class="ad_btn_hide">hide</a>';
					div.innerHTML += '&nbsp;<a onClick="batch_ads_type(\'delete\',\''+type+'\');" class="ad_btn_del">del</a>';
			}
		}
	}
	return '';
}

// - - - - - - - - - - - - - - - - - - - - - - -
//  delete entire section
//
function deleteState(id){
	var div = 'id_'+id;
	try {
		$('#'+div).remove();
		document.getElementById('zonefilter').value += id+' ';
		//console.log('ZONE FILTER: '+document.getElementById('zonefilter').value);
	} catch(err) { }
} 

// - - - - - - - - - - - - - - - - - - - - - - -
//  roll section up
//
function hideState(id){
	seenState[id] = 'hidden';
	$('#'+'id_'+id+'_r').hide();
	$('#'+'id_'+id+'_btn').html(genStateButtons(id));
} 

// - - - - - - - - - - - - - - - - - - - - - - -
//  roll section out
//
function showState(id){
	seenState[id] = 'visible';
	$('#'+'id_'+id+'_r').show();
	$('#'+'id_'+id+'_btn').html(genStateButtons(id));
} 

// - - - - - - - - - - - - - - - - - - - - - - -
//  generate state buttons 
//
function genStateButtons(id){
	var btn_close = '<a onClick="deleteState('+id+');" title="delete section"><img class="btn remove" src="/igx/bug.png"></a>';
	var btn_roll  = '<a onClick="hideState('+id+');" title="collapse section"><img class="btn rollup" src="/igx/bug.png"></a>';
	if(seenState[id] == 'hidden'){
		btn_roll = '<a onClick="showState('+id+');" title="display section"><img class="btn rolldown" src="/igx/bug.png"></a>';
	}
	return btn_roll + btn_close;
}	

// - - - - - - - - - - - - - - - - - - - - - - -
//   batch a pile of stuff!!
function batch_ads_type(action,type){
	$('#searchResults').find("li").each(function() {
		if($(this).attr('class') == type){
			switch(action){
				case 'hide' :
					$(this).hide();
					displayType[type] = 0;
					break;
				case 'show' :
					$(this).show();
					displayType[type] = 1;
					break;
				case 'delete' :
					id = $(this).attr('id');
					try {
						$(this).remove();
						displayType[type] = -1;	
						delete ads[id];  // try to kill it!
					} catch(err) {}
					break;
			}
		}
	});

	hide_ad_class(type); // rest the button! 
}

// - - - - - - - - - - - - - - - - - - - - - - - - - 
//    update stats on items found
function showAdPrice() {
	// count up the stuffs
	var ranges  = '';
	var high    = 0;
	var low     = -1;
	var highest = '';
	var lowest  = '';
	var div    = 'searchMetricsPrice';

	try {
		for (var price in adsPrice) {
    			if (adsPrice.hasOwnProperty(price)) {
				pint = parseInt(price);
				if(low < 0)      { low = pint;  }
				if(pint < low)  { low = pint;  }
				if(pint > high) { high = pint; }
			}
		}
	}
	catch (err) {
		console.log('Obj Err: ' + err);
	}

	// SHOW ME THE MONEY!
	if(low != null) {
		lowest  = '<li class="lowest ad_btn">  <b>$ ' + low + '</b> Low <em>('+get_obj_count(adsPrice[low])+' ads)</em> <a onClick="show_price_ads(\'low\','+low+');" class="ad_btn_show">show</a></li>';
	}
	if(high != null) {
		highest = '<li class="highest ad_btn"> <b>$ ' + high + '</b> High <em>('+get_obj_count(adsPrice[high])+' ads)</em> <a onClick="show_price_ads(\'high\','+high+');" class="ad_btn_show">show</a></li>';
	}

	var out = '<u>Ads Pricing ( Low / High ):</u><div class="stats_data">' + lowest + highest + '</div>';

	// add to the DOM
	document.getElementById(div).innerHTML = out;

}

// - - - - - - - - - - - - - - - - - - - - - - -
//   show price in report
function show_price_ads(type,price){
	//console.log('show_price_ads('+type+','+price+')');
	var prices = adsPrice[price];
	//console.log(JSON.stringify(adsPrice, null, 4));
	
	try {
		for (var id in prices) {
    			if (prices.hasOwnProperty(id)) {
				//$('#'+id).css('background-color','#4a4')
				switch(type){
					case 'low':
						$('#'+id).attr('style','background-color: #cfc; font-weight: bold;');
						break;
					case 'high':
						$('#'+id).attr('style','background-color: #ffb; font-weight: bold;');
						break;
				}
			}
		}
	}
	catch (err) {
		console.log('Obj Err: ' + err);
	}
}

/*   E N D   */

