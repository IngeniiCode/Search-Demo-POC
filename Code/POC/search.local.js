var request = require('request');
var cheerio = require('cheerio');
var url     = process.argv[2];

//console.log('url = '+url);

request(url, function (error, response, html) {
	if (!error && response.statusCode == 200) {
		var $ = cheerio.load(html);
		$('div.text-place-summary-details').each(function(i, element){
console.log('LOC: ' + $(this));
		})
		$('div.text-place-summary-details.text-place-has-thumbnail').each(function(i, element){
console.log('LOC: ' + $(this));
		})
console.log(html);
	}
});

