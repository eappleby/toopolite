<?php
session_start();
require_once("facebook-php-sdk/src/facebook.php");

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
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"
      xmlns:fb="http://www.facebook.com/2008/fbml">
	<head prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# toopolitetoask: http://ogp.me/ns/fb/toopolitetoask#">
	</head>
	<body>
		hello world
	</body>
</html>