						<div class="form-container">
							<div class="navigation">
								<a href="<?php echo getUrl("give-your-answer/".$_SESSION['a_selectedApprovedTopic']['id']); ?>" class="first">Give Answer</a>
								<a class="selected">Ask Friend</a>
								<a href="<?php echo getUrl("/suggest-a-topic/".$_SESSION['a_selectedApprovedTopic']['id']); ?>">Suggest Topic</a>
							</div>
							<div class="question-to-be-answered">
								What does it feel like to <?php echo htmlClean ($_SESSION['a_approvedTopics'][$_SESSION['a_selectedApprovedTopic']['lcv']]['topic']); ?>?
							</div>
							<div class="user-input">
								<form action="<?php echo getUrl("received-email-friend-request/"); ?>" onsubmit="return validate_form(this);" method="post">
									<div class="input-text-line half">
										<input id="friend_username" name="friend_username" type="text" value="<?php echo $s_friend_username_FM; ?>" style="color:#8a8a8a" onfocus="if(this.value=='<?php echo jsClean($s_friend_username_FM); ?>'){this.value='';this.style.color='#000';}" onblur="if(this.value==''){this.value='<?php echo jsClean($s_friend_username_FM); ?>';this.style.color='#8a8a8a'}">
										<input id="friend_email" name="friend_email" type="text" value="<?php echo $s_friend_email_FM; ?>" style="color:#8a8a8a" onfocus="if(this.value=='<?php echo jsClean($s_friend_email_FM); ?>'){this.value='';this.style.color='#000';}" onblur="if(this.value==''){this.value='<?php echo jsClean($s_friend_email_FM); ?>';this.style.color='#8a8a8a'}">
									</div>
									<div class="input-text-line short-answer-box">
										<textarea name="message" style="color:#8a8a8a" onfocus="if(this.value=='<?php echo jsClean($s_message_FM); ?>'){this.value='';this.style.color='#000';}" onblur="if(this.value==''){this.value='<?php echo jsClean($s_message_FM); ?>';this.style.color='#8a8a8a'}"><?php echo $s_message_FM; ?></textarea>
									</div>
									<div class="input-text-line">
										<div class="submit-checkboxes">
											<div class="submit-checkbox">
												<label for="cc_sender">
													<input id="cc_sender" name="cc_sender" type="checkbox">
													<span>I would like to be copied on this email</span>
												</label>
											</div>
											<div class="submit-checkbox not-first">
												<label for="email_opt_in">
													<input id="email_opt_in" name="email_opt_in" type="checkbox">
													<span>I would like to be notified of upcoming <i>Too Polite To Ask</i> products and news</span>
												</label>
											</div>
										</div>
										<div class="submit-button two-checkboxes">
											<input type="submit" value="Submit">
										</div>
									</div>
								</form>
							</div>
						</div>