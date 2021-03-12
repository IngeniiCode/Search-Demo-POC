<?php

$TOOLS = <<<SHARE
<div class="buttons">
<!--
<div id="searchSearch">
  <a href="#" onClick="return toggleDiv('#shareFormDiv');" class="toolLink">SHARE!</a>
  <div id="shareFormDiv" class="toolToggle hidden">
  <form id="shareForm" name="shareForm">
    <b>SOME SORT OF SHARING STUFF GOING ON HERE</b>
    <button class="btn_general" type="button" onClick="shareSearch('shareSearch');">SHARE!</button>&nbsp;&nbsp;<button class="btn_general" type="button" onClick="return toggleDiv('#shareFormDiv');">Cancel</button>
  </form>
  </div> -->
</div>
SHARE;


if($x_auth = @$_COOKIE['x-auth']){

	// show the save search button
	$TOOLS .= <<<SAVE
<div id="saveSearch">
  <a href="#" onClick="return toggleDiv('#saveFormDiv');" class="toolLink">Save Search</a>
  <div id="saveFormDiv" class="toolToggle hidden">
  <form id="saveForm" name="saveForm">
    name: <input class="input_text" type="text" size="20" id="saveName" name="saveName"><br>  
    <button class="btn_general" type="button" onClick="saveSearch('saveSearch');">Save</button>&nbsp;&nbsp;<button class="btn_general" type="button" onClick="return toggleDiv('#saveFormDiv');">Cancel</button>
  </form>
  </div>
</div>
SAVE;

	// show the download report button
	$TOOLS .= <<<DOWNLOAD
<div id="downloadResults">
  <a href="#" onClick="return toggleDiv('#downloadFormDiv');" class="toolLink">Download Results</a>
  <div id="downloadFormDiv" class="toolToggle hidden">
  <form id="MMH" action="/ast/generate.pdf.php" method="POST" target="PDF">
    filename: <input class="input_text" type="text" size="20" id="downloadFileName" name="downloadFileName"><br>
    <input type="hidden" value="x" name="MMHD" id="MMHD">
    <input type="hidden" value="bar" name="foo">
    <button id="MMHB" class="btn_general" type="submit">Download</button>&nbsp;&nbsp;<button class="btn_general" type="button" onClick="return toggleDiv('#downloadFormDiv');">Cancel</button>
  </form>
  </div>
</div>
DOWNLOAD;

	$TOOLS .= '</div>';
}
else {
	$TOOLS .= '<br><em>Members enjoy expanded search tools!</em><br><a href="/Membership" target="_mbr"><b>Click Here to Join!</b></a>';
}

$TOOLS .= '</div>';

?>
<u>Search Tools</u>
<?= $TOOLS ?>
