<?php
session_start();
require_once("facebook-php-sdk/src/facebook.php");
require("methods/db-calls.php");

$facebook = new Facebook(array(
  'appId'  => '158967034168646',
  'secret' => $_ENV["FACEBOOK_SECRET"],
));
$user = $facebook->getUser();

if ($user) {
	try {
		// Proceed knowing you have a logged in user who's authenticated.
		$user_profile = $facebook->api('/me');
	} catch (FacebookApiException $e) {
		error_log($e);
		$user = null;
	}
}
include("declarations/form-vars.php");
include("declarations/session-vars.php");
include("declarations/topic-vars.php");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "https://www.w3.org/TR/html4/strict.dtd">
<html xmlns="https://www.w3.org/1999/xhtml"
      xmlns:fb="https://www.facebook.com/2008/fbml">
	<head prefix="og: https://ogp.me/ns# fb: https://ogp.me/ns/fb# toopolitetoask: https://ogp.me/ns/fb/toopolitetoask#">
		<title><?php echo htmlClean($_SESSION["meta-title"]); ?></title>
		<link rel="icon" type="image/ico" href="<?php echo getUrl("favicon.ico"); ?>"></link> 
		<link rel="shortcut icon" href="<?php echo getUrl("favicon.ico"); ?>"></link>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/> 
		<?php if (!((empty($_GET))||(isset($_REQUEST["ra"]))||(isset($_REQUEST["abt"])))) { echo "<META NAME=\"robots\" CONTENT=\"NOINDEX\">"; } ?>
		<meta property="fb:app_id" content="158967034168646" />
		<meta property="fb:admins" content="2259453" />
		<meta property="og:site_name" content="Too Polite To Ask" />
		<meta property="og:description" content="<?php echo htmlClean($_SESSION["meta-description"]); ?>" />
		<meta property="og:title" content="<?php echo htmlClean($_SESSION["meta-title"]); ?>" />
		<meta property="og:url" content="<?php echo getUrl("", true); ?>" />
		<meta property="og:image" content="<?php echo getUrl("images/Too-Polite-To-Ask_image.png"); ?>" />
		<meta property="og:type" content="<?php if (isset($_REQUEST["ra"])) { echo "article"; } else { echo "website"; } ?>" />
