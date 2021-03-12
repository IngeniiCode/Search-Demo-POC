<?php

require_once($_SERVER['NLIBS'].'/MySQL/profile.class.php');

class Dashboard extends ProfileSQL {

	private $x_auth     = '';
	private $jSession   = '';
	private $title      = '';
	private $DASHBOARD  = '';
	private $USER       = '';
	private $JS         = '';
	private $message    = '';
	//private $interval   = 'date_sub(now(),interval 2 week)';
	private $interval   = 'date_sub(now(),interval 1 month)';

	// = = = = = = = = = = = = = = = = = = = = = = = = =  
	//  C O N S T R U C T O R  
	// = = = = = = = = = = = = = = = = = = = = = = = = =  

	public function __construct(){
		$this->title = 'Outspoken Ninja Dashboard';

		// get the session
		$this->x_auth = @$_COOKIE['x-auth'];   // this is a marker that user has logged in.

		// setup the parent class
		parent::__construct(array('x_a'=>$this->x_auth));  // init parent class.

		// perform some initialization actions here
		$this->jSession = json_encode($_SESSION);

		// set user configuration
		if($id = $this->user_valid()){
			$this->USER = array_shift($this->user_lookup($id));
		}
		else {
			$this->USER['type'] = 'guest';
		}

		$this->_init_login_message();
	}

	// - - - - - - - - - - - - - - - - - - - - - -

	// = = = = = = = = = = = = = = = = = = = = = = = = =  
	//  P R I V A T E    F U N C T I O N S  
	// = = = = = = = = = = = = = = = = = = = = = = = = =  

	// - - - - - - - - - - - - - - - - - - - - - -
	//
	private function _generate_guest_dashboard(){
		$GUEST = '<div class="dashboard" id="dashboard_guest"><div class="title">Outspoken Ninja Search Trends</div>';
		$GUEST .= $this->_search_type_pie();
		$GUEST .= $this->_search_term_pie();
		$GUEST .= '</div>';
		return $GUEST;
	}

	// - - - - - - - - - - - - - - - - - - - - - -
	//
	private function _generate_admin_dashboard(){
		$ADMIN = '<!--  Usage -->';
		if($this->USER['type'] == 'admin'){
			$ADMIN .= '<div class="dashboard" id="dashboard_admin"><div class="title">Usage and Performance</div>';
			$ADMIN .= $this->_searches_per_day();
			$ADMIN .= $this->_ads_found_per_day();
			$ADMIN .= $this->_ads_clicked_per_day();
			$ADMIN .= '</div>';
		}	
		return $ADMIN;  // default is nothing for admin graph
	}

	// - - - - - - - - - - - - - - - - - - - - - -
	//  line chart of searches by day 
	// 
	public function _searches_per_day() {
		// get counts of the search types in the last week.
		$perday = $this->get_all("select sh.day,count(*) as searches from (select date_format(dtime,'%W %d-%b') as day,dtime as ddate from searchHistory where dtime >= $this->interval) sh group by sh.day order by sh.ddate asc;");
		$this->JS .= PHP_EOL.'google.setOnLoadCallback(drawPerDayChart);';
		$this->JS .= PHP_EOL."function drawPerDayChart() { var data = new google.visualization.DataTable(); data.addColumn('string', 'Day'); data.addColumn('number', 'Daily Searches');";
		$parts = array();
		foreach($perday as $data){
			$dayp = explode('-',$data['day']);
			$parts[] = sprintf("['%s',%d]",$data['day'],$data['searches']);
		}
		$this->JS .= 'data.addRows(['.join(',',$parts).']);';
		$this->JS .= "var options = {'title':'Searches Per Day','curveType':'function',legend:{position:'bottom'},'width':415,'height':100};";
		$this->JS .= "var chart = new google.visualization.LineChart(document.getElementById('gpd'));";
		$this->JS .= "chart.draw(data, options);}";

		return '<div id="gpd" class="dashboard_metrics"><em>loading searches...</em><img src="/igx/searching.gif" alt="Loading Search Metrics"></div>';
	}

	// - - - - - - - - - - - - - - - - - - - - - -
	//  line chart of ads found by day 
	// 
	public function _ads_found_per_day() {
		// get counts of the ads found in the last week.
		$perday = $this->get_all("select f.day,count(*) as ads from (select date_format(found,'%W %d-%b') as day,found as ddate from adsFound where found >= $this->interval) f group by f.day order by f.ddate asc;");
		$this->JS .= PHP_EOL.'google.setOnLoadCallback(drawAdsDayChart);';
		$this->JS .= PHP_EOL."function drawAdsDayChart() { var data = new google.visualization.DataTable(); data.addColumn('string', 'Day'); data.addColumn('number', 'Ads Discovered');";
		$parts = array();
		foreach($perday as $data){
			$dayp = explode('-',$data['day']);
			$parts[] = sprintf("['%s',%d]",$data['day'],$data['ads']);
		}
		$this->JS .= 'data.addRows(['.join(',',$parts).']);';
		$this->JS .= "var options = {'title':'Ads Discovered Per Day','curveType':'function',legend:{position:'bottom'},'width':415,'height':100};";
		$this->JS .= "var chart = new google.visualization.LineChart(document.getElementById('gapd'));";
		$this->JS .= "chart.draw(data, options);}";

		return '<div id="gapd" class="dashboard_metrics"><em>loading searches...</em><img src="/igx/searching.gif" alt="Loading Search Metrics"></div>';
	}

