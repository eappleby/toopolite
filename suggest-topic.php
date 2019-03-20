						<div class="form-container">
							<div class="navigation">
								<a href="<?php echo getUrl("give-your-answer/".$_SESSION['a_selectedApprovedTopic']['id']); ?>" class="first">Give Answer</a>
								<a href="<?php echo getUrl("ask-a-friend/".$_SESSION['a_selectedApprovedTopic']['id']); ?>">Ask Friend</a>
								<a class="selected">Suggest Topic</a>
							</div>
							<form action="<?php echo getUrl("received-topic-suggestion/"); ?>" onsubmit="return validate_form(this);" method="post">
								<div class="new-topic">
									What does it feel like to <input id="new_topic" name="new_topic" type="text" maxlength="31"><a title="Please avoid topics with obvious answers and topics that can only be answered by few">?</a>
								</div>
								<div class="user-input">
									<div class="input-text-line">
										<div class="submit-checkboxes">
											<div class="submit-checkbox">
												<label for="notify_author">
													<input id="notify_author" name="notify_author" type="checkbox" checked>
													<span>I would like to be notified if this topic is approved</span>
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
								</div>
							</form>
						</div>