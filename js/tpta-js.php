<script language="JavaScript" type="text/javascript">
<!--
<?php if (isset($_REQUEST["ra"])) : ?>
function showModule (modId) {
	document.getElementById(modId).style.visibility = "visible";
}

function hideModule (modId) {
	document.getElementById(modId).style.visibility = "hidden";
}

function toggleModule (modId, modIdOff) {
	hideModule(modIdOff);
	var module = document.getElementById(modId);
	if (module.style.visibility == "visible") {
		module.style.visibility = "hidden";
	} else {
		module.style.visibility = "visible";
	}
}
<?php endif ?>

<?php if (($b_overlay) || (isset($_REQUEST["adm-at"])) || (isset($_REQUEST["adm-aa"])) || (isset($_REQUEST["adm-ut"])) || (isset($_REQUEST["adm-ua"]))): ?>
var validate=true;
function validate_form ( form ) {
	if (!validate) {validate = true; return true;}
	
    var valid = true;
	var emailRegEx = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
	
	if (form.username) {
		if ((form.username.value=="<?php echo jsClean ($s_username_FM); ?>") || (form.username.value=="")) {
			form.username.className = "input-error";
			valid = false;
		} else {
			form.username.className = "";
		}
	}
	if (form.email) {
		if ((form.email.value=="<?php echo jsClean ($s_email_FM); ?>") || (form.email.value=="")||(form.email.value.search(emailRegEx) == -1)) {
			form.email.className = "input-error";
			valid = false;
		} else {
			form.email.className = "";
		}
	}
	if (form.answer_text) {
		if ((form.answer_text.value=="<?php echo jsClean($s_answer_FM); ?>") || (form.answer_text.value=="")) {
			form.answer_text.className = "input-error";
			valid = false;
		} else {
			form.answer_text.className = "";
		}
	}
	if (form.legal_opt_in) {
		if (!form.legal_opt_in.checked) {
			document.getElementById("legal_text").className = "input-error-text";
			valid = false;
		} else {
			document.getElementById("legal_text").className = "";
		}
	}
	if (form.friend_username) {
		if ((form.friend_username.value=="<?php echo jsClean ($s_friend_username_FM); ?>") || (form.friend_username.value=="")) {
			form.friend_username.className = "input-error";
			valid = false;
		} else {
			form.friend_username.className = "";
		}
	}
	if (form.friend_email) {
		if ((form.friend_email.value=="<?php echo jsClean ($s_friend_email_FM); ?>") || (form.friend_email.value=="")||(form.friend_email.value.search(emailRegEx) == -1)) {
			form.friend_email.className = "input-error";
			valid = false;
		} else {
			form.friend_email.className = "";
		}
	}
	if (form.message) {
		if ((form.message.value=="<?php echo jsClean ($s_message_FM); ?>") || (form.message.value=="")) {
			form.message.className = "input-error";
			valid = false;
		} else {
			form.message.className = "";
		}
	}
	if (form.new_topic) {
		if (form.new_topic.value=="") {
			form.new_topic.className = "input-error";
			valid = false;
		} else {
			form.new_topic.className = "";
		}
	}
	if (form.quote) {
		if ((form.quote.value=="<?php echo jsClean ($s_quote_FM); ?>") || (form.quote.value=="")) {
			form.quote.className = "input-error";
			valid = false;
		} else {
			form.quote.className = "";
		}
	}
	if (form.rating) {
		if ((form.rating.value=="<?php echo jsClean ($s_rating_FM); ?>") || (form.rating.value=="")) {
			form.rating.className = "input-error";
			valid = false;
		} else {
			rating_int = parseInt(form.rating.value);
			if (isNaN(rating_int)) {
				form.rating.className = "input-error";
				valid = false;
			} else if ((rating_int <0) || (rating_int >100)) {
				form.rating.className = "input-error";
				valid = false;
			} else {
				form.rating.className = "";
			}
		}
	}
    return valid;
}

function processEnterKey(e) {
    if (null == e)
        e = window.event ;
    if (e.keyCode == 13)  {
        document.getElementById("accept_or_update").click();
        return false;
    }
}
<?php endif; ?>
<?php if ((!$b_overlay) && (isset($_SESSION['a_answeredTopics']))): ?>

<?php if (empty($_GET)) : ?>
var secondClick = true;
<?php else : ?>
var secondClick = false;
<?php endif; ?>

function showNewQuote (topicId) {
	if (!secondClick) { secondClick = true; } 
	else {
		document.getElementById('other-options').style.display = "none";
		document.getElementById('arrowImg').style.backgroundPosition = "0 -54px";
		document.getElementById('arrowSide1').style.borderRightColor = "#8a8a8a";
		document.getElementById('arrowSide2').style.borderRightColor = "#8a8a8a";
		var bubble = document.getElementById('bubble');
		bubble.style.borderTopColor = "#8a8a8a";
		bubble.style.borderRightColor = "#8a8a8a";
		bubble.style.borderBottomColor = "#8a8a8a";
		
		
		switch(topicId) {
<?php
			// Cycle through all the answered topics
			foreach ($_SESSION['a_answeredTopics'] as $lcv => $a_answeredTopic) {
			
				// If topic is exactly 16 above or 36 below the selected topic and there are more topics in that direction
				if (($lcv <= $_SESSION['a_selectedAnsweredTopic']['lcv'] + 36) && (($lcv >= $_SESSION['a_selectedAnsweredTopic']['lcv'] - 16))){
					$a_answer = getAnswerQuote($a_answeredTopic["id"]);
					print "			case ".$a_answeredTopic["id"].":\n";
					print "				document.getElementById('bubbleText').innerHTML = \"<p class=\\\"preview\\\">".htmlClean ($a_answer['quote'])."</p>\";\n";
					print "				break;\n";
				}
			}
?>
		}
	}
}

function revertToSelectedQuote () {
	document.getElementById('other-options').style.display = "block";
	document.getElementById('arrowImg').style.backgroundPosition = "0 0";
	document.getElementById('arrowSide1').style.borderRightColor = "#009418";
	document.getElementById('arrowSide2').style.borderRightColor = "#009418";
	var bubble = document.getElementById('bubble');
	bubble.style.borderTopColor = "#009418";
	bubble.style.borderRightColor = "#009418";
	bubble.style.borderBottomColor = "#009418";
	
	document.getElementById('bubbleText').innerHTML = "<?php echo "<p>".htmlClean ($_SESSION['a_approvedAnswers'][$_SESSION['a_selectedApprovedAnswer']['lcv']]['quote'])."  &nbsp;</p><a href=\\\"/what-it-feels-like-to-".$_SESSION['a_answeredTopics'][$_SESSION['a_selectedAnsweredTopic']['lcv']]['url']."/".$_SESSION['a_approvedAnswers'][$_SESSION['a_selectedApprovedAnswer']['lcv']]['id']."\\\" class=\\\"read-more\\\">Read More &#187;</a>"; ?>";
}
<?php endif; ?>

<?php if (isset($_REQUEST["adm-ua"])): ?>
function answerPopup(url) {
	newwindow=window.open(url,'name','height=450,width=520,top=200,left=300,resizable');
	if (window.focus) {newwindow.focus()}
}
<?php endif; ?>
//-->
</script>