var util     = require('util');
var cheerio  = require('cheerio');
var request  = require('request');
var assert   = require('assert');
var async    = require('async');
var crypto   = require('crypto');
var DATA     = {};
var lastpage = false;
var url      = process.argv[2];
var isUrl    = new RegExp(/[-a-zA-Z0-9@:%_\+.~#?&//=]{2,256}\.[a-z]{2,4}\b(\/[-a-zA-Z0-9@:%_\+.~#?&//=]*)?/gi);
var md5cyph  = crypto.createHash('md5');

assert(isUrl.test(url), 'must provide a correct URL env variable');

// Crawl along the pages and collect all of the links
(function loop() {
	if (!lastpage) {
		//console.log('Request' + url);
		request({
			method: 'GET',
			url: url,
		}, function(err, response, body) {
			if (err) return console.error(err);
	
			// do the work!	
			load_links(body);
	
			loop();
		});
    	}
	else {
		//  now do something else when this is done.
		console.log(JSON.stringify(DATA));	
	}
}());


// - - - - - - - - - - - - - - - - - -
//   do some parsing
// 
function load_links(body){

	$ = cheerio.load(body);

	$('li').each(function() {
		if( $(this).attr('data-id')) { 
			var an = $(this).find('a');
			if(href = an.attr('href')){
				var id   = crypto.createHash('md5').update(href).digest('hex');
				var text = an.text();
				DATA[id] = {href: href,contents: text};
			}
		}
	});
	// see if there is a next page link
	if(href = $("#pg-next").attr('href')){
		url = href;
	}
	else {
		lastpage = true;
		url      = false;
	}
}


