var util    = require('util');
var cheerio = require('cheerio');
var request = require('request');
var assert  = require('assert');
var isUrl   = new RegExp(/[-a-zA-Z0-9@:%_\+.~#?&//=]{2,256}\.[a-z]{2,4}\b(\/[-a-zA-Z0-9@:%_\+.~#?&//=]*)?/gi);
var DATA    = {};

var url = process.argv[2];

assert(isUrl.test(url), 'must provide a correct URL env variable');

request({
	method: 'GET',
	url: url,
}, function(err, response, body) {
	if (err) return console.error(err);

	//  look for the magical main url!
	$ = cheerio.load(body);

	try {	
		DATA['name']          = $('h1.kg-style1').text();
		DATA['home_url']      = $('a.r-track.att-track').attr('href');
		DATA['phone_num']     = $('li.info-phone').text();
	} catch(err) {}

	// address building
	try {
		//DATA['address_mashup'] = $('li.info-addr').text();
		$('li.info-addr').children().each(function() {
			DATA['address_mashup'] += $(this).text()+"\n";
			if( $(this).attr('itemprop') == 'streetAddress'){
				DATA['addr_street'] = $(this).text();
			}
			if( $(this).attr('itemprop') == 'addressLocality'){
				DATA['addr_city'] = $(this).text();
			}
			if( $(this).attr('itemprop') == 'addressRegion'){
				DATA['addr_state'] = $(this).text();
			}
			if( $(this).attr('itemprop') == 'postalCode'){
				DATA['addr_postal'] = $(this).text();
			}
		});
	} catch(err) {}

	// location scraping
	try {
		DATA['yahoo_map']      = $('a.render-map.r-track').attr('href');
		DATA['addr_latitude']  = $("meta[property='place:location:latitude']").attr("content");
		DATA['addr_longitude'] = $("meta[property='place:location:longitude']").attr("content");
		DATA['geolocation']    = geosafe(DATA['addr_latitude']) + ',' + geosafe(DATA['addr_longitude']);
	} catch(err) {}

	// info building
	try {
		$('ul.info-rel').find('strong').each(function() {
			if( $(this).text() == 'About:'){
				DATA['desc_short'] = $(this).parent().text();
			}
			if( $(this).text() == 'Categories:'){
				DATA['services'] = $(this).parent().text().split(':')[1].split(',').map(function(service) { return service.trim().toLowerCase(); } );
			}
/*
			if( $(this).text() == 'Other Contact:'){
				DATA['info']['yahoo_other_contact'] = $(this).parent().text();
			}
*/
		});
	} catch(err) {}
	
	console.log(JSON.stringify(DATA));
	//  now do something else when this is done.
});

// test to see if Not-a-Number and deal with accordingly
function geosafe(val){
	if(isNaN(val))
		return 0;
	return val;
}
