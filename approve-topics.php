						<div class="form-container">
							<div class="navigation admin">
								<a class="selected first">Approve Topic</a>
								<a href="<?php echo getUrl("admin/approve-answer/"); ?>">Approve Answer</a>
								<a href="<?php echo getUrl("admin/update-topic/"); ?>">Update Topic</a>
								<a href="<?php echo getUrl("admin/update-answer/"); ?>">Update Answer</a>
							</div>
							<form action="<?php echo getUrl("admin/topic-approved-denied/"); ?>" onsubmit="return validate_form(this);" method="post">
								<div class="new-topic">
									What does it feel like to <input id="new_topic" name="new_topic" type="text" value="<?php echo htmlClean ($_SESSION['a_unapprovedTopics'][$_SESSION['a_selectedUnapprovedTopic']['lcv']]['topic']); ?>" onkeydown="return processEnterKey(event)">?
								</div>
								<div class="user-input">
									<div class="short-text-line">
										<div class="answer-author">
											<b>Submitted by:</b> &nbsp;<?php echo htmlClean ($_SESSION['a_unapprovedTopics'][$_SESSION['a_selectedUnapprovedTopic']['lcv']]['name']); ?>
										</div>
										<div class="answer-create-date">
											<b>Date Submitted:</b> &nbsp;<?php echo date('Y-m-d', strtotime($_SESSION['a_unapprovedTopics'][$_SESSION['a_selectedUnapprovedTopic']['lcv']]['dt_created'])); ?>
										</div>
									</div>
									<div class="categories-wrapper">
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
											<input type="submit" name="submit_button" value="Deny" onClick="validate=false;">
										</div>
										<div class="submit-button">
											<input type="submit" name="submit_button" value="Approve" id="accept_or_update">
										</div>
									</div>
								</div>
							</form>
						</div>