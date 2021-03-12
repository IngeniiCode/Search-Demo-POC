<?php

require_once($_SERVER['NLIBS']."/page.php");

$PAGE->main_body("<h2>Outspoken.Ninja has encountered a Server Error.</h2><p>The event has bene logged and a report dispatched to our engineering staff.</p><p>We regret that The Outspoken Ninja cannot complete the request under the current circumstances.</p>");

$PAGE->http_response(500);
$PAGE->render();

?>
