var util    = require('util');
var cheerio = require('cheerio');
var request = require('request');
var assert  = require('assert');
var isUrl   = new RegExp(/[-a-zA-Z0-9@:%_\+.~#?&//=]{2,256}\.[a-z]{2,4}\b(\/[-a-zA-Z0-9@:%_\+.~#?&//=]*)?/gi);

var url = process.argv[2];

assert(isUrl.test(url), 'must provide a correct URL env variable');

request({
	method: 'GET',
	url: url,
}, function(err, response, body) {
	if (err) return console.error(err);
	//  look for the magical main url!
	$ = cheerio.load(body);
	$('a').each(function(){
		if($(this).attr('class')) { 
			if( $(this).attr('class').match(/fl-l/) ) {
    				console.log($(this).attr('href'));
			}
		}
	});
	//  now do something else when this is done.
});


