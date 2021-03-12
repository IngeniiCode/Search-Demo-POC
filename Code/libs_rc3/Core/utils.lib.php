<?php
// Set the timezone
date_default_timezone_set('UTC');

/*
  =======================================================================
    U T I L I T I E S
  =======================================================================
*/

// -----------------------------------------------------------------------
//   minify  a string for HTML.  Make this miserable for anyone to read
//   on the website
function minify($str){
	return preg_replace('|\s\s+|',' ',str_replace("\n",' ',$str));
}

// -----------------------------------------------------------------------
//  calculate the actual host URL for setting up a correct base path
//
function host_url($path=''){
	return sprintf('%s//%s%s',(isset($_SERVER['HTTPS']))?'https:':'http:',$_SERVER['SERVER_NAME'],$path);
}

// -----------------------------------------------------------------------
//   look for a local file, with suffix of .txt.  If located this is the
//   basic body content for the page being rendered.  Load the text, use 
//   as the HTML, wrap in a div (last part may need to change)
//
function load_body_html($fbase=false){
	$fl = basename($fbase);
	$fn = preg_replace('/.php$/','_body.txt',$fl);
	if(file_exists($fn)){
		// open file
		return sprintf('<div id="content_right">%s</div>',file_get_contents($fn));
	}
	else {
		return '';
	}
}

// -----------------------------------------------------------------------
//   get IP address of remote client accessing the site.
//
function get_client_ip(){
	if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
		return $_SERVER['HTTP_CLIENT_IP'];
	}
	if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
		return $_SERVER['HTTP_X_FORWARDED_FOR'];
	}
	return (@$_SERVER['REMOTE_ADDR'])?:'000.000.000.000'; 
}

// -----------------------------------------------------------------------
//   get GEO LOCATION INFORMAITON.
//
function get_latlon_ip($ip){
	$url = 'http://www.geoplugin.net/php.gp?ip='.$ip;
	if($geo = unserialize(file_get_contents($url))){
		$lat = $geo['geoplugin_latitude'];
		$lon = $geo['geoplugin_longitude'];
		return sprintf('%s,%s',$lat,$lon);
	}
	else {
		return false;
	}
}

// -----------------------------------------------------------------------
//   get GEO LOCATION VERBOSE.
//
function get_location_ip($ip){
	$url = 'http://www.geoplugin.net/php.gp?ip='.$ip;
	if($geo = unserialize(file_get_contents($url))){
		$geo = array(
			'lat'    => $geo['geoplugin_latitude'],
			'lon'    => $geo['geoplugin_longitude'],
			'city'   => $geo['geoplugin_city'],
			'ccode'  => $geo['geoplugin_countryCode'],
			'county' => $geo['geoplugin_countryName']
		);
		return $geo;
	}
	else {
		return array();
	}
}

// -----------------------------------------------------------------------
//   short 22 char  base64  hashed string used for ID keys
//   the two character substitutions implemented are neccessary to prevent
//   jQuery from puking on the computed IDs when they contain a '/' or '+'
//   not really awesome bugs (IMHO) in jQuery
//
function id_gen($str=''){
	$pats = array('/','+');
	$subs = array('_','-');
	return str_replace($pats,$subs,substr(base64_encode(md5($str,true)),0,22));
}

// -----------------------------------------------------------------------
//   long  base64  hashed string used for other types of hash keys
//
function key_gen($str=''){
	return str_replace('=','',base64_encode(md5($str)));
}

// -----------------------------------------------------------------------
//  Memory safe alternative to using the PHP 'exec' to execute shell
// commands.
//
function execSafe($command='',&$results){
        if(!$command) {
                return 'NO COMMAND PROVIDED';
        }
        // setup process pipelines

        $process = proc_open(
                $command,
                array(
                        0 => array("pipe", "r"), //STDIN
                        1 => array("pipe", "w"), //STDOUT
                        2 => array("pipe", "w")  //STDERR
                ),
                $pipes
        );
        if(isset($results)){
                $results = stream_get_contents($pipes[1]);
        }
        if($error = stream_get_contents($pipes[2])){
                $results .= sprintf("\nERROR: %s",$error);
        };
        fclose($pipes[1]);
        fclose($pipes[2]);
        $status = proc_close($process);
        return($error);
}

// -----------------------------------------------------------------------
//  Memory safe node shell wraper launcher thingy
//
function forkNode($command){
	$bin = env('NODE_BIN');
	return execBin($bin,$command);
}

// -----------------------------------------------------------------------
//  Memory safe node shell wraper launcher thingy
//
function forkCasper($command){
	$bin = env('CASPER_BIN');
	return execBin($bin,$command);
}

// -----------------------------------------------------------------------
//  Memory safe node shell wraper launcher thingy
//
function execBin($bin,$command){
                // create CMD
                $return = '';
                $cmd = sprintf("export NODE_PATH='%s'; %s %s",env('NODE_PATH'),$bin,$command);
                execSafe($cmd,$return);
                return $return;
}

// -----------------------------------------------------------------------
//   get the env from either server or user environment
//
function env($str){
	return (getenv($str))?:$_SERVER[$str]; // generate library path:
}

/*
  =======================================================================
   ZULU Date Processor for Solr

   A number of entities require the conversion of EPOC (bigint) values
   to standardized date strings.   With the possibility that date epoc
   might be missing, this function's purpose is to provide a subsitute
   of the current epoc, instead of a zero result.

*/

// -----------------------------------------------------------------------
//   fabricate a Solr timestamp 
//
function solrTimestamp($epoch=0) {
        if($utc = intval($epoch)) {
                return date('Y-m-d\TH:i:s\Z',$utc);
        }
        else {
                return date('Y-m-d\TH:i:s\Z',time());
        }
        return;
}

/*   E N D   */
?>
