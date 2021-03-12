<?php
require_once('g12/gl.php');

// Generate header
$HEADER       = gen_header('','');
$BANNER_HEAD  = gen_banner_head();
$BANNER_IMAGE = gen_banner_img();
$SPLASH_AD    = gen_splash_ad();
$COL_LEFT     = gen_left_col();

?>
<!DOCTYPE html>
<html>
<?php print $HEADER ?>
<body class='landing-page wsite-theme-light wsite-page-index'>
<?php print $BANNER_HEAD; ?>
<div id="page-wrap">
<?php print $BANNER_IMAGE; ?> 		

<div id="banner-wrap" style="border:3px gray solid;">
   <div class="container">
      <div id="banner" style="border:2px green solid;">

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
      </div><!-- end banner -->
   </div><!-- end container -->
</div><!-- end banner-wrap -->
	
<div id="main-wrap" style="border: 3px red dashed;">
   <div class="container">
     <?php $MAIN_CONTENT ?>
   </div>
</div>
				<div id='wsite-content' class='wsite-elements wsite-not-footer'>
<div><div class="wsite-multicol"><div class='wsite-multicol-table-wrap' style='margin:0 -15px'>
<table class='wsite-multicol-table'>
<tbody class='wsite-multicol-tbody'>
<tr class='wsite-multicol-tr'>
<td class='wsite-multicol-col' style='width:79.094827586207%;padding:0 15px'>

<h2 style="text-align:left;">YOU'RE the Expert, Share your opinion! <br /></h2>

<div>
<form enctype="multipart/form-data" action="http://www2.dragndropbuilder.com/editor/apps/formSubmit.php" method="POST" id="form-272981144949958149">
<div id="272981144949958149-form-parent" class="wsite-form-container" style="margin-top:10px;">
  <ul class="formlist" id="272981144949958149-form-list">
    <div><div class="wsite-form-field" style="margin:5px 0px 0px 0px;">
  <label class="wsite-form-label" for="input-207026828397040022">How did you hear about this site? <span class="form-required">*</span></label>
  <div class="wsite-form-radio-container">
    <span class='form-radio-container'><input type='radio' id='radio-0-_u207026828397040022' name='_u207026828397040022' value='Internet Search' /><label for='radio-0-_u207026828397040022'>Internet Search</label></span>
<span class='form-radio-container'><input type='radio' id='radio-1-_u207026828397040022' name='_u207026828397040022' value='Advertisement' /><label for='radio-1-_u207026828397040022'>Advertisement</label></span>
<span class='form-radio-container'><input type='radio' id='radio-2-_u207026828397040022' name='_u207026828397040022' value='Friend' /><label for='radio-2-_u207026828397040022'>Friend</label></span>
<span class='form-radio-container'><input type='radio' id='radio-3-_u207026828397040022' name='_u207026828397040022' value='Other' /><label for='radio-3-_u207026828397040022'>Other</label></span>

  </div>
  <div id="instructions-How did you hear about this site?" class="wsite-form-instructions" style="display:none;"></div>
</div></div>

<div><div class="wsite-form-field" style="margin:5px 0px 5px 0px;">
				<label class="wsite-form-label" for="input-560104569563758456">If Other please specify: <span class="form-not-required">*</span></label>
				<div class="wsite-form-input-container">
					<input id="input-560104569563758456" class="wsite-form-input wsite-input wsite-input-width-285px" type="text" name="_u560104569563758456" />
				</div>
				<div id="instructions-560104569563758456" class="wsite-form-instructions" style="display:none;"></div>
			</div></div>

<div><div class="wsite-form-field" style="margin:5px 0px 0px 0px;">
  <label class="wsite-form-label" for="input-512073543353706339">What is your age? <span class="form-required">*</span></label>
  <div class="wsite-form-radio-container">
    <span class='form-radio-container'><input type='radio' id='radio-0-_u512073543353706339' name='_u512073543353706339' value='Less than 13' /><label for='radio-0-_u512073543353706339'>Less than 13</label></span>
<span class='form-radio-container'><input type='radio' id='radio-1-_u512073543353706339' name='_u512073543353706339' value='13-18' /><label for='radio-1-_u512073543353706339'>13-18</label></span>
<span class='form-radio-container'><input type='radio' id='radio-2-_u512073543353706339' name='_u512073543353706339' value='19-25' /><label for='radio-2-_u512073543353706339'>19-25</label></span>
<span class='form-radio-container'><input type='radio' id='radio-3-_u512073543353706339' name='_u512073543353706339' value='26-35' /><label for='radio-3-_u512073543353706339'>26-35</label></span>
<span class='form-radio-container'><input type='radio' id='radio-4-_u512073543353706339' name='_u512073543353706339' value='36-50' /><label for='radio-4-_u512073543353706339'>36-50</label></span>
<span class='form-radio-container'><input type='radio' id='radio-5-_u512073543353706339' name='_u512073543353706339' value='Over 50' /><label for='radio-5-_u512073543353706339'>Over 50</label></span>
<span class='form-radio-container'><input type='radio' id='radio-6-_u512073543353706339' name='_u512073543353706339' value='Prefer not to say' /><label for='radio-6-_u512073543353706339'>Prefer not to say</label></span>

  </div>
  <div id="instructions-What is your age?" class="wsite-form-instructions" style="display:none;"></div>
