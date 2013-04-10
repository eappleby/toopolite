					<div class="topics-and-form">
						<div class="topics-panel">
							<div class="header">
								<?php echo $s_topicHeader_TL; ?>
							</div>
							<div id="topics-list">
								<div class="submission-topic-list">
									<?php
									foreach ($a_topics_TL as $lcv => $a_topic) {
										if (($lcv < $i_selectedTopicLcv_TL - ($i_selectedTopicLcv_TL%$i_maxTopicsDisplayed) ) || ( $lcv >= $i_selectedTopicLcv_TL - ($i_selectedTopicLcv_TL%$i_maxTopicsDisplayed) + $i_maxTopicsDisplayed )) {
										} elseif ($lcv == $i_selectedTopicLcv_TL) {
											print "									<a class=\"selected-topic\">".htmlClean ($a_topic["topic"])."?".((isset($a_topics_TL[$lcv]['answered'])&&($a_topics_TL[$lcv]['answered']==FALSE)) ? " *" : "")."</a>";
										} else {
											print "									<a href=\"$s_urlVar_TL".$a_topics_TL[$lcv]['id']."/\">".htmlClean ($a_topic["topic"])."?".((isset($a_topics_TL[$lcv]['answered'])&&($a_topics_TL[$lcv]['answered']==FALSE)) ? " *" : "")."</a>";
										}
									}
									?>
								</div>
							</div>
							<div id="other-options">
								<div class="compact">
								<?php
								// No need to display the navigation if there is just one column of topics
								if (ceil(count($a_topics_TL)/$i_maxTopicsDisplayed)>1) {
									for ($lcv=0; $lcv<ceil(count($a_topics_TL)/$i_maxTopicsDisplayed); $lcv++) {
										$tmp_selected = floor($i_selectedTopicLcv_TL/$i_maxTopicsDisplayed);
										if (($lcv < $tmp_selected - ($tmp_selected%5)) || ($lcv >= $tmp_selected + 5 - ($tmp_selected%5))) {
											if ($lcv == $tmp_selected  - ($tmp_selected %5) - 5) {
												print "								<a href=\"$s_urlVar_TL".$a_topics_TL[($lcv*$i_maxTopicsDisplayed)]['id']."/\" class=\"first\">&#171;</a>";
											}
											if ($lcv == $tmp_selected  - ($tmp_selected %5) + 5) {
												print "								<a href=\"$s_urlVar_TL".$a_topics_TL[($lcv*$i_maxTopicsDisplayed)]['id']."/\">&#187;</a>";
											}
										} elseif (($lcv == 0) && ($lcv == $tmp_selected)) {
											print "								<a class=\"selected first\">".($lcv+1)."</a>";
										} elseif ($lcv == 0) {
											print "								<a href=\"$s_urlVar_TL".$a_topics_TL[($lcv*$i_maxTopicsDisplayed)]['id']."/\" class=\"first\">".($lcv+1)."</a>";
										} elseif ($lcv == $tmp_selected) {
											print "								<a class=\"selected\">".($lcv+1)."</a>";
										} else {
											print "								<a href=\"$s_urlVar_TL".$a_topics_TL[($lcv*$i_maxTopicsDisplayed)]['id']."/\" >".($lcv+1)."</a>";
										}
									}
								}
								?>
								</div>
							</div>
						</div>
						<?php
						if (isset($_REQUEST["ga"])) { include ("give-answer.php"); } 
						else if (isset($_REQUEST["af"])) { include ("ask-friend.php"); } 
						else if (isset($_REQUEST["st"])) { include ("suggest-topic.php"); } 
						else if (isset($_REQUEST["itv"])) { include ("interview-confirmation.php"); } 
						else if (isset($_REQUEST["adm-at"])) { include ("approve-topics.php"); } 
						else if (isset($_REQUEST["adm-aa"])) { include ("approve-answers.php"); } 
						else if (isset($_REQUEST["adm-ut"])) { include ("update-topics.php"); } 
						else if (isset($_REQUEST["adm-ua"])) { include ("update-answers.php"); } 
						?>
						
					</div>
					<?php
					if ((isset($_REQUEST["ga"]))||(isset($_REQUEST["af"]))||(isset($_REQUEST["st"]))||(isset($_REQUEST["itv"]))) {
					?>
					<div class="footer">
						<div class="asterisk">
							* be the first to answer this question
						</div>
						<div class="legal">
							<a href="<?php echo getUrl("content-submission-agreement/"); ?>" class="first">user content submission agreement</a>
							<a href="<?php echo getUrl("terms-of-service/"); ?>">terms & conditions</a>
							<a href="<?php echo getUrl("copyright-compliance-policy/"); ?>">copyright</a>
							<a href="<?php echo getUrl("privacy-policy/"); ?>">privacy policy</a>
						</div>
					</div>
					<?php
					}
					?>