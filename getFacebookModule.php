<?php 
	require_once("facebook-php-sdk/src/facebook.php");
	$facebook = new Facebook(array(
	  'appId'  => '158967034168646',
	  'secret' => '71da3bb60bb033ddda0ca955bb4e31b7',
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
	if (!$user) {
		echo <<<HTML
											<div id="facebook-share">
												<div id="fb-inner-mod">
													<div id="facebook-connection">
														<div class="avatar">
															<img src="/images/fb-avatar.gif">
														</div>
														<p>
															Share the stories you read on Facebook
															<a href="#" class="learn-facebook">Learn More &#187</a>
														</p>
														<div class="enable-fb-sharing">
															<div class="fb-login-button" onlogin="after_login_button()" data-show-faces="false" data-width="200" data-max-rows="1">Enable Sharing</div>
														</div>
													</div>
												</div>
											</div>
HTML;
	} else {
		echo <<<HTML
											<div id="facebook-share">
												<div id="fb-inner-mod">
													<div id="facebook-connection">
														<div class="avatar">
															<img src="http://graph.facebook.com/$user/picture?type=square">
														</div>
														<div class="facebook-share-content">
															<div class="logged-into-facebook">
																Logged into Facebook as
															</div>
															<div class="facebook-name">
																$user_profile[name]
															</div>
															<div class="facebook-share-links">
																<a href="#">Your Activity</a> | <a href="#"> Social: ON</a>
															</div>
														</div>
													</div>
												</div>
											</div>
HTML;
	}
?>