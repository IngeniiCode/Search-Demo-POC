<?
if($type = @$_GET['type']){
	$tmp = <<<TMP
window.history.pushState({
  "html":response.html,
  "pageTitle":response.pageTitle
},
  "",
  ' THIS IS A TEST! ' 
);}
TMP;

printf($tmp,$type);
}

?>
console.log('PAGE TITLE');
