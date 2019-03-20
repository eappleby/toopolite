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
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"
      xmlns:fb="http://www.facebook.com/2008/fbml">
	<head prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# toopolitetoask: http://ogp.me/ns/fb/toopolitetoask#">
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
		hello world
	</body>
</html>