						<div class="form-container">
							<div class="navigation">
								<a class="selected first">Give Answer</a>
								<a href="<?php echo getUrl("ask-a-friend/".$_SESSION['a_selectedApprovedTopic']['id']); ?>">Ask Friend</a>
								<a href="<?php echo getUrl("suggest-a-topic/".$_SESSION['a_selectedApprovedTopic']['id']); ?>">Suggest Topic</a>
							</div>
							<div class="question-to-be-answered">What does it feel like to <?php echo htmlClean ($_SESSION['a_approvedTopics'][$_SESSION['a_selectedApprovedTopic']['lcv']]['topic']); ?>? <?php include ("twitter-login.php"); ?></div>
							<div class="user-input">
								<form action="<?php echo getUrl("received-answer-submission/"); ?>" onsubmit="return validate_form(this);" method="post">
									<div class="input-text-line answer-box">
										<textarea name="answer_text" style="color:#8a8a8a" onfocus="if(this.value=='<?php echo jsClean($s_answer_FM); ?>'){this.value='';this.style.color='#000';}" onblur="if(this.value==''){this.value='<?php echo jsClean($s_answer_FM); ?>';this.style.color='#8a8a8a'}"><?php echo $s_answer_FM; ?></textarea>
									</div>
									<div class="input-text-line">
										<div class="submit-checkboxes">
											<div class="submit-checkbox">
												<label for="book_opt_in">
													<input id="book_opt_in" name="book_opt_in" type="checkbox" checked>
													<span>Consider my answer for the TPTA book (see <a href="/answer-guidelines/">Answer Guidelines</a> for more detail)</span>
												</label>
											</div>
											<div class="submit-checkbox not-first">
												<label for="email_opt_in">
													<input id="email_opt_in" name="email_opt_in" type="checkbox">
													<span>I would like to be notified of upcoming <i>Too Polite To Ask</i> products and news</span>
												</label>
											</div>
											<div class="submit-checkbox not-first">
												<label for="anonymous">
													<input id="anonymous" name="anonymous" type="checkbox">
													<span>I would like to answer this question anonymously</span>
												</label>
											</div>
										</div>
										<div id="answerSubmitButton">
											<input id="submitButton" type="submit" value="Submit" style="position:absolute; right: 50px;">
										</div>
									</div>
								</form>
							</div>
						</div>