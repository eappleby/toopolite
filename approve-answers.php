						<div class="form-container">
							<div class="navigation admin">
								<a href="<?php echo getUrl("admin/approve-topic/"); ?>" class="first">Approve Topic</a>
								<a class="selected">Approve Answer</a>
								<a href="<?php echo getUrl("admin/update-topic/"); ?>">Update Topic</a>
								<a href="<?php echo getUrl("admin/update-answer/"); ?>">Update Answer</a>
							</div>
							<div class="question-to-be-answered">
								What does it feel like to <?php echo htmlClean ($_SESSION['a_topicsWithUnapprovedAnswers'][$_SESSION['a_selectedTopicWithUnapprovedAnswers']['lcv']]['topic']); ?>?
							</div>
							<div class="user-input">
								<form action="<?php echo getUrl("admin/answer-approved-denied/"); ?>" onsubmit="return validate_form(this);" method="post">
									<div class="short-text-line">
										<div class="answer-author">
											<b>Submitted by:</b> &nbsp;<?php echo htmlClean ($_SESSION['a_unapprovedAnswers'][$_SESSION['a_selectedUnapprovedAnswer']['lcv']]['name']); if ($_SESSION['a_unapprovedAnswers'][$_SESSION['a_selectedUnapprovedAnswer']['lcv']]['anonymous']) { echo " (Anonymous)"; }?>
										</div>
										<div class="answer-create-date">
											<b>Date Submitted:</b> &nbsp;<?php echo $_SESSION['a_unapprovedAnswers'][$_SESSION['a_selectedUnapprovedAnswer']['lcv']]['dt_created']; ?>
										</div>
									</div>
									<div class="input-text-line long-answer-box">
										<textarea id="answer_text" name="answer_text"><?php echo htmlClean ($_SESSION['a_unapprovedAnswers'][$_SESSION['a_selectedUnapprovedAnswer']['lcv']]['answer']); ?></textarea>
									</div>
									<div class="mood-colors">
										<?php 										
										// Cycle through all the moods
										foreach ($_SESSION['a_moods'] as $lcv => $a_mood) {
											if ($lcv == $_SESSION['a_selectedMood']['lcv']) {
												print "								<input type=\"radio\" name=\"mood_id\" id=\"".$a_mood['id']."\" value=\"".$a_mood['id']."\" checked=\"checked\"  />";
												print "								<label for=\"".$a_mood['id']."\" style=\"background:#".$a_mood['rgb']."; color:#".$a_mood['font_color'].";\">".$a_mood['mood']."</label>";
											} else {
												print "								<input type=\"radio\" name=\"mood_id\" id=\"".$a_mood['id']."\" value=\"".$a_mood['id']."\" />";
												print "								<label for=\"".$a_mood['id']."\" style=\"background:#".$a_mood['rgb']."; color:#".$a_mood['font_color'].";\">".$a_mood['mood']."</label>";
											}
										}
										?>
									</div>
									<div class="input-text-line thirds">
										<input id="citation_name" name="citation_name" type="text"  value="<?php echo $s_citationName_FM; ?>" style="color:#8a8a8a" onfocus="if(this.value=='<?php echo jsClean($s_citationName_FM); ?>'){this.value='';this.style.color='#000';}" onblur="if(this.value==''){this.value='<?php echo jsClean($s_citationName_FM); ?>';this.style.color='#8a8a8a'}" onkeydown="return processEnterKey(event)">
										<input id="citation_url" name="citation_url" type="text"  value="<?php echo $s_citationUrl_FM; ?>" style="color:#8a8a8a" onfocus="if(this.value=='<?php echo jsClean($s_citationUrl_FM); ?>'){this.value='';this.style.color='#000';}" onblur="if(this.value==''){this.value='<?php echo jsClean($s_citationUrl_FM); ?>';this.style.color='#8a8a8a'}" onkeydown="return processEnterKey(event)">
										<input id="audio" name="audio" type="text"  value="<?php echo $s_audio_FM; ?>" style="color:#8a8a8a; float:right;" onfocus="if(this.value=='<?php echo jsClean($s_audio_FM); ?>'){this.value='';this.style.color='#000';}" onblur="if(this.value==''){this.value='<?php echo jsClean($s_audio_FM); ?>';this.style.color='#8a8a8a'}" onkeydown="return processEnterKey(event)">
									</div>
									<div class="input-text-line">
										<div class="three-quarters">
											<input id="quote" name="quote" type="text" maxlength="125" value="<?php echo $s_quote_FM; ?>" style="color:#8a8a8a" onfocus="if(this.value=='<?php echo jsClean($s_quote_FM); ?>'){this.value='';this.style.color='#000';}" onblur="if(this.value==''){this.value='<?php echo jsClean($s_quote_FM); ?>';this.style.color='#8a8a8a'}" onkeydown="return processEnterKey(event)">
										</div>
										<div class="one-quarter">
											<input id="rating" name="rating" type="text" value="<?php echo $s_rating_FM; ?>" style="color:#8a8a8a" onfocus="if(this.value=='<?php echo jsClean($s_rating_FM); ?>'){this.value='';this.style.color='#000';}" onblur="if(this.value==''){this.value='<?php echo jsClean($s_rating_FM); ?>';this.style.color='#8a8a8a'}" onkeydown="return processEnterKey(event)">
										</div>
									</div>
									<div class="input-text-line">
										<div id="other-options">
											<div class="compact select-answer">
												<?php
												if (count($_SESSION['a_unapprovedAnswers'])>1) {
													for ($lcv=0; $lcv<count($_SESSION['a_unapprovedAnswers']); $lcv++) {
														if (($lcv < $_SESSION['a_selectedUnapprovedAnswer']['lcv'] - ($_SESSION['a_selectedUnapprovedAnswer']['lcv']%5)) || ($lcv >= $_SESSION['a_selectedUnapprovedAnswer']['lcv'] + 5 - ($_SESSION['a_selectedUnapprovedAnswer']['lcv']%5))) {
															if ($lcv == $_SESSION['a_selectedUnapprovedAnswer']['lcv']  - ($_SESSION['a_selectedUnapprovedAnswer']['lcv'] %5) - 5) {
																print "								<a href=\"".getUrl("admin/index.php?adm-aa=1&t=".$_SESSION['a_selectedTopicWithUnapprovedAnswers']['id']."&a=".$_SESSION['a_unapprovedAnswers'][$lcv]['id'])."\" class=\"first\">&#171;</a>";
															}
															if ($lcv == $_SESSION['a_selectedUnapprovedAnswer']['lcv']  - ($_SESSION['a_selectedUnapprovedAnswer']['lcv'] %5) + 5) {
																print "								<a href=\"".getUrl("admin/index.php?adm-aa=1&t=".$_SESSION['a_selectedTopicWithUnapprovedAnswers']['id']."&a=".$_SESSION['a_unapprovedAnswers'][$lcv]['id'])."\">&#187;</a>";
															}
														} elseif (($lcv == 0) && ($lcv == $_SESSION['a_selectedUnapprovedAnswer']['lcv'])) {
															print "								<a class=\"selected first\">".($lcv+1)."</a>";
														} elseif ($lcv == 0) {
															print "								<a href=\"".getUrl("admin/index.php?adm-aa=1&t=".$_SESSION['a_selectedTopicWithUnapprovedAnswers']['id']."&a=".$_SESSION['a_unapprovedAnswers'][$lcv]['id'])."\" class=\"first\">".($lcv+1)."</a>";
														} elseif ($lcv == $_SESSION['a_selectedUnapprovedAnswer']['lcv']) {
															print "								<a class=\"selected\">".($lcv+1)."</a>";
														} else {
															print "								<a href=\"".getUrl("admin/index.php?adm-aa=1&t=".$_SESSION['a_selectedTopicWithUnapprovedAnswers']['id']."&a=".$_SESSION['a_unapprovedAnswers'][$lcv]['id'])."\" >".($lcv+1)."</a>";
														}
													}
												}
												?>
											</div>
										</div>
										<div class="submit-button">
											<div class="input-text-line">
												<div class="submit-button right-button">
													<input type="submit" name="submit_button" value="Deny" onClick="validate=false;">
												</div>
												<div class="submit-button">
													<input type="submit" name="submit_button" value="Approve" id="accept_or_update">
												</div>
											</div>
										</div>
									</div>
								</form>
							</div>
						</div>