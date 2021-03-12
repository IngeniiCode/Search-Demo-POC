<?php

require_once($_SERVER['DOCUMENT_ROOT'].'/gl.php');

// Generate header
$HEADER       = gen_header('','');
$BANNER_HEAD  = gen_banner_head();
$BANNER_IMAGE = gen_banner_img();
$SPLASH_AD    = gen_splash_ad();
$COL_LEFT     = active_surveys_col();
$FOOTER       = footer_new();
$MAIN_CONTENT = '';

?>
<!DOCTYPE html>
<html>
<?php print $HEADER ?>
<body class='landing-page wsite-theme-light wsite-page-index'>
<?php print $BANNER_HEAD; ?>
<div id="page-wrap">
   <div class="page-core">
      <?php print $BANNER_IMAGE; ?> 		

      <div id="banner-wrap" style="border:3px gray solid;">
         <div class="container">
            <!--  MAIN CONTENT CELL -->
            <?php print $SPLASH_AD; ?>
			
            <?php print $COL_LEFT; ?>
					
            <div style="clear:both;"></div>
         </div><!-- end container -->
      </div><!-- end banner-wrap -->
	
      <div id="main-wrap" style="border: 3px red dashed;">
         <div class="container">
           <?php $MAIN_CONTENT ?>
         </div><!-- end container -->
      </div><!-- end main-wrap -->
   </div><!-- end page-core -->
</div><!-- end page-wrap -->

<?php print $FOOTER.$ANALYTICS; ?>
</body>
</html>
