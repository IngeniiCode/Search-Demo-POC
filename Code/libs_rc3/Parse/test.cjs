casper.start('http://www.google.com/', function() {
    this.echo(this.getTitle()); // "Google"
});

//casper.run();
