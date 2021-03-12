<?

// store a cookie for the feedback
$date_of_expiry = time() + 60 * 30 ;  // cookie to last 30 minutes
setcookie('x-f',$date_of_expiry,$date_of_expiry,'/');  // login cookie lasts for 1 hour

?>
<h2>Feedback</h2>
<hr>
<form action="#" name="fba<?= $date_of_expiry ?>" id="fba" method="get">
<div id="fbq_1" class="question">
  <div class="query">Is this your first visit to Outspoken Ninja?</div>
  <div class="options">
    <input name="firstvisit" value="First Visit" type="radio">Yes<br>
    <input name="firstvisit" value="Returning User" type="radio">No
  </div>
</div>
<div id="fbq_2" class="question">
  <div class="query">General Impression of the Site</div>
  <div class="options">
    <input name="impression" value="Awesome" type="radio">Awesome
    <input name="impression" value="Useful" type="radio">Useful
    <input name="impression" value="OK" type="radio">OK
    <input name="impression" value="Fair" type="radio">Fair
    <input name="impression" value="Poor" type="radio">Poor 
  </div>
</div>
<div id="fbq_3" class="question">
  <div class="query">What I Like Most is:</div>
  <div class="options">
  <textarea name="like" cols=40 rows=4></textarea>
  </div>
</div>
<div id="fbq_3" class="question">
  <div class="query">What I Like Least is:</div>
  <div class="options">
  <textarea name="dislike" cols=40 rows=4></textarea>
  </div>
</div>
<div id="fbq_90" class="question">
  <div class="query">Did you visit Outspoken Ninja for business or pleasure?</div>
  <div class="options">
    <input name="whyvisit" value="Business visit" type="radio">business<br>
    <input name="whyvisit" value="Pleasure visit" type="radio">pleasure
  </div>
</div>
<div id="fbq_91" class="question">
  <div class="query">Do you think you will use the site again?</div>
  <div class="options">
    <input name="revisit" value="Certainly" type="radio">Certainly
    <input name="revisit" value="Probably" type="radio">Probably<br>
    <input name="revisit" value="Maybe" type="radio">Maybe
    <input name="revisit" value="Unlikely" type="radio">Unlikely
    <input name="revisit" value="Very Unlikely" type="radio">Very Unlikely
  </div>
</div>
<div id="fbq_95" class="question">
  <div class="query">Would you recommend Outspoken Ninja to others?</div>
  <div class="options">
    <input name="share" value="Yes" type="radio">Yes
    <input name="share" value="Maybe" type="radio">Maybe
    <input name="share" value="No" type="radio">No
    <br>Because:<br><textarea name="sharebecause" cols=40 rows=2></textarea>
  </div>
</div>

<div class="buttons">
<button type="button" name="login_std" onClick="send_feedback('fba');" tabindex="13">Send Feedback</button>
<button type="button" name="cancel" onClick="hide_feedback();" tabindex="99">Cancel</button>
</div>
</form>
