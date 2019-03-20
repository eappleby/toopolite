<?php 
$s_topicHeader_LG = "";
$s_include_LG = "";
$s_abtSelected_LG = "class=\"first\"";
$s_csaSelected_LG = "";
$s_tosSelected_LG = "";
$s_ccpSelected_LG = "";
$s_ppoSelected_LG = "";
$s_aguSelected_LG = "";

if (isset($_REQUEST["abt"])) {
	$s_topicHeader_LG = "<h1>About Too Polite To Ask</h1>";
	$s_include_LG = "about-tpta.php";
	$s_abtSelected_LG = "class=\"first selected\"";
} else if (isset($_REQUEST["csa"])) {
	$s_topicHeader_LG = "<h2>User Content Submission Agreement</h2>";
	$s_include_LG = "submission-agreement.php";
	$s_csaSelected_LG = "class=\"selected\"";
} else if (isset($_REQUEST["tos"])) {
	$s_topicHeader_LG = "<h1>Terms of Service</h1>";
	$s_include_LG = "terms-of-service.php";
	$s_tosSelected_LG = "class=\"selected\"";
} else if (isset($_REQUEST["ccp"])) {
	$s_topicHeader_LG = "<h1>Copyright Compliance Policy</h1>";
	$s_include_LG = "copyright.php";
	$s_ccpSelected_LG = "class=\"selected\"";
} else if (isset($_REQUEST["ppo"])) {
	$s_topicHeader_LG = "<h1>Privacy Policy</h1>";
	$s_include_LG = "privacy-policy.php";
	$s_ppoSelected_LG = "class=\"selected\"";
} else if (isset($_REQUEST["agu"])) {
	$s_topicHeader_LG = "<h1>Answer Guidelines</h1>";
	$s_include_LG = "answer-guidelines.php";
	$s_aguSelected_LG = "class=\"selected\"";
}
?>