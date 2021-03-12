/*
    CasperJS based Yahoo Services Aggregator 
*/

var userAgents = [
	'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.74 Safari/537.36',
	'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_1) AppleWebKit/537.35 (KHTML, like Gecko) Chrome/41.0.2272.74 Safari/537.35'
];

/*
   +++++++++++++++++++++++++++++++++++++++++++++++
   +  C A S P E R   C O N F I G U R A T I O N    +
   +++++++++++++++++++++++++++++++++++++++++++++++
*/

var settings = {
        pageSettings:  { loadPlugins: false, loadImages:  false },
        viewportSize:  { width: 1020, height: 820 },
        onStepTimeout: function(){ to_exit('Step'); },
        onWaitTimeout: function(){ to_exit('Wait'); }
};

/*
   +++++++++++++++++++++++++++++++++++++++++++++++
   +  G E N E R A L   R E Q U I R E M E N T S    +
   +++++++++++++++++++++++++++++++++++++++++++++++
*/

var casper   = require('casper').create(settings);
var system   = require('system');
var utils    = require('utils');
var fs       = require('fs');

// process cli params
var query    = casper.cli.args[0];

// some requires
var startUrl = 'https://search.yahoo.com/yhs/search' + query;
var links    = []; 
var agency   = []; 
var linkNext = "#pg-next"
var agent    = agentStr();

// - - - - - - - - - - - - - - - - - - - - - -
// S T A R T   P A G E   G R A B  
//console.log('Start: ' + startUrl);
casper.start(); 
casper.userAgent(agent);
casper.thenOpen(startUrl);

// - - - - - - - - - - - - - - - - - - - - - -
//   I T E R A T E   
// Click the "See More" button for apps
casper.then(function() {
	// locate the 'See All' link identifier, then click it.
	if (casper.visible('a.fl-l.fz-m')) {
		this.click('a.fl-l.fz-m');
	}
	else {
		console.log("{ 'status': 'No Results for "+query+"'");
		casper.exit(0);
	}
});

// - - - - - - - - - - - - - - - - - - - - - -
//  I N I T I A T E   E X E C U T I O N  
casper.then(function() {
	processAgencies(this);
});

// - - - - - - - - - - - - - - - - - - - - - -
//  D I S P L A Y   A G E N C Y   D A T A  
casper.then(function() {
	console.log(JSON.stringify(links));
});

// - - - - - - - - - - - - - - - - - - - - - -
//  E X E C U T E   C A S P E R J S  
casper.run();


/*
   ++++++++++++++++++++++++++++++++++++++++++++++++++++++
   + F U N C T I O N S                                  +
   ++++++++++++++++++++++++++++++++++++++++++++++++++++++
*/

// - - - - - - - - - - - - - - - - - - - - - - - - - - -
//   process the links found on the page
//   
function processAgencies(casper) {

	casper.getElementsInfo('a.yschttl.spt').forEach(function(info){
		try {
			// get url
			var url = info.attributes.href;
			var name = info.text;
			if(name){
				links.push({name: name, engine_url: url});
			}
		} 
		catch(err) {
			console.log('ERROR');
			utils.dump(err);
		}

	});

	// now look for linkNext and click if visible
	if (casper.visible(linkNext)) {
		casper.click(linkNext);
		casper.then(function() {
			processAgencies(this);
		});
	}
}

// - - - - - - - - - - - - - - - - - - - - - - - - - - -
//  process the agency
//
function processAgencyUrl(e){
	url = e.getAttribute('href'); 
	return url;
}

// - - - - - - - - - - - - - - - - - - - - - - - - - - -
//   writes string to stdout
//
function progress(text) {
	system.stdout.write(text);
}

// - - - - - - - - - - - - - - - - - - - - - - - - - - -
//   tries to setup an exit timer
//
function to_exit(msg){
	console.error(msg + " Timeout Exit");
	casper.exit(0);
}

// - - - - - - - - - - - - - - - - - - - - - - - - - - -
//  neither Phantom or Casper can do this on thier own
//  so necesity dictates this hackorama
//
function getPath(){
	var fs          = require('fs');
	var currentFile = require('system').args[3];
	var curFilePath = fs.absolute(currentFile).split('/');
	curFilePath.pop();
	return curFilePath.join('/');
}

// - - - - - - - - - - - - - - - - - - - - - - - - - - -
//   exit routine
function to_exit(msg){
	console.err(msg + ' Timeout');
	capser.exit(9);
}

// -----------------------------------------------
//   return one of the available user agent strings
//
function agentStr(){
	// randomly select one of the strings
	return userAgents[Math.floor(Math.random()*userAgents.length)];
}

