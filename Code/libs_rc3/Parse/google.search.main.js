var util     = require('util');
var cheerio  = require('cheerio');
var assert   = require('assert');
var async    = require('async');
var crypto   = require('crypto');
var DATA     = {};
var lastpage = false;
var pagesmax = 10;
var failmax  = 15;
var failed   = 0;
var curpage  = 1;
var url      = process.argv[2];
var isUrl    = new RegExp(/[-a-zA-Z0-9@:%_\+.~#?&//=]{2,256}\.[a-z]{2,4}\b(\/[-a-zA-Z0-9@:%_\+.~#?&//=]*)?/gi);
var noCache  = new RegExp(/webcache.google/);
var noGoogle  = new RegExp(/google.com\//i);
var isOffsu  = new RegExp(/^\/url\?q=(.*)\/\&sa=.*$/);
var isNext   = new RegExp(/Next/i);
var md5cyph  = crypto.createHash('md5');
var headers = { 
    'User-Agent': 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10.10; rv:34.0) Gecko/20100101 Firefox/34.0',
};
var proxyies = [
//"http://108.165.33.13:3128/",
"http://69.197.148.18:7808/",
//"http://69.197.148.18:8089/",
"http://199.200.120.36:7808/",
"http://199.200.120.36:8089/",
"http://108.165.33.3:3128/",
"http://108.165.33.12:3128/",
"http://199.200.120.37:7808/",
"http://165.24.10.16:8080/",
"http://199.200.120.37:8089/",
"http://174.34.166.10:3128/",
//"http://108.165.33.5:3128/",
"http://23.253.221.66:80/",
"http://166.70.51.198:8080/",
"http://166.70.91.1:8080/",
"http://142.54.170.72:7808/",
"http://142.54.170.72:8089/",
"http://108.165.33.7:3128/",
"http://66.192.33.78:8080/",
"http://137.135.166.225:8121/",
"http://216.171.205.9:8080/",
"http://75.148.236.49:3128/",
"http://24.100.137.39:3128/",
//"http://68.98.216.36:3128/",
"http://192.3.171.92:80/",
"http://69.28.85.50:3128/",
"http://141.105.164.188:8080/",
//"http://24.172.34.114:8181/",
"http://64.62.233.67:80/",
"http://69.7.113.5:3128/",
//"http://207.5.112.114:8080/",
"http://204.228.129.46:8080/",
"http://70.32.73.89:28289/",
"http://151.200.170.146:80/"
];
var proxyhost = get_proxy();

assert(isUrl.test(url), 'must provide a correct URL env variable');

// Crawl along the pages and collect all of the links
(function loop() {
	if (!lastpage) {
		//console.log('Page: ' + curpage + '  URL' + url);
		//var request = require('request').defaults({proxy:proxyhost});
		var request = require('request');
		request({
			method: 'GET',
			url: url,
			headers: headers,
		}, function(err, response, body) {
			if (err) return console.error(err);
	
			// do the work!	
			load_links(body);
	
			loop();
		});
		if(curpage >= pagesmax){
			lastpage = true; // stop the page hunt
		}
    	}
	else {
		//  now do something else when this is done.
		console.log(JSON.stringify(DATA));	
	}
}());

// - - - - - - - - - - - - - - - - - -
function get_proxy(){
	// randomly select a proxy
	var rnd = Math.floor((Math.random() * proxyies.length) + 0);
	var proxy =  proxyies[rnd];
	//console.log('Using Proxy: ' + proxy);
	return proxy;
}

// - - - - - - - - - - - - - - - - - -
//   do some parsing
// 
function load_links(body){
	$ = cheerio.load(body);
//console.log('BODY: ' + body);

	if(links_type1()){
		curpage++;
		return getNext();
	}
	if(links_type2()){
		curpage++;
		return getNext();
	}
	//console.log("Didn't find any items");
	return;
}

// - - - - - - - - - - - - - - - - - -
//  look for the div_search element
function links_type1(){
	var found = 0;
	$("div#search").find("a").each(function() {
		if(href = $(this).attr('href')) {
			if(href.match(noGoogle)){
				return true;	
			}
			if(href.match(noCache)){
				return true;	
			}
			var text = $(this).text();
//console.log("A - ["+href+"]  "+text);
			if(site = href.match(isOffsu)){
				var id   = crypto.createHash('md5').update(site[1]).digest('hex');
				DATA[id] = {href: href,site: site[1],contents: text};
//console.log("OFFU - "+text+" -- "+site[1]);
				found++;
			}
			if(href.match(isUrl)){
				var id   = crypto.createHash('md5').update(href).digest('hex');
				DATA[id] = {href: href,site: href,contents: text};
//console.log("REAL - "+text+" -- "+href);
				found++;
			}
		}
	});
	return found;
}

// - - - - - - - - - - - - - - - - - -
//  look for the div_search element
function links_type2(){
	var found = 0;
	$("div#search").find("li").each(function() {
//console.log("LI - "+$(this).text());
	});
	return found;
}

// - - - - - - - - - - - - - - - - - - - - - - -
//  try to get the next page
function getNext(node){
	$("table#nav").find("a").each(function() {
		if($(this).text().match(isNext)){
			if(href = $(this).attr('href')) {
				url = 'http://www.google.com' + href;
				return;
				//return console.log("PAGE: "+$(this).text()+" -- " + url);
			}
		}
		else {
			// no next page seen.. 
			if(failed++ > failmax){
				lastpage = true;  // stop the madness
			}
		}
	});
}
