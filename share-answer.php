					<div class="announcement-wrapper">
						<div class="announcement-header">
							<h1>Share Answer To Topic...</h1>
						</div>
					</div>
					<div class="announcement-wrapper">
						<div class="announcement-info less-bottom-margin">
							<a href="<?php echo getUrl("what-it-feels-like-to-".stringToUrl($_SESSION['a_answeredTopics'][$_SESSION['a_selectedAnsweredTopic']['lcv']]['topic'])."/".$_SESSION['a_selectedApprovedAnswer']['id']); ?>">What does it feel like to <?php echo $_SESSION['a_answeredTopics'][$_SESSION['a_selectedAnsweredTopic']['lcv']]['topic']?>?</a>
						</div>
					</div>
					<div class="announcement-wrapper">			
						<form action="<?php echo getUrl("received-share-answer-request/"); ?>" onsubmit="return validate_form(this);" method="post">
							<div class="input-line">
								<input id="your_username" name="your_username" type="text" value="<?php echo $s_your_username_FM; ?>" style="color:#8a8a8a" onfocus="if(this.value=='<?php echo jsClean($s_your_username_FM); ?>'){this.value='';this.style.color='#000';}" onblur="if(this.value==''){this.value='<?php echo jsClean($s_your_username_FM); ?>';this.style.color='#8a8a8a'}">
								<input id="your_email" name="your_email" type="text" value="<?php echo $s_your_email_FM; ?>" style="color:#8a8a8a" onfocus="if(this.value=='<?php echo jsClean($s_your_email_FM); ?>'){this.value='';this.style.color='#000';}" onblur="if(this.value==''){this.value='<?php echo jsClean($s_your_email_FM); ?>';this.style.color='#8a8a8a'}">
							</div>
							<div class="input-line">
								<input id="friend_username" name="friend_username" type="text" value="<?php echo $s_friend_username_FM; ?>" style="color:#8a8a8a" onfocus="if(this.value=='<?php echo jsClean($s_friend_username_FM); ?>'){this.value='';this.style.color='#000';}" onblur="if(this.value==''){this.value='<?php echo jsClean($s_friend_username_FM); ?>';this.style.color='#8a8a8a'}">
								<input id="friend_email" name="friend_email" type="text" value="<?php echo $s_friend_email_FM; ?>" style="color:#8a8a8a" onfocus="if(this.value=='<?php echo jsClean($s_friend_email_FM); ?>'){this.value='';this.style.color='#000';}" onblur="if(this.value==''){this.value='<?php echo jsClean($s_friend_email_FM); ?>';this.style.color='#8a8a8a'}">
							</div>
							<div class="input-line textarea">
								<textarea name="message" style="color:#8a8a8a" onfocus="if(this.value=='<?php echo jsClean($s_message_FM); ?>'){this.value='';this.style.color='#000';}" onblur="if(this.value==''){this.value='<?php echo jsClean($s_message_FM); ?>';this.style.color='#8a8a8a'}"><?php echo $s_message_FM; ?></textarea>
							</div>
							<div class="input-line">
								<div class="checkboxes">
									<div class="submit-checkbox">
										<label for="cc_sender">
											<input id="cc_sender" name="cc_sender" type="checkbox">
											<span>I would like to be copied on this email</span>
										</label>
									</div>
									<div class="submit-checkbox">
										<label for="email_opt_in">
											<input id="email_opt_in" name="email_opt_in" type="checkbox">
											<span>I would like to be notified of <i>Too Polite To Ask</i> products and news</span>
										</label>
									</div>
								</div>
								<div class="submit-button">
									<input type="submit" value="Submit">
								</div>
							</div>							
						</form>
					</div>
					<div class="footer shorter centered">
						<a href="<?php echo getUrl("about-tpta/"); ?>" class="first">about tpta</a>
						<a href="<?php echo getUrl("terms-of-service/"); ?>">terms & conditions</a>
						<a href="<?php echo getUrl("copyright-compliance-policy/"); ?>">copyright</a>
						<a href="<?php echo getUrl("privacy-policy/"); ?>">privacy policy</a>
						<a href="<?php echo getUrl("content-submission-agreement/"); ?>">user content submission agreement</a>
					</div>