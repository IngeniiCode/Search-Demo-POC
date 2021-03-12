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