	// - - - - - - - - - - - - - - - - - - - - - -
	//  line chart of ads found by day 
	// 
	public function _ads_clicked_per_day() {
		// get counts of the ads found in the last week.
		$perday = $this->get_all("select f.day,count(*) as ads from (select date_format(clicked,'%W %d-%b') as day,clicked as ddate from adsClicked where clicked >= $this->interval) f group by f.day order by f.ddate asc;");
		$this->JS .= PHP_EOL.'google.setOnLoadCallback(drawAdsClickedChart);';
		$this->JS .= PHP_EOL."function drawAdsClickedChart() { var data = new google.visualization.DataTable(); data.addColumn('string', 'Day'); data.addColumn('number', 'Ads Clicked');";
		$parts = array();
		foreach($perday as $data){
			$dayp = explode('-',$data['day']);
			$parts[] = sprintf("['%s',%d]",$data['day'],$data['ads']);
		}
		$this->JS .= 'data.addRows(['.join(',',$parts).']);';
		$this->JS .= "var options = {'title':'Ads Clicked Per Day','curveType':'function',legend:{position:'bottom'},'width':415,'height':100};";
		$this->JS .= "var chart = new google.visualization.LineChart(document.getElementById('gacd'));";
		$this->JS .= "chart.draw(data, options);}";

		return '<div id="gacd" class="dashboard_metrics"><em>loading searches...</em><img src="/igx/searching.gif" alt="Loading Search Metrics"></div>';
	}


	// - - - - - - - - - - - - - - - - - - - - - -
	//   pie chart of search terms
	// 
	public function _search_term_pie() {
		// get counts of the search types in the last week.
		$types = $this->get_all("select sh.terms,freq from (select terms,count(*) as freq from searchHistory where dtime >= $this->interval AND terms > ' ' group by terms) sh order by sh.freq desc,sh.terms asc LIMIT 1,10;");
		$this->JS .= PHP_EOL.'google.setOnLoadCallback(drawTermsChart);';
		$this->JS .= PHP_EOL."function drawTermsChart() { var data = new google.visualization.DataTable(); data.addColumn('string', 'Search Terms'); data.addColumn('number', 'Searches');";
		$parts = array();
		foreach($types as $data){
			$parts[] = "['".$data['terms']."', ".$data['freq']."]";
		}
		$this->JS .= 'data.addRows(['.join(',',$parts).']);';
		$this->JS .= "var options = {'title':'Top 10 Search Terms',legend:{position:'right'},'width':200,'height':100};";
		$this->JS .= "var chart = new google.visualization.PieChart(document.getElementById('stp'));";
		$this->JS .= "chart.draw(data, options);}";

		return '<div id="stp" class="dashboard_metrics"><em>loading terms...</em><img src="/igx/searching.gif" alt="Loading Search Metrics"></div>';
	}

	// - - - - - - - - - - - - - - - - - - - - - -
	//   pie chart of search type percentages
	// 
	public function _search_type_pie() {
		// get counts of the search types in the last week.
		$types = $this->get_all("select type,count(*) as searches from searchHistory where dtime >= $this->interval group by type;");
		$this->JS .= PHP_EOL.'google.setOnLoadCallback(drawTypeChart);';
		$this->JS .= PHP_EOL."function drawTypeChart() { var data = new google.visualization.DataTable(); data.addColumn('string', 'Search Type'); data.addColumn('number', 'Searches');";
		$parts = array();
		foreach($types as $data){
			$parts[] = "['".$data['type']."', ".$data['searches']."]";
		}
		$this->JS .= 'data.addRows(['.join(',',$parts).']);';
		$this->JS .= "var options = {'title':'Search Type Popularity',legend:{position:'bottom'},'width':200,'height':100};";
		$this->JS .= "var chart = new google.visualization.PieChart(document.getElementById('gtp'));";
		$this->JS .= "chart.draw(data, options);}";

		return '<div id="gtp" class="dashboard_metrics"><em>loading types...</em><img src="/igx/searching.gif" alt="Loading Search Metrics"></div>';
	}

	// - - - - - - - - - - - - - - - - - - - - - -
	//
	private function _finish_loading(){
		return '';
	}

	// - - - - - - - - - - - - - - - - - - - - - -
	//
	private function _init_login_message(){
		$msg = PHP_EOL.'<!-- DASH MSG -->'.PHP_EOL;
		// verify login
		if(isset($this->USER['id'])){
			$this->title .= ' for <em>'.$this->USER['uname'].'</em>';
		}
		else {
			//$this->message .= 'Unlock the power of your personal Ninja Dashboard!  <a href="/Membership" target="_members"><b>Sign-up Now for Free!</b></a></div>';
		}

	}

	// = = = = = = = = = = = = = = = = = = = = = = = = =  
	//  P R O T E C T E D    F U N C T I O N S
	// = = = = = = = = = = = = = = = = = = = = = = = = =  

	// = = = = = = = = = = = = = = = = = = = = = = = = =  
	//  P U B L I C    F U N C T I O N S
	// = = = = = = = = = = = = = = = = = = = = = = = = =  

	// - - - - - - - - - - - - - - - - - - - - - -
	//
	public function show() {
		$this->_finish_loading();
		$GUEST = $this->_generate_guest_dashboard();
		$ADMIN = $this->_generate_admin_dashboard();
		$TITLE = '<h2>'.$this->title.'</h2>';
		$DEBUG = print_r($this->USER,true);
		$MSG   = ($this->message) ? '<div id="dash_message" class="announcement">'.$this->message.'</div>' : '';		
		return <<<DASH
<script type="text/javascript">
$this->JS
</script>
<div id="DASH">
${TITLE}
${MSG}
${ADMIN}
${GUEST}
</div>
DASH;
	}
}

// MAKE IT REAL!
$DASH = new Dashboard();

?>
