<?php

require_once($_SERVER['NLIBS']."/page.php");
$BODY = <<<BODY
<h2>OOPS! Something's wrong!</h2>

<p>Page you're looking for is not here.  Maybe the link is broken, or maybe you've wandered into a dark alley.  Good thing you're a Ninja!</p>

BODY;
$PAGE->main_body($BODY);
$PAGE->http_response(404);
$PAGE->render();

?>
