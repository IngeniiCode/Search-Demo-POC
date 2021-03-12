var request = require('request');
var cheerio = require('cheerio');
var url     = process.argv[2];

//console.log('url = '+url);

request(url, function (error, response, html) {
	if (!error && response.statusCode == 200) {
		var $ = cheerio.load(html);
		$('a').each(function(i, element){
			if(location = $(this).attr('href')){
				if(match = location.match(/maps.*\&sll=([\-0-9\.]+),([\-0-9\.]+)\&/)){
					//console.log(match);
					//console.log('LAT: ' + match[1] + '  LON: ' + match[2]);
					var parts = {};
					parts.LAT = match[1];
					parts.LON = match[2];
					var jso = JSON.stringify(parts);
					console.log(jso);
				}
			}
		})
	}
});

