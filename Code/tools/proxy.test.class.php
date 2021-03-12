<?
// require the MySQL interface to Proxy tables
require_once(getenv('NLIBS').'/MySQL/proxy.class.php');

class ProxyTest {

	private $SQL;
	private $DOM;
	private $validation_url;
	private $test_url;
	private $protocol;
	private $proxy;
	private $cH;
	private $agent;
	private $retIP;
	private $pubIP;
	private $proxyUp;
	private $title;
	private $timestamp;
	private $protocols = array();

	/*
	  ------------------------------------------------------
	  --  C O N S T R U C T O R
	  ------------------------------------------------------
	*/
	public function __construct($settings=array()){
		$this->agent          = 'Mozilla/5.0 (Windows NT 6.1; WOW64; Trident/7.0; rv:11.0) like Gecko';
		$this->validation_url = 'http://www.daviddemartini.com/proxtest.php';
		$this->timestamp      = date("Y-m-d H:i:s",time());
		$this->SQL            = new ProxySQL();
		$this->DOM            = new DOMDocument;

		// setup the CURL testing
		$this->cH = curl_init();
		curl_setopt($this->cH, CURLOPT_FRESH_CONNECT,  true);
		curl_setopt($this->cH, CURLOPT_MAXREDIRS,      2);
		curl_setopt($this->cH, CURLOPT_TIMEOUT,        (@$this->config['timeout'])?:2);
		curl_setopt($this->cH, CURLOPT_USERAGENT,      $this->agent);
		curl_setopt($this->cH, CURLOPT_RETURNTRANSFER, true);

		// Determine current public IP
		$this->_get_public_ip();

		return;
	}

	/*
	  ------------------------------------------------------
	  -- P R I V A T E   M E T H O D S 
	  ------------------------------------------------------
	*/

	//  - - - - - - - - - - - - - - - - - - - - - - - - - - 
	//   parse title string for ip
	//
	private function _init($proxy){
		// set and reset parameters
		$this->proxy   = $proxy;
		$this->title   = null;
		$this->retIP   = null;
		$this->proxyUp = null;

		$this->protocols = array(
			'http'    => CURLPROXY_HTTP,
			'socks'   => CURLPROXY_SOCKS4,
			'socks5'  => CURLPROXY_SOCKS5,
			'socks5h' => 7,
		);

		// setup the proxy info
		curl_setopt($this->cH, CURLOPT_PROXY,     $this->proxy['host']);
		curl_setopt($this->cH, CURLOPT_PROXYPORT, $this->proxy['port']);

		// set the timestamp for last tested
		$this->proxy['tested_on'] = $this->timestamp; 
		$this->proxy['anon']      = 'no';
			
		return;
	}

	//  - - - - - - - - - - - - - - - - - - - - - - - - - - 
	//   parse title string for ip
	//
	private function _parse_ip($title){
		if(preg_match('#^IP:(.*)$#i',$title,$m)){
			return $this->retIP = trim($m[1]);
		}
		return false;
	}
	
	//  - - - - - - - - - - - - - - - - - - - - - - - - - - 
	//    get some data
	//
	private function _get($protocol=''){
		$CURLOPT = $this->protocols[$protocol];
		curl_setopt($this->cH, CURLOPT_PROXYTYPE, $CURLOPT);
//printf("%s %s => %s",__METHOD__,$protocol,$CURLOPT);

		if($html = trim(curl_exec($this->cH))){
		        @$this->DOM->loadHTML(($html)?:'<html><body></body></html>');
			if($title = $this->_get_title()){
//printf("\tTITLE: %s\n",$title);
				if($this->_parse_ip($title)){
					return $this->proxy['status'] = 'up';
				}
			}
			return true;
		}	
//printf("\tNO HTML\n");
		return false;
	}

