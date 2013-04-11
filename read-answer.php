					<div class="color-and-reading">
						<div id="color-bar" style="background:#<?php echo $_SESSION['a_approvedAnswer']['rgb']; ?>"></div>
						<div id="reading-pane">
							<div class="question"><h1>What does it feel like to <?php echo htmlClean ($_SESSION['a_answeredTopics'][$_SESSION['a_selectedAnsweredTopic']['lcv']]['topic']);?>?</h1></div>
							<div class="posted-by">
							<h2>By: <?php 
								if ($_SESSION['a_approvedAnswer']['citation_name']) {
									echo htmlClean ($_SESSION['a_approvedAnswer']['citation_name']);
								} elseif ($_SESSION['a_approvedAnswer']['anonymous']) { 
									echo "Anonymous"; 
								} else {
									echo htmlClean ($_SESSION['a_approvedAnswer']['name']);
								}
								if ($_SESSION['a_approvedAnswer']['citation_url']) { 

									// get host name from URL
									preg_match('@^(?:http://)?([^/]+)@i', $_SESSION['a_approvedAnswer']['citation_url'], $matches);
									$host = $matches[1];

									// get last two segments of host name
									preg_match('/[^.]+\.[^.]+$/', $host, $matches);

									echo " <a target=\"_blank\" href=\"".htmlClean ($_SESSION['a_approvedAnswer']['citation_url'])."\">via ".htmlClean ($matches[0])."</a>";
								} 
							?></h2>
							</div>
							<div class="answer-and-links-wrapper">
								<div class="links-wrapper">
									<div class="links first">
										<div id="load-fb-component">
											<div id="facebook-share">
												<div id="fb-inner-mod">
													<div id="facebook-connection">
<?php if (!$user) : ?>
														<div class="avatar">
															<img src="/images/fb-avatar.gif">
														</div>
														<p>
															Share the stories you read on Facebook
															<a href="#" onclick="showModule('fbLearnMorePopup')" class="learn-facebook">Learn More &#187</a>
														</p>
														<div class="enable-fb-sharing">
															<div class="fb-login-button" scope="email, publish_actions" onlogin="window.location.reload();" data-show-faces="false" data-width="100" data-max-rows="1">Enable Sharing</div>
														</div>
<?php else : ?>
														<div class="avatar">
															<img src="http://graph.facebook.com/<?php echo $user; ?>/picture?type=square">
														</div>
														<div class="facebook-share-content">
															<div class="logged-into-facebook">
																Logged into Facebook as
															</div>
															<div class="facebook-name">
																<?php echo $user_profile['name'];?>
															</div>
															<div class="facebook-share-links">
																<a onclick="toggleModule('myActivityModule', 'mySocialSharing')" href="#">Your Activity</a>
																<a onclick="toggleModule('myActivityModule', 'mySocialSharing')" href="#" class="arrow-down"></a>
																<span>|</span>
																<a onclick="toggleModule('mySocialSharing', 'myActivityModule')" id="socialToggle1" href="#"> Social</a>
																<a onclick="toggleModule('mySocialSharing', 'myActivityModule')" href="#" class="arrow-down"></a>
															</div>
<?php 
	try {
		$a_articlesRead = $facebook->api('/me/news.reads','GET');
    } catch(FacebookApiException $e) {
		$a_articlesRead = null;
//        echo "ERROR TYPE: ".$e->getType()."\n";
//        echo "ERROR MSG: ".$e->getMessage()."\n";
    } 
?>
															<div id="myActivityModule" style="visibility:hidden">
																<div class="facebook-share-links selected myactivity">
																	<a onclick="toggleModule('myActivityModule', 'mySocialSharing')" href="#">Your Activity</a>
																	<a onclick="toggleModule('myActivityModule', 'mySocialSharing')" href="#" class="arrow-up"></a>
																</div>
																<div id="fbRecentActivity" class="fbOgModule facebookActivity">
																	<div id="fbActivityHeader" class="fbActivityHeader">
																		<div class="avatar">
																			<img src="http://graph.facebook.com/<?php echo $user; ?>/picture?type=square">
																		</div>
																		<div class="facebook-share-count">
																			<div class="facebook-name">
																				<?php echo $user_profile['name'];?>
																			</div>
																			<div id="fbActivityCount" class="facebook-shares">
																				Recent Activity (<?php echo count($a_articlesRead['data']); ?>)
																			</div>
																		</div>
