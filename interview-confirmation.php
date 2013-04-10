						<div class="form-container">
							<div class="navigation">
								<a class="selected header">Interview Confirmation</a>
							</div>
							<div class="question-to-be-answered centered">
								What does it feel like to <?php echo htmlClean ($_SESSION['a_approvedTopics'][$_SESSION['a_selectedApprovedTopic']['lcv']]['topic']); ?>?
							</div>
							<div class="user-input">
								<form action="<?php echo getUrl("received-interview-confirmation/"); ?>" onsubmit="return validate_form(this);" method="post">
									<div class="input-text-line half">
										<input id="username" name="username" type="text" value="<?php echo $s_username_FM; ?>" style="color:#8a8a8a" onfocus="if(this.value=='<?php echo jsClean($s_username_FM); ?>'){this.value='';this.style.color='#000';}" onblur="if(this.value==''){this.value='<?php echo jsClean($s_username_FM); ?>';this.style.color='#8a8a8a'}">
										<input id="email" name="email" type="text" value="<?php echo $s_email_FM; ?>" style="color:#8a8a8a" onfocus="if(this.value=='<?php echo jsClean($s_email_FM); ?>'){this.value='';this.style.color='#000';}" onblur="if(this.value==''){this.value='<?php echo jsClean($s_email_FM); ?>';this.style.color='#8a8a8a'}">
									</div>
									<div class="input-text-line short-answer-box">
										<textarea name="biography" style="color:#8a8a8a" onfocus="if(this.value=='<?php echo jsClean($s_biography_FM); ?>'){this.value='';this.style.color='#000';}" onblur="if(this.value==''){this.value='<?php echo jsClean($s_biography_FM); ?>';this.style.color='#8a8a8a'}"><?php echo $s_biography_FM; ?></textarea>
									</div>
									<div class="input-text-line">
										<div class="submit-checkboxes">
											<div class="submit-checkbox">
												<label id="legal_text" for="legal_opt_in">
													<input id="legal_opt_in" name="legal_opt_in" type="checkbox">
													<span>I agree to the <a href="/terms-of-service/">Terms of Service</a> and <a href="/privacy-policy">Privacy Policy</a></span>
												</label>
											</div>
											<div class="submit-checkbox not-first">
												<label for="anonymous">
													<input id="anonymous" name="anonymous" type="checkbox">
													<span>I would like to remain anonymous</span>
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