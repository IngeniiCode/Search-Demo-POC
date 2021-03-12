<?php
require_once($_SERVER['NLIBS'].'/MySQL/profile.class.php');
require_once($_SERVER['NLIBS'].'/MySQL/search.class.php');

$x_auth = @$_COOKIE['x-auth'];
$id     = $_GET['sid'];

$PROF = new ProfileSQL();
$SRCH = new SearchSQL(array('x_a'=>$x_auth,'searchId'=>$id));
$CONTENT = 'id='.$sid;

// Check to see if they are logged in, and if so if they have a saved search
if($user = $PROF->user_lookup($x_auth)){
	// select all of the user's saved searches
	$SETTINGS = '';
	if($SEARCH = json_decode($SRCH->get_saved_search(),true)){
		$SRCH   = print_r($SEARCH,true);
		$mode   = trim(@$SEARCH['mode']?:'sss');
		$terms  = addslashes(@$SEARCH['search_terms']?:'I want a unicorn');
		$recnt  = trim(@$SEARCH['recentOnly']?:'false');
		$titly  = trim(@$SEARCH['titleOnly']?:'false');
		$exttr  = trim(@$SEARCH['exclude_terms']?:'');
		$plow   = trim(@$SEARCH['price_usd_low']?:'');
		$phigh  = trim(@$SEARCH['price_usd_high']?:'');
		$mylow  = trim(@$SEARCH['model_year_start']?:'');
		$myhigh = trim(@$SEARCH['model_year_end']?:'');
		$zone   = trim(@$SEARCH['searchZone']?:0);
		

		$CONTENT = <<<JS
try { $("#mode_${mode}").prop("checked",true); } catch (err) { }
try { $("#search_terms").val("${terms}"); } catch (err) { }
try { $("#titleOnly").prop( "checked", ${titly} ); } catch (err) { }
try { $("#recentOnly").prop( "checked", ${recnt} ); } catch (err) { }
try { $("#exclude_terms").val("${exttr}"); } catch (err) { }
try { $("#price_usd_low").val("${plow}"); } catch (err) { }
try { $("#price_usd_high").val("${phigh}"); } catch (err) { }
try { $("#model_year_start").val("${mylow}"); } catch (err) { }
try { $("#model_year_end").val("${myhigh}"); } catch (err) { }
try { $("#searchZone").val("${zone}"); } catch (err) { }

<!-- animate -->
try { 
	$("#button_1").animate( { backgroundColor: "#afa" }, 100 ); 
	$("#button_1").animate( { backgroundColor: "transparent" }, 6000 );
} catch (err) {}

console.log('SEARCH $sid LOADED');
JS;

//print_r($SEARCH,true);
		// Attempt to load the form!
	}
}
/*
  EXAMPLE OUTPUT 
{
	"type":"motorcycle",
	"mode":"mca",
	"zonefilter":"",
	"search_terms":"ducati 998",
	"titleOnly":true,
	"exclude_terms":"cagiva monster parts ",
	"price_usd_low":"100 ",
	"price_usd_high":"",
	"model_year_start":"",
	"model_year_end":"",
	"searchZone":"1",
	"submit_search":"Submit Search"
}
*/

header('Content-type: text/javascript; charset=utf-8');
?>
<?= $CONTENT ?>
