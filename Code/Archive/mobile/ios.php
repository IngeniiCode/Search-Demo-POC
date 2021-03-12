<?php

require_once($_SERVER['DOCUMENT_ROOT'].'/gl.php');

// Generate header
$HEADER       = header_white('','');
$BANNER       = banner_head_white();
$BAR_LEFT     = left_content_surveys(); 
$FOOTER       = footer_new();
$MAIN_RIGHT = <<<MAIN
<div id="content_right">
    <h1>Mobile Ninja Apps</h1>
</div>
MAIN;

$MAIN_CONTENT = '';

?>
<!DOCTYPE html>
<html>
<?=$HEADER?>
<body>
<div id='main_body'>
  <?=$BANNER?>
  <div id='content'>
    <table>
    <tr>
      <td id="col_left" width="<?=$WIDTH_LEFT_COL?>"><?=$BAR_LEFT?></td>
      <td id="col_right" width="<?=$WIDTH_RIGHT_COL?>"><?=$MAIN_RIGHT?></td>
    <tr>
    </table>
    <?=$MAIN_CONTENT?>
  </div>
  <?=$FOOTER?>
  <?=$ANALYTICS?>
</div>
</body>
</html>
