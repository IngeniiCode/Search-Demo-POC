var request = require('request');
var cheerio = require('cheerio');
var url = process.argv[2];

console.log('url = '+url);

request(url, function (error, response, html) {
  if (!error && response.statusCode == 200) {
    console.log(html);
  }
});

