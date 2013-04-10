<?php
session_start();
require("../methods/db-calls.php");
include("../declarations/form-vars.php");
include("../declarations/session-vars.php");
include("../declarations/adm-session-vars.php");
include("../declarations/topic-vars.php");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"
      xmlns:fb="http://www.facebook.com/2008/fbml">
	<head>
		<title><?php echo htmlClean($_SESSION["meta-title"]); ?></title>
		<link rel="icon" type="image/ico" href="<?php echo getUrl("favicon.ico"); ?>"></link> 
		<link rel="shortcut icon" href="<?php echo getUrl("favicon.ico"); ?>"></link>
		<meta name="robots" content="noindex">
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/> 
		<meta property="fb:page_id" content="203905262986317" />		
		<meta property="og:site_name" content="Too Polite To Ask" />
		<meta property="og:description" content="<?php echo htmlClean($_SESSION["meta-description"]); ?>" />
		<meta property="og:title" content="<?php echo htmlClean($_SESSION["meta-title"]); ?>" />
		<meta property="og:url" content="<?php echo getUrl("", true); ?>" />
		<meta property="og:image" content="<?php echo getUrl("images/Too-Polite-To-Ask_image.png"); ?>" />
		<meta property="og:type" content="website" />
		<link rel="stylesheet" type="text/css" href="<?php echo getUrl("css/style.css"); ?>">
		<link rel="stylesheet" type="text/css" href="<?php echo getUrl("css/style-admin.css"); ?>">
		<!--[if IE]>
        <link rel="stylesheet" type="text/css" href="<?php echo getUrl("css/style-ie.css"); ?>" />
		<![endif]-->
		<?php include("../js/tpta-js.php"); ?>
	</head>
	<body>
		<div id="entire-website">
			<div id="visible-area">
				<div id="overlay">
					<?php include ("../update-using-topic-list.php"); ?>
				</div>
				<div id="empty-pane-with-question">
					<div class="logo">
						<ul class="logo-nav">
							<li class="logo-too"><a href="<?php echo getUrl(); ?>"></a></li>
							<li class="logo-polite"><a href="<?php echo getUrl(); ?>"></a></li>
							<li class="logo-to"><a href="<?php echo getUrl(); ?>"></a></li>
							<li class="logo-ask"><a href="<?php echo getUrl(); ?>"></a></li>							
						</ul>
					</div>
					<div class="slogan">
						a website dedicated to understanding others
					</div>			
				</div>
			</div>
		</div>
	</body>
</html>