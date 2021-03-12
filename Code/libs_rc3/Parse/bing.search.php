<?php

require_once('search.php');

class SearchParse extends Search {

	// = = = = = = = = = = = = = = = = = = = = = = = = =  
	//  C O N S T R U C T O R  
	// = = = = = = = = = = = = = = = = = = = = = = = = =  

	// = = = = = = = = = = = = = = = = = = = = = = = = =  
	//  P R I V A T E    F U N C T I O N S  
	// = = = = = = = = = = = = = = = = = = = = = = = = =  


	// = = = = = = = = = = = = = = = = = = = = = = = = =  
	//  P R O T E C T E D    F U N C T I O N S
	// = = = = = = = = = = = = = = = = = = = = = = = = =  


	// = = = = = = = = = = = = = = = = = = = = = = = = =  
	//  P U B L I C    F U N C T I O N S
	// = = = = = = = = = = = = = = = = = = = = = = = = =  

	// - - - - - - - - - - - - - - - - - - - - - -
	//   parse for all the link block
	function parse_for_items(){

		// http://www.bing.com/entities/search?q=drug+rehab+NEAR%3a84101&filters=segment%3a%22local%22&go=Submit+Query&qs=ds&pin=YN873x129697258%2cYN876x15269212&qpvt=drug+rehab+NEAR%3a84101&FORM=SEMORE

		$this->_get_start_point();

		$items = '';

		foreach($this->DOM->getElementsByTagName('ul') as $block) {
			if($class = $block->getAttribute('class')){
				if($class == 'b_vList'){
					foreach($block->getElementsByTagName('a') as $link) {
						// Look for Show All link
						$href = $link->getAttribute('href');
						$text = no_newlines($link->nodeValue);
						$items .= sprintf('<li><div><a href="%s" target="bing">%s</a></div></li>',$href,$text);
					}
				}
			}
		}
		return addslashes('<div class="search_items"><ul>'.$items.'</ul></div>'); // add slashes required to escape the insert
	}
	
}

?>
