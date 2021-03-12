<?php

require_once($_SERVER['DOCUMENT_ROOT'].'/gl.php');

$MAIN_RIGHT = load_main_html(__FILE__);
$MAIN_BODY  = load_body_html(__FILE__);

// Generate header
$HEADER       = header_white('','');
$BANNER       = banner_head_white();
$BAR_LEFT     = left_content_surveys(); 
$FOOTER       = footer_new();

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
    <?=$MAIN_BODY?>
  </div>
  <?=$FOOTER?>
  <?=$ANALYTICS?>
</div>
</body>
</html>
