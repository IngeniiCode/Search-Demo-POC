<?php

require_once($_SERVER['DOCUMENT_ROOT'].'/gl.php');

// Generate header
$HEADER       = gen_header('','');
$BANNER_HEAD  = gen_banner_head();
$BANNER_IMAGE = gen_banner_img();
$MAIN_CONTENT = '';
$SPLASH_AD    = '';
$COL_LEFT     = '';
/*
$SPLASH_AD    = gen_splash_ad();
$COL_LEFT     = active_surveys_col();
*/
$FOOTER       = footer_new();

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
         <div id="bannerleft" style="border:2px red dashed;">
           <?php print $SPLASH_AD; ?>
         </div>
				
         <div id="bannerright" class="landing-banner-outer" style="border:2px blue dashed;>
            <div class="landing-banner-mid">
               <div class="landing-banner-inner">
                  <?php print $COL_LEFT; ?>
               </div><!-- end banner inner -->
            </div><!-- end banner mid -->
         </div><!-- end banner-right -->
					
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

<div id="footer-wrap" style="border:2px gray dotted;">
   <?php print $FOOTER.$ANALYTICS; ?>
</div><!-- end footer-wrap -->
</body>
</html>
