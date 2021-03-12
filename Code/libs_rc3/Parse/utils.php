<?php

// used to connect to remote sites
require_once($_SERVER['NLIBS'].'/Core/cUrl.class.php');

/*
  U T I L I T I E S   F U N C T I O N S 
*/

function no_newlines($str=''){
	if(!$str){
		return '';  // self-preservation -- no preg_match errors on empty strings
	}
	return trim(preg_replace('/\s+/',' ',$str));
}

/*
  =======================================================================
  Memory safe node shell wraper launcher thingy 
*/
function execNode($file,$script,$args){
		$nodecmd = sprintf("export NODE_PATH='%s';%s",getenv('NODE_PATH'),getenv('NODE_BIN'));
		$script  = sprintf('%s/%s',dirname($file),$script);
		// create CMD
		$return = '';
		$cmd = sprintf("%s %s '%s'",$nodecmd,$script,$args);
		execSafe($cmd,$return);
		return $return;
}

/*
  =======================================================================
  Memory safe alternative to using the PHP 'exec' to execute shell
  commands.  
*/
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


?>