<?php if (count($a_articlesRead['data'])>5) : ?>
																		<div id="fbActivityNav" class="fbActivityNav">
																			<span id="activityDisplay">1-5</span><a id="leftNav" class="navBox left inactive"><span id="arrowLeft" class="arrowLeft inactive"></span></a><a id="rightNav" class="navBox right active" onclick="showMoreActivity(6)"><span id="arrowRight" class="arrowRight active"></span></a>
																		</div>
<?php endif ?>
																	</div>
<?php
	function ago($timestamp, $precision = 1) { 
	  $time = time() - $timestamp; 
	  $a = array('year' => 31557600, 'month' => 2629800, 'week' => 604800, 'day' => 86400, 'hour' => 3600, 'minute' => 60, 'second' => 1); 
	  $i = 0; 
		foreach($a as $k => $v) { 
		  $$k = floor($time/$v); 
		  if ($$k) $i++; 
		  $time = $i >= $precision ? 0 : $time - $$k * $v; 
		  $s = $$k > 1 ? 's' : ''; 
		  $$k = $$k ? $$k.' '.$k.$s.' ' : ''; 
		  @$result .= $$k; 
		} 
	  return $result ? $result.'ago' : '1 second ago'; 
	}
	
	foreach ($a_articlesRead['data'] as $lcv => $a_article) {
		echo "<div id=\"".$a_article['id']."\" class=\"fbPastActivity\"";
		if ($lcv >= 5) { echo " style=\"display:none\""; }
		echo ">";
		echo "  <div class=\"spacer\"></div>";
		echo "  <a href=\"".$a_article['data']['article']['url']."\"><b>".$a_article['data']['article']['title']."</b>&nbsp; ".ago(strtotime($a_article['publish_time']))."</a>";
		echo "  <a onclick=\"deleteActivity('".$a_article['id']."')\" class=\"deleteActivity\" href=\"#\"><span>x</span></a>";
		echo "</div>";
	}
?>																	
																</div>
															</div>
															<div id="mySocialSharing" style="visibility:hidden">
																<div class="facebook-share-links selected mysocial">
																	<a onclick="toggleModule('mySocialSharing', 'myActivityModule')" id="socialToggle2" href="#">Social</a>
																	<a onclick="toggleModule('mySocialSharing', 'myActivityModule')" href="#" class="arrow-up"></a>
																</div>
																<div class="fbOgModule shareOptions">
																	<div class="socialToggleLink">
																		<a onclick="toggleSocial()" id="socialToggle3" href="#">Turn Social</a>
																	</div>
																	<div class="remindToggle">
																		<label for="beReminded">
																			<input onchange="toggleReminder()" id="beReminded" name="beReminded" type="checkbox" checked>
																			<div class="remindText">
																				<span>Remind me every time I add to my activity</span>
																			</div>
																		</label>
																	</div>
																	<div class="spacer"></div>
																	<div class="fbLinks">
																		<div class="fbLink"><a id="fbLogoutBtn" href="#">Not <?php echo $user_profile['first_name']; ?>? Log out</a></div>
																		<div class="fbLink"><a onclick="showModule('fbLearnMorePopup')" href="#">Learn more</a></div>
																	</div>																	
																</div>
															</div>
															<div id="myNotificationMsg">
																<div class="close">
																	<a onclick="hideModule('myNotificationMsg')" href="#">X</a>
																</div>
																<div class="message">
																	Added to Your Activity
																</div>
																<div class="spacer">
																</div>
																<div class="shareLinks">
																	<div class="shareLink"><a id="removeFromFB" href="#">Remove from Facebook</a></div>
																	<div class="shareLink"><a id="turnOffFBSharing" href="#">Turn off Facebook sharing</a></div>
																	<div class="shareLink"><a id="turnOffNotify" href="#">Turn off Notifications</a></div>
																	<div class="shareLink"><a id="fbLearnMore" onclick="showModule('fbLearnMorePopup')" href="#">Learn more</a></div>
																</div>
															</div>
														</div>
<?php endif ?>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="links">
										<div class="header">
											Add New Answer
										</div>
										<div class="content">
											<a href="<?php echo getUrl("give-your-answer/".$_SESSION['a_selectedAnsweredTopic']['id']."/"); ?>">Give Yours</a><br>
											<a href="<?php echo getUrl("ask-a-friend/".$_SESSION['a_selectedAnsweredTopic']['id']."/"); ?>">Ask a Friend</a>
										</div>
									</div>
