					<?php 
						$err_status=-1;
						$err_message = "Sorry, but that ";

						if ($_REQUEST["err"]=="answer-not-found") {
							$err_status = getAnswerStatus($_REQUEST["a"]);
							
							$err_message .= "answer ";
						} else if ($_REQUEST["err"]=="topic-not-found") {
							$err_status = getTopicStatus($_REQUEST["t"]);
							$err_message .= "topic ";							
						} else {
							$err_message .= "page ";							
						}

						if (isDenied($err_status)) {
							$err_message .= "is no longer available";
						} else if (isPending($err_status)) {
							$err_message .= "is pending approval";
						} else if (isNotFound($err_status)) {
							$err_message .= "cannot be found";
						} else if (isApproved($err_status)) {
							$err_message .= "has not yet been answered";
						} else {
							$err_message .= "does not exist";
						}
					?>
					<div class="error-content">
						<div class="title">
							<h1><?php echo $err_message; ?></h1>
						</div>
						<div class="links-group first">
							<h3>Recently Viewed</h3>
							<?php
								$a_recentlyViewedAnswers = getRecentlyViewedAnswers();
								foreach ($a_recentlyViewedAnswers as $lcv => $a_answer) {
									if ($lcv<3) {
										print "<div class=\"link\"><a href=\"".getUrl("/what-it-feels-like-to-".$a_answer["url"]."/".$a_answer["id"]."/")."\">".$a_answer["topic"]."</a></div>";
									}
								}
							?>
						</div>
						<div class="links-group">
							<h3>New Stories</h3>
							<?php
								$a_recentlyApprovedAnswers = getRecentlyApprovedAnswers();
								foreach ($a_recentlyApprovedAnswers as $lcv => $a_answer) {
									if ($lcv<3) {
										print "<div class=\"link\"><a href=\"".getUrl("/what-it-feels-like-to-".$a_answer["url"]."/".$a_answer["id"]."/")."\">".$a_answer["topic"]."</a></div>";
									}
								}
							?>
						</div>
						<div class="links-group">
							<h3>Most Viewed</h3>
							<?php
								$a_mostViewedAnswers = getMostViewedAnswers();
								foreach ($a_mostViewedAnswers as $lcv => $a_answer) {
									if ($lcv<3) {
										print "<div class=\"link\"><a href=\"".getUrl("/what-it-feels-like-to-".$a_answer["url"]."/".$a_answer["id"]."/")."\">".$a_answer["topic"]."</a></div>";
									}
								}
							?>
						</div>
					</div>