<?php if (isset($_REQUEST["ra"])) { echo "		<meta property=\"article:section\" content=\"".getAnswerCategory($_SESSION['a_selectedAnsweredTopic']['id'])."\">"; } ?>
		<meta name="title" content="<?php echo htmlClean($_SESSION["meta-title"]); ?>">
		<meta name="description" content="<?php echo htmlClean($_SESSION["meta-description"]); ?>" />
		<link rel="image_src" href="<?php echo getUrl("images/Too-Polite-To-Ask_image.png"); ?>" />
		<link rel="stylesheet" type="text/css" href="<?php echo getUrl("css/style.css"); ?>">
		<!--[if IE]>
        <link rel="stylesheet" type="text/css" href="<?php echo getUrl("css/style-ie.css"); ?>" />
		<![endif]-->
		<?php include("js/tpta-js.php"); ?>
	</head>
	<body>
		<div id="fb-root"></div>
		<div id='header-bar'>
			<ul id='header-bar-content' >
 			    <li class='main-logo'>
					<a class="no_border" href="<?php echo getUrl(); ?>"><img src='/images/ibanner_txt.png'/></a>
			    </li>
				<li class="hb-button">
					<div class="nav-buttons">
						<div id="fbConnect">
						<?php if (!$user) : ?>
							<a class="navbar-login">Log in</a>
						<?php else : ?>
							<a class="navbar-other">Log out</a>
						<?php endif ?>
						</div>
					</div>
				</li>
				<li class="hb-button">
					<div class="nav-buttons"><a class="navbar-other" href="<?php echo getUrl("about-tpta/"); ?>">About</a></div>
				</li>
				<li class="hb-button">
					<div id="answerLink" class="nav-buttons">
						<?php if (!$user) {echo "Answer";} else {echo "<a class=\"navbar-other\" href=\"".getUrl("give-your-answer/".$_SESSION['a_selectedAnsweredTopic']['id']."/")."\">Answer</a>";} ?>
					</div>
				</li>
				<li class="hb-button">
					<div id="askLink" class="nav-buttons">
						<?php if (!$user) {echo "Ask";} else {echo "<a class=\"navbar-other\" href=\"".getUrl("suggest-a-topic/".$_SESSION['a_selectedAnsweredTopic']['id']."/")."\">Ask</a>";} ?>
					</div>
				</li>
			</ul>
		</div>
		<div id="entire-website">
			<div id="visible-area">
				<?php if ($b_overlay) { include ("overlay.php"); }	?>
				<?php if (isset($_REQUEST["ra"])) { include ("fbLearnMorePopup.php"); } ?>
				<div><br></div>
				<div id="empty-pane-with-question">
					<div class="empty-space">
						<div class="question">
							What does it feel like to...
						</div>
					</div>
				</div>
				<div id="topics-list-wrapper">
					<div id="topics-list">
						<?php
						print "						<div class=\"above-selected-wrapper\">";
						print "							<div class=\"above-selected\">";

						// Cycle through all the answered topics
						foreach ($_SESSION['a_answeredTopics'] as $lcv => $a_answeredTopic) {
						
							// If topic is exactly 16 above or 36 below the selected topic and there are more topics in that direction
							if ((($lcv == $_SESSION['a_selectedAnsweredTopic']['lcv'] + 36) && ($_SESSION['i_numAnsweredTopics'] > $_SESSION['a_selectedAnsweredTopic']['lcv'] + 36)) || (($lcv == $_SESSION['a_selectedAnsweredTopic']['lcv'] - 16)&&($lcv>0))){
							
								// Label that topic as "first", which will make it look like it is fading away
								print "								<a href=\"".getUrl("/topic/".$a_answeredTopic["id"]."/")."\" class=\"first\" onmouseover=\"showNewQuote(".$a_answeredTopic["id"].")\" onmouseout=\"revertToSelectedQuote()\">".htmlClean ($a_answeredTopic["topic"])."?</a>";
								
							// If topic is exactly 15 above or 35 below the selected topic in either direction
							} elseif ((($lcv == $_SESSION['a_selectedAnsweredTopic']['lcv'] + 35) && ($_SESSION['i_numAnsweredTopics'] > $_SESSION['a_selectedAnsweredTopic']['lcv'] + 35)) || (($lcv == $_SESSION['a_selectedAnsweredTopic']['lcv'] - 15)&&($lcv>1))){
							
								// Label that topic as "second", which will make it look like it is fading away, but not as much as if it were "first"
								print "								<a href=\"".getUrl("/topic/".$a_answeredTopic["id"]."/")."\" class=\"second\" onmouseover=\"showNewQuote(".$a_answeredTopic["id"].")\" onmouseout=\"revertToSelectedQuote()\">".htmlClean ($a_answeredTopic["topic"])."?</a>";
								
							// If this is the selected topic
							} elseif ($lcv == $_SESSION['a_selectedAnsweredTopic']['lcv']) {
								print "							</div>";
								print "						</div>";
								print "						<div class=\"selected\">";
								print "							".htmlClean ($a_answeredTopic["topic"])."?";
								print "						</div>";
								print "						<div class=\"below-selected\">";
								
							// If a topic is within 17 above and 37 below the selected topic
							} elseif (($lcv < $_SESSION['a_selectedAnsweredTopic']['lcv'] + 37) && ($lcv > $_SESSION['a_selectedAnsweredTopic']['lcv'] - 17)) {
							
								print "								<a href=\"".getUrl("/topic/".$a_answeredTopic["id"]."/")."\" onmouseover=\"showNewQuote(".$a_answeredTopic["id"].")\" onmouseout=\"revertToSelectedQuote()\">".htmlClean ($a_answeredTopic["topic"])."?</a>";
							}
						}
						print "						</div>";
						?>
					</div>
				</div>
				<div id="pictures-and-quote">
					<div class="picture1">
						<img src="<?php echo getUrl("images/Ferrane-Worker.png"); ?>">
					</div>
					<div class="social">
						<a class="facebook" href="https://www.facebook.com/toopolite" target="_blank">Become our fan on Facebook</a>
						<div class="facebook-like">
							<div class="fb-like" data-href="https://www.facebook.com/toopolite" data-send="false" data-show-faces="false" data-font="verdana"></div>
						</div>
					</div>
					<div class="quote">
						<div class="arrow">
							<div id="arrowSide1"></div>
							<div id="arrowImg"></div>
							<div id="arrowSide2"></div>
						</div>
						<div id="bubble">
							<div id="bubbleText">
								<?php 
								print "<p>".htmlClean ($_SESSION['a_approvedAnswers'][$_SESSION['a_selectedApprovedAnswer']['lcv']]['quote'])."  &nbsp;</p>";
								print "<a href=\"".getUrl("/what-it-feels-like-to-".$_SESSION['a_answeredTopics'][$_SESSION['a_selectedAnsweredTopic']['lcv']]['url']."/".$_SESSION['a_approvedAnswers'][$_SESSION['a_selectedApprovedAnswer']['lcv']]['id']."/")."\" class=\"read-more\">Read More &#187;</a>";
								?>
							</div>
							<div id="other-options">
								<?php

								// No need to display navigation if there is just one answer
								if (count($_SESSION['a_approvedAnswers'])>1) {

									// Cycle through the approved answers for the selected topic
									for ($lcv=0; $lcv<count($_SESSION['a_approvedAnswers']); $lcv++) {
									
										// If the answer is outside of the 5 answers nearest to the selected answer in the answer list
										if (($lcv < $_SESSION['a_selectedApprovedAnswer']['lcv'] - ($_SESSION['a_selectedApprovedAnswer']['lcv']%5)) || ($lcv >= $_SESSION['a_selectedApprovedAnswer']['lcv'] + 5 - ($_SESSION['a_selectedApprovedAnswer']['lcv']%5))) {
										
											// If the answer is exactly 5 less than the smallest lcv of the 5 answers nearest to the selected answer in the answer list
											if ($lcv == $_SESSION['a_selectedApprovedAnswer']['lcv']  - ($_SESSION['a_selectedApprovedAnswer']['lcv'] %5) - 5) {
												print "								<a href=\"".getUrl("/answer/".$_SESSION['a_approvedAnswers'][$lcv]['id']."/")."\" class=\"first\">&#171;</a>";
											}
											
											// If the answer is exactly 1 more than the largest lcv of the 5 answers nearest to the selected answer in the answer list
											elseif ($lcv == $_SESSION['a_selectedApprovedAnswer']['lcv']  - ($_SESSION['a_selectedApprovedAnswer']['lcv'] %5) + 5) {
												print "								<a href=\"".getUrl("/answer/".$_SESSION['a_approvedAnswers'][$lcv]['id'])."\">&#187;</a>";
											}
											
										// If the answer is the first answer in the answer list and is the selected answer
										} elseif (($lcv == 0) && ($lcv == $_SESSION['a_selectedApprovedAnswer']['lcv'])) {
											print "								<a class=\"selected first\">".($lcv+1)."</a>";
											
										// If the answer is the first answer in the answer list
										} elseif ($lcv == 0) {
											print "								<a href=\"".getUrl("/answer/".$_SESSION['a_approvedAnswers'][$lcv]['id'])."\" class=\"first\">".($lcv+1)."</a>";
											
										// If the answer is the selected answer
										} elseif ($lcv == $_SESSION['a_selectedApprovedAnswer']['lcv']) {
											print "								<a class=\"selected\">".($lcv+1)."</a>";
											
										// All others
										} else {
											print "								<a href=\"".getUrl("/answer/".$_SESSION['a_approvedAnswers'][$lcv]['id'])."\" >".($lcv+1)."</a>";
										}
									}
								}
								?>
							</div>
						</div>
					</div>
					<div class="picture2">
						<img src="<?php echo getUrl("images/Shiri-Appleby.png"); ?>">
					</div>
					<div class="picture3">
						<img src="<?php echo getUrl("images/Moroccan-Police-Officer.png"); ?>">
					</div>
					<div class="picture2">
						<img src="<?php echo getUrl("images/Young-Dina-Appleby.png"); ?>">
					</div>
<!--
					<div class="disclaimer">
						* Images above are not related to specific stories on this site
					</div>
-->
				</div>
			</div>
		</div>
		<?php include("js/outside-js.php"); ?>
	</body>
</html>