</div></div>

<div><div class="wsite-form-field" style="margin:5px 0px 0px 0px;">
  <label class="wsite-form-label" for="input-498717360904725518">What is your household income? <span class="form-required">*</span></label>
  <div class="wsite-form-radio-container">
    <span class='form-radio-container'><input type='radio' id='radio-0-_u498717360904725518' name='_u498717360904725518' value='Less than $10,000' /><label for='radio-0-_u498717360904725518'>Less than $10,000</label></span>
<span class='form-radio-container'><input type='radio' id='radio-1-_u498717360904725518' name='_u498717360904725518' value='$10,001 - $25,000' /><label for='radio-1-_u498717360904725518'>$10,001 - $25,000</label></span>
<span class='form-radio-container'><input type='radio' id='radio-2-_u498717360904725518' name='_u498717360904725518' value='$25,001 - $40,000' /><label for='radio-2-_u498717360904725518'>$25,001 - $40,000</label></span>
<span class='form-radio-container'><input type='radio' id='radio-3-_u498717360904725518' name='_u498717360904725518' value='$40,001 - $70,000' /><label for='radio-3-_u498717360904725518'>$40,001 - $70,000</label></span>
<span class='form-radio-container'><input type='radio' id='radio-4-_u498717360904725518' name='_u498717360904725518' value='$70,001 - $100,000' /><label for='radio-4-_u498717360904725518'>$70,001 - $100,000</label></span>
<span class='form-radio-container'><input type='radio' id='radio-5-_u498717360904725518' name='_u498717360904725518' value='Greater than $100,000' /><label for='radio-5-_u498717360904725518'>Greater than $100,000</label></span>
<span class='form-radio-container'><input type='radio' id='radio-6-_u498717360904725518' name='_u498717360904725518' value='Prefer not to say' /><label for='radio-6-_u498717360904725518'>Prefer not to say</label></span>

  </div>
  <div id="instructions-What is your household income?" class="wsite-form-instructions" style="display:none;"></div>
</div></div>
  </ul>
</div>
<div style="display:none; visibility:hidden;">
  <input type="text" name="wsite_subject" />
</div>
<div style="text-align:left; margin-top:10px; margin-bottom:10px;">
  <input type="hidden" name="form_version" value="2" />
  <input type="hidden" name="wsite_approved" id="wsite-approved" value="approved" />
  <input type="hidden" name="ucfid" value="272981144949958149" />
  <input type='submit' style='position:absolute;top:0;left:-9999px;width:1px;height:1px' /><a class='wsite-button' onclick="document.getElementById('form-272981144949958149').submit()"><span class='wsite-button-inner'>Submit</span></a>
</div>
</form>

</div>

</td>
<td class='wsite-multicol-col' style='width:20.905172413793%;padding:0 15px'>

<h2 style="text-align:left;"><font size="6">Product Information</font><br /><span></span><br /></h2>

<div><div id="537473613647424142" align="left" style="width: 100%; overflow-y: hidden;" class="wcustomhtml">Click to set custom <iframe width="180" height="135" src="//www.youtube.com/embed/eosYqd_fuJM" frameborder="0" allowfullscreen></iframe></div>

</div>

<div class="paragraph" style="text-align:left;"></div>

</td>
</tr>
</tbody>
</table>
</div></div></div></div>

			</div><!-- end container -->
		</div><!-- end main-wrap -->
	</div><!-- end page-wrap -->

    <div id="footer-wrap">
        <div class="container">
       		<div class='wsite-elements wsite-footer'>
<div><div class="wsite-multicol"><div class='wsite-multicol-table-wrap' style='margin:0 -15px'>
<table class='wsite-multicol-table'>
<tbody class='wsite-multicol-tbody'>
<tr class='wsite-multicol-tr'>
<td class='wsite-multicol-col' style='width:50%;padding:0 15px'></td>
<td class='wsite-multicol-col' style='width:50%;padding:0 15px'></td>
</tr>
</tbody>
</table>
</div></div></div>

<script type='text/javascript'>
<!--

if (document.cookie.match(/(^|;)\s*is_mobile=1/)) {
	var windowHref = window.location.href || '';
	if (windowHref.indexOf('?') > -1) {
		windowHref += '&';
	} else {
		windowHref += '?';
	}
	document.write(
		"&nbsp;&nbsp;&nbsp;&nbsp;" +
		"<a class='wsite-view-link-mobile' href='" + windowHref + "view=mobile'>Mobile Site</a>"
	);
}

//-->
</script>

        </div><!-- end container -->
    </div><!-- end footer-wrap -->

</body>
</html>
