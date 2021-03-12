<?php

require_once('session.class.php');

class Feeedback extends Session {

	private $FORM   = ''; // form container  
	private $MENU     = '';

	// = = = = = = = = = = = = = = = = = = = = = = = = =  
	//  C O N S T R U C T O R  
	// = = = = = = = = = = = = = = = = = = = = = = = = =  

	public function __construct(){
		// perform some initialization actions here	

		// check to see if user is logged in or not
	}

	// - - - - - - - - - - - - - - - - - - - - - -

	// = = = = = = = = = = = = = = = = = = = = = = = = =  
	//  P R I V A T E    F U N C T I O N S  
	// = = = = = = = = = = = = = = = = = = = = = = = = =  

	// - - - - - - - - - - - - - - - - - - - - - -
	//

	// = = = = = = = = = = = = = = = = = = = = = = = = =  
	//  P R O T E C T E D    F U N C T I O N S
	// = = = = = = = = = = = = = = = = = = = = = = = = =  

	// - - - - - - - - - - - - - - - - - - - - - -
	//

	// = = = = = = = = = = = = = = = = = = = = = = = = =  
	//  P U B L I C    F U N C T I O N S
	// = = = = = = = = = = = = = = = = = = = = = = = = =  

	// - - - - - - - - - - - - - - - - - - - - - -
	//
}
/*
<div id="cardBody"><style> #comments, #ratings, #commentContent textarea { width: 100%; float: none; } .cduRating { display: none; } #overallRating { display: inline; } .ratingHeader { width: auto; } #overallRating .ratingHeader { font-weight: normal; } td.smalltext { width: 9%; } textarea.inputText { width: 240px; } #ratings { border-bottom: 1px dotted #CCC; } </style><div id="customQuestionsTop">
<div class="customQuestion"><script language="javascript" type="text/javascript"> quesNum++; </script><input name="question1" value="138065" type="hidden"><span class="questionText" id="qt_138065"><span id="qid_138065" class="questionSpan qSpan138065">Do you visit mobile.de for business or private reasons?</span></span><span class="questionAnswertype" id="qa_138065"><div style="text-align: left;"><input name="answer_138065" value="Visit for  business  reasons" type="radio"> Visit for  business  reasons<br><input name="answer_138065" value="Visit for  private reasons" style="margin-top: 7px;" type="radio"> Visit for  private reasons</div></span><div></div>
</div>
</div>
<div id="mainSection">
<div id="comments">
<h1 class="header" id="commentHeader">What are your comments for us? </h1>
<div id="commentContent"><span id="tsText" class=""><input name="topic_selection" value="138067" type="hidden"><select name="answer_138067"><option value="0">Choose a topic for your comments...</option><option value="Errors on our site">Errors on our site</option><option value="Complaints concerning vehicle ads">Complaints concerning vehicle ads</option><option value="Other Complaints">Other Complaints</option><option value="Suggestions">Suggestions</option><option value="Compliments">Compliments</option><option value="Others">Others</option></select></span><textarea name="comments" onfocus="clearbox(this)" wrap="virtual" class="medium" cols="30">Please enter your feedback here.</textarea><script language="javascript" type="text/javascript" src="https://secure.opinionlab.com/includes/commentLimit.js"></script><div id="commentLimit"><script>displaylimit('document.CommentCard.comments',1000)</script><span id="document.CommentCard.comments">1000</span> characters remaining.</div>
</div>
</div>
<div id="ratings">
<div id="cduContent">
<div class="cduRating" id="contentRating"><span class="ratingHeader">Content</span><ul>
<li><input name="content" value="5" id="c5" type="radio"><label for="c5" id="c5l">+ +</label></li>
<li><input name="content" value="4" id="c4" type="radio"><label for="c4" id="c4l">+</label></li>
<li><input name="content" value="3" id="c3" type="radio"><label for="c3" id="c3l">+ -</label></li>
<li><input name="content" value="2" id="c2" type="radio"><label for="c2" id="c2l">-</label></li>
<li><input name="content" value="1" id="c1" type="radio"><label for="c1" id="c1l">- -</label></li>
</ul>
<div></div>
</div>
<div class="cduRating" id="designRating"><span class="ratingHeader">Design</span><ul>
<li><input name="design" value="5" id="d5" type="radio"><label for="d5" id="d5l">+ +</label></li>
<li><input name="design" value="4" id="d4" type="radio"><label for="d4" id="d4l">+</label></li>
<li><input name="design" value="3" id="d3" type="radio"><label for="d3" id="d3l">+ -</label></li>
<li><input name="design" value="2" id="d2" type="radio"><label for="d2" id="d2l">-</label></li>
<li><input name="design" value="1" id="d1" type="radio"><label for="d1" id="d1l">- -</label></li>
</ul>
<div></div>
</div>
<div class="cduRating" id="usabilityRating"><span class="ratingHeader">Usability</span><ul>
<li><input name="usability" value="5" id="u5" type="radio"><label for="u5" id="u5l">+ +</label></li>
<li><input name="usability" value="4" id="u4" type="radio"><label for="u4" id="u4l">+</label></li>
<li><input name="usability" value="3" id="u3" type="radio"><label for="u3" id="u3l">+ -</label></li>
<li><input name="usability" value="2" id="u2" type="radio"><label for="u2" id="u2l">-</label></li>
<li><input name="usability" value="1" id="u1" type="radio"><label for="u1" id="u1l">- -</label></li>
</ul>
<div></div>
</div>
<div class="cduRating" id="overallRating"><span class="ratingHeader" id="ovText">Overall</span><ul>
<li id="oLI5"><input name="overall" value="5" id="o5" type="radio"><label for="o5" id="o5l">+ +</label></li>
<li id="oLI4"><input name="overall" value="4" id="o4" type="radio"><label for="o4" id="o4l">+</label></li>
<li id="oLI3"><input name="overall" value="3" id="o3" type="radio"><label for="o3" id="o3l">+ -</label></li>
<li id="oLI2"><input name="overall" value="2" id="o2" type="radio"><label for="o2" id="o2l">-</label></li>
<li id="oLI1"><input name="overall" value="1" id="o1" type="radio"><label for="o1" id="o1l">- -</label></li>
</ul>
<div></div>
</div>
<div></div>
</div>
<div></div>
</div>
<div></div>
</div>
<div id="customQuestions">
<div id="scrollQuestions">
<div class="customQuestion"><script language="javascript" type="text/javascript"> quesNum++; </script><input name="question2" value="138064" type="hidden"><span class="questionText" id="qt_138064"><span id="qid_138064" class="questionSpan qSpan138064">How likely are you to recommend mobile.de to family, friends, or acquaintances?</span></span><span class="questionAnswertype" id="qa_138064"><table border="0" cellpadding="0" cellspacing="0" width="250"><tbody><tr><td colspan="6">Not at all likely</td><td colspan="5" align="right">Extremely likely</td></tr><tr><td class="smalltext" align="center" valign="top"><input class="body" value="Zero" name="answer_138064" type="radio"></td><td class="smalltext" align="center" valign="top"><input class="body" value="1" name="answer_138064" type="radio"></td><td class="smalltext" align="center" valign="top"><input class="body" value="2" name="answer_138064" type="radio"></td><td class="smalltext" align="center" valign="top"><input class="body" value="3" name="answer_138064" type="radio"></td><td class="smalltext" align="center" valign="top"><input class="body" value="4" name="answer_138064" type="radio"></td><td class="smalltext" align="center" valign="top"><input class="body" value="5" name="answer_138064" type="radio"></td><td class="smalltext" align="center" valign="top"><input class="body" value="6" name="answer_138064" type="radio"></td><td class="smalltext" align="center" valign="top"><input class="body" value="7" name="answer_138064" type="radio"></td><td class="smalltext" align="center" valign="top"><input class="body" value="8" name="answer_138064" type="radio"></td><td class="smalltext" align="center" valign="top"><input class="body" value="9" name="answer_138064" type="radio"></td><td class="smalltext" align="center" valign="top"><input class="body" value="10" name="answer_138064" type="radio"></td></tr><tr><td class="smalltext" align="center" valign="top">0</td><td class="smalltext" align="center" valign="top">1</td><td class="smalltext" align="center" valign="top">2</td><td class="smalltext" align="center" valign="top">3</td><td class="smalltext" align="center" valign="top">4</td><td class="smalltext" align="center" valign="top">5</td><td class="smalltext" align="center" valign="top">6</td><td class="smalltext" align="center" valign="top">7</td><td class="smalltext" align="center" valign="top">8</td><td class="smalltext" align="center" valign="top">9</td><td class="smalltext" align="center" valign="top">10</td></tr></tbody></table></span><div></div>
</div>
<div class="customQuestion"><script language="javascript" type="text/javascript"> quesNum++; </script><input name="question3" value="138066" type="hidden"><span class="questionText" id="qt_138066"><span id="qid_138066" class="questionSpan qSpan138066">Please tell us why you feel that way about your likelihood to recommend mobile.de to family, friends, or acquaintances.</span></span><span class="questionAnswertype" id="qa_138066"><textarea name="answer_138066" id="answer_138066" wrap="virtual" rows="2" class="inputText"></textarea></span><div></div>
</div>
<div></div>
</div>
</div><div style="padding: 10px; text-align: left;">Please do not leave personal information. To contact our customer service, please <a href="http://auto-verkaufen.mobile.de/fsbo/static/help/faqEntry.html?isPopup=true" target="_blank">click here</a>.</div></div>

*/
?>
