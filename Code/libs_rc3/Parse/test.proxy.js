var fs        = require('fs');
var util      = require('util');
var cheerio   = require('cheerio');
var assert    = require('assert');
var url       = process.argv[2];
var userAgent = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10.8; rv:33.0) Gecko/20100101 Firefox/33.0';
var headers = { 
    'User-Agent': 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10.8; rv:33.0) Gecko/20100101 Firefox/33.0',
};

var myrealip = '127.0.0.1';  // placeholder

var whatismyip = 'http://whatismyipaddress.com';

/*  F I N D   O U T   M Y   I P  */

var req = require('request');
req({
	method: 'GET',
	url: whatismyip,
	headers: headers,
}, function(err, response, body) {
	if (err) return console.log('Cannot get to ' + whatismyip + ' to get IP -- DIE');
	$ = cheerio.load(body);  // load your cheerios
	myrealip = $('#section_left div:nth-child(2)').text().trim();
	console.log('IP ' + myrealip);
	
//console.log(body);

checkproxies();
	
});

function checkproxies(){
	readline = require('readline');

	var rd = readline.createInterface({
		input: fs.createReadStream('./proxy.lst'),
		output: process.stdout,
		terminal: false
	});

	rd.on('line', function(line) {
		var proxy = 'http://' + line + '/';
		var request = require('request').defaults({proxy:proxy});
		request({
			method: 'GET',
			url: 'http://www.outspoken.ninja/myip.php',
			headers: headers,
		}, function(err, response, tbody) {
			if (err) return; //  
			$ = cheerio.load(tbody);  // load your cheerios
			if(proxiedip = $('#myip').text()){
				var status = 'My IP: ' + myrealip + ' ProxIP: ' + proxiedip;
				var type   = ' PassThru ';
				if(proxiedip != myrealip){
					console.log(response['request']['proxy']['href']);	
				}
				//console.log(type +' '+response['request']['proxy']['href'] + "\t " + status);
			}
			//console.log(body);
		});
	});
}