	//  - - - - - - - - - - - - - - - - - - - - - - - - - - 
	//    get some data
	//
	private function _test_cl(){
		$protocol = $this->proxy['protocol'];  // pull the set protocol
		$CURLOPT = $this->protocols[$protocol];
		curl_setopt($this->cH, CURLOPT_URL,'http://monterey.craigslist.org');
		curl_setopt($this->cH, CURLOPT_PROXYTYPE, $CURLOPT);
printf("%s %s => %s",__METHOD__,$protocol,$CURLOPT);
		if($html = trim(curl_exec($this->cH))){
		        @$this->DOM->loadHTML(($html)?:'<html><body></body></html>');
			if($title = $this->_get_title()){
printf("\tTITLE: %s\n",$title);
				if(preg_match('#craigslist#i',$title)){
					return $this->proxy['cl_safe'] = 'yes';
				}
			}
			return true;
		}	
		return false;
	}

	//  - - - - - - - - - - - - - - - - - - - - - - - - - - 
	//    parse for the title 
	//
	private function _get_title(){
		// test for and return the  
		$list = $this->DOM->getElementsByTagName("title");
		if ($list->length > 0) {
			return $this->title = trim($list->item(0)->textContent);
		}
		return false;		
	}

	//  - - - - - - - - - - - - - - - - - - - - - - - - - - 
	//    parse for the public IP 
	//
	private function _get_public_ip(){
		// determine our IP
		curl_setopt($this->cH, CURLOPT_URL,'http://www.daviddemartini.com/proxtest.php');
		// get the data
		$html = curl_exec($this->cH);
		$this->DOM->loadHTML(($html)?:'<html><body></body></html>');
		$title = $this->_get_title();
		if(preg_match('#^IP:(.*)$#i',$title,$m)){
			$this->pubIP = trim($m[1]);
			printf("Public IP: %s\n",$this->pubIP);
                	return;
		}
		return;
        }

	//  - - - - - - - - - - - - - - - - - - - - - - - - - - 
	//   Test for Anonymization 
	//
	private function _test_anon(){
		if($this->retIP != $this->pubIP){
			$this->proxy['anon'] = 'yes';
		}
		return;
	}

	/*
	  ------------------------------------------------------
	  -- P R O T E C T E D   M E T H O D S 
	  ------------------------------------------------------
	*/
	
	/*
	  ------------------------------------------------------
	  -- P U B L I C   M E T H O D S 
	  ------------------------------------------------------
	*/

	public function xroxy_parse($url){
		// determine our IP
		curl_setopt($this->cH, CURLOPT_URL,$url);
		// get the data
		$html = curl_exec($this->cH);
		@$this->DOM->loadHTML(($html)?:'<html><body></body></html>');
printf("%s HTML: %s\n",__METHOD__,$html);
		return;
	}

	//  - - - - - - - - - - - - - - - - - - - - - - - - - - 
	//    test against the validation URL to figure out if
	//    the proxy is UP, which protocol it might use and
	//    if it's clean or mangling data
	//
	public function validate($proxy){

		$this->_init($proxy);
printf("%s %s:%d\n",__METHOD__,$proxy['host'],$post['port']);

		// set the validation URL
		curl_setopt($this->cH, CURLOPT_URL, $this->validation_url);

		foreach($this->protocols as $protocol => $int){
			if($this->_get($protocol)){
				// set the active protocol
				$this->proxy['protocol'] = $protocol;

				// test for anon
				$this->_test_anon();

				// run the tests	
				$this->_test_cl();

				// break out of the foreach loop
				printf("%s workes at %s:%s\n",$protocol,$this->proxy['host'],$this->proxy['port']);
				break;		
			}
		}

		// check to see if this worked 
		if($this->proxy['status'] != 'up'){
				// Throw out this proxy
				$this->proxy['status']    = 'down';
				$this->proxy['anon']      = 'no';
				$this->proxy['cl_safe']   = 'no';
				// update the proxy	
		}

		printf("%s PROXY: %s\n",__METHOD__,print_r($this->proxy,true));
		$this->SQL->update_proxy($this->proxy);

		return $this->proxy;
	}
}

?>