<?php if (count($_SESSION['a_approvedAnswers'])>1) : ?>
									<div class="links">
										<div class="header">
											Read Other Answers
										</div>
										<div class="content">
<?php
											$i_count=0;
											foreach ($_SESSION['a_approvedAnswers'] as $lcv => $a_answer) {
												if (($i_count<3)&($a_answer["id"]!=$_SESSION['a_selectedApprovedAnswer']['id'])) {
													if ($i_count>0){ print "<br><br>\n"; }
													print "											<a href=\"".getUrl("/what-it-feels-like-to-".$_SESSION['a_answeredTopics'][$_SESSION['a_selectedAnsweredTopic']['lcv']]['url']."/".$a_answer["id"]."/")."\">\"".$a_answer["quote"]."\"</a>";
													$i_count++;
												}
											}
?>
										</div>
									</div>
<?php endif ?>
									<div class="links">
										<div class="header">
											Share Answer
										</div>
										<div class="content">
											<a href="http://www.facebook.com/dialog/feed?
												app_id=158967034168646&
												link=<?php echo urlClean(getUrl("", true)); ?>&
												picture=<?php echo urlClean(getUrl("images/Too-Polite-To-Ask_image.png")); ?>&
												name=<?php echo urlClean("What does it feel like to ".$_SESSION['a_answeredTopics'][$_SESSION['a_selectedAnsweredTopic']['lcv']]['topic']."?"); ?>&
												caption=<?php echo urlClean("toopolite.com"); ?>&
												description=<?php echo urlClean($_SESSION['a_approvedAnswers'][$_SESSION['a_selectedApprovedAnswer']['lcv']]['quote']."..."); ?>&
												redirect_uri=<?php echo urlClean(getUrl("", true)); ?>">Facebook</a><br>
											<a href="http://twitter.com/intent/tweet?url=<?php echo urlClean(getUrl("", true)); ?>&text=<?php echo urlClean("Reading about what it feels like to ".$_SESSION['a_answeredTopics'][$_SESSION['a_selectedAnsweredTopic']['lcv']]['topic']); ?>&via=TooPoliteToAsk" title="Click to share this answer on Twitter">Twitter</a><br>
											<a href="<?php echo getUrl("share-answer/".$_SESSION['a_selectedApprovedAnswer']['id']."/"); ?>">Email</a>
										</div>
									</div>
<?php if (count($_SESSION['a_categoryTopics'])>0) : ?>
									<div class="links">
										<div class="header">
											Similar Topics
										</div>
										<div class="content">
<?php
	foreach ($_SESSION['a_categoryTopics'] as $lcv => $a_topic) {
		if ($lcv<3) {
			print "											<a href=\"".getUrl("/what-it-feels-like-to-".$a_topic["url"]."/".$a_topic["answerid"]."/")."\">".$a_topic["topic"]."</a><br>";
		}
	}
?>
										</div>
									</div>
<?php endif ?>
<?php if ($_SESSION['a_approvedAnswer']['audio']!="") : ?>
									<div class="links">
										<div class="header">
											Audio Webcast
										</div>
										<div class="content">
											<object width="215" height="25" classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,40,0">
												<param name="allowscriptaccess" value="always">
												<param name="src" value="http://www.youtube.com/v/<?php echo $_SESSION['a_approvedAnswer']['audio'];?>?fs=1&amp;hl=en_US&amp;rel=0">
												<param name="allowfullscreen" value="false">
												<param name="modestbranding" value="true"></param>
												<embed width="215" height="25" type="application/x-shockwave-flash" src="http://www.youtube.com/v/<?php echo $_SESSION['a_approvedAnswer']['audio'];?>&amp;hl=en_US&amp;rel=0? type=" allowscriptaccess="always" allowfullscreen="false">
											</object>										
										</div>
									</div>
<?php endif ?>
									<div class="links last">
										<div class="content small">
											<img src="<?php echo getUrl("images/eye.png"); ?>"> <?php echo $_SESSION['a_approvedAnswer']['viewcount']; ?>
										</div>
									</div>
								</div>
								<div class="answer"><?php echo htmlEscapeAndLinkUrls($_SESSION['a_approvedAnswer']['answer']); ?></div>
							</div>
						</div>
					</div>
					<div class="more-links">
						<div class="share">
							<div class="google-button">
								<g:plusone count="false"></g:plusone>
							</div>
							<div class="facebook-button">
								<div class="fb-like" data-send="true" data-width="700" data-show-faces="false" data-action="recommend" data-font="verdana"></div>
							</div>
						</div>
					</div>