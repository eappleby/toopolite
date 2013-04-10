<?php
require("methods/db-calls.php");
$s_title = "";
$s_answer = "";
if (isset($_REQUEST["a"])) { 
	if (isset($_REQUEST["p"])) { 
		$s_title = "Last Submitted Answer - Too Polite To Ask"; 
		$s_answer = getAnswerPrevious ($_REQUEST["a"]);
	} else { 
		$s_title = "Original Answer - Too Polite To Ask"; 
		$s_answer = getAnswerFirst ($_REQUEST["a"]);
	}
}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml"
      xmlns:fb="http://www.facebook.com/2008/fbml">
	<head>
		<title><?php echo htmlClean ($s_title); ?></title>
		<link rel="icon" type="image/ico" href="<?php echo getUrl("favicon.ico"); ?>"></link> 
		<link rel="shortcut icon" href="<?php echo getUrl("favicon.ico"); ?>"></link>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/> 
		<meta name="title" content="<?php echo htmlClean ($s_title); ?>">
		<link rel="image_src" href="<?php echo getUrl("images/Too-Polite-To-Ask_image.png"); ?>" />
		<link rel="stylesheet" type="text/css" href="<?php echo getUrl("css/style.css"); ?>">
		<!--[if IE]>
        <link rel="stylesheet" type="text/css" href="<?php echo getUrl("css/style-ie.css"); ?>" />
		<![endif]-->
	</head>
	<body>
		<div id="answer-popup"><?php echo htmlClean ($s_answer); ?></div>
	</body>
</html>