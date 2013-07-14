						<div class="form-container">
							<div class="navigation admin">
								<a href="<?php echo getUrl("admin/approve-topic/"); ?>" class="first">Approve Topic</a>
								<a href="<?php echo getUrl("admin/approve-answer/"); ?>">Approve Answer</a>
								<a class="selected">Update Topic</a>
								<a href="<?php echo getUrl("admin/update-answer/"); ?>">Update Answer</a>
							</div>
							<form action="<?php echo getUrl("admin/topic-updated/"); ?>" onsubmit="return validate_form(this);" method="post">
								<div class="new-topic">
									What does it feel like to <input id="new_topic" name="new_topic" type="text" value="<?php echo htmlClean ($_SESSION['a_approvedTopics'][$_SESSION['a_selectedApprovedTopic']['lcv']]['topic']); ?>" onkeydown="return processEnterKey(event)">?
								</div>
								<div class="user-input">
									<div class="medium-text-line">
										<div class="answer-update-date">
											<b>Date Last Updated: </b><?php echo $_SESSION['a_approvedTopics'][$_SESSION['a_selectedApprovedTopic']['lcv']]['dt_updated']; ?>
										</div>
										<div class="answer-author">
											<b>Submitted by: </b><?php echo htmlClean ($_SESSION['a_approvedTopics'][$_SESSION['a_selectedApprovedTopic']['lcv']]['name']); ?>
										</div>
										<div class="answer-create-date">
											<b>Date Submitted: </b><?php echo $_SESSION['a_approvedTopics'][$_SESSION['a_selectedApprovedTopic']['lcv']]['dt_created']; ?>
										</div>
									</div>
									<div class="categories-wrapper push-down">
										<?php 										
										// Cycle through all the categories
										foreach ($_SESSION['a_categories'] as $lcv => $a_category) {
											print "										<div class=\"category";
											if ($lcv == 0) { print " first"; }
											print "\">";
											print "											<input type=\"radio\" name=\"category_id\" id=\"category_id-".$a_category['id']."\" value=\"".$a_category['id']."\"";
											if ($lcv == $_SESSION['a_selectedCategory']['lcv']) { print " checked=\"checked\""; }
											print "/>";
											print "											<label for=\"category_id-".$a_category['id']."\">".$a_category['category']."<span>".$a_category['description']."</span></label>";
											print "										</div>";
										}
										?>
									</div>
									<div class="input-text-line">
										<div class="submit-button right-button">
											<input type="submit" name="submit_button" value="Remove" onClick="validate=false;">
										</div>
										<div class="submit-button">
											<input type="submit" name="submit_button" value="Update" id="accept_or_update">
										</div>
									</div>
								</div>
							</form>
						</div>