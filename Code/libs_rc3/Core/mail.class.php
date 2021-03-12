<?

class NinjaMailer {

	private $to;
	private $from;
	private $cc;
	private $bcc;
 	private $headers;
	private $html;
	private $text;
	private $boundry;
	private $txtStart;
	private $htmlStart;
	private $msgEnd;
	private $allowed;
	private $nl       = "\n";
	private $split    = "\r\n";

	// = = = = = = = = = = = = = = = = = = = = = = = = =  
	//  C O N S T R U C T O R  
	// = = = = = = = = = = = = = = = = = = = = = = = = =  

	public function __construct(){
		$this->allowed = array(
			'to'      => true,
			'from'    => true,
			'cc'      => true,
			'bcc'     => true,
 			'headers' => false,
			'html'    => true,
			'text'    => true,
		);
		$this->set_boundry();
		$this->headers();
		$this->htmlframe();
	}


	// = = = = = = = = = = = = = = = = = = = = = = = = =  
	//  P R I V A T E    F U N C T I O N S  
	// = = = = = = = = = = = = = = = = = = = = = = = = =  

	// -------------------------------------------------
	//  create the boundry strings
	//
	private function set_boundry(){
		$this->boundry   =  sprintf('NinjaMail-%s',md5(__FILE__));
		$this->txtStart  = sprintf('--%s%sContent-Type: text/plain; charset="iso-8859-1"%sContent-Transfer-Encoding: 7bit%s',$this->boundry,$this->nl,$this->nl,$this->split);
		$this->htmlStart = sprintf('--%s%sContent-Type: text/html; charset="iso-8859-1"%sContent-Transfer-Encoding: 7bit%s',$this->boundry,$this->nl,$this->nl,$this->split);
		$this->msgEnd    = sprintf('--%s--%s',$this->boundry,$this->split);
	}

	// -------------------------------------------------
	//  create the header componetns 
	//
	private function headers(){
		$this->headers  = 'From: members@outspoken.ninja'.$this->split;
		$this->headers .= 'X-Mailer: NinjaMailer'.phpversion().$this->split;
		$this->headers .= 'MIME-Version: 1.0'.$this->split;
		$this->headers .= 'Content-Type: multipart/alternative; boundary="'.$this->boundry.'"'.$this->split.$this->split;
	}

	// -------------------------------------------------
	//  load the HTML framework 
	//
	private function htmlframe() {
		$this->htmlframe  = file_get_contents(dirname(__FILE__).'/html.template');
	}

	// -------------------------------------------------
	//   construct the html section of the message
	// 
	private function injecthtml($html){
		return str_replace('#__HTML__#',$html,$this->htmlframe);
	}


	// = = = = = = = = = = = = = = = = = = = = = = = = =  
	//  P R O T E C T E D    F U N C T I O N S
	// = = = = = = = = = = = = = = = = = = = = = = = = =  


	// = = = = = = = = = = = = = = = = = = = = = = = = =  
	//  P U B L I C    F U N C T I O N S
	// = = = = = = = = = = = = = = = = = = = = = = = = =  	

	public function send($to='',$subject='',$text='',$html=''){

		// assemble the mail content
		$html = $this->injecthtml($html);

		// assemble	
		$message = $this->txtStart.$text.$this->split.$this->htmlStart.$html.$this->split.$this->msgEnd;	

		// send mail
		$response = mail($to,$subject,$message,$this->headers);
	}
}

?>
