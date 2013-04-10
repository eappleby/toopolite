					<div class="announcement-wrapper">
						<div class="announcement-header">
							<h1>Thank You</h1>
						</div>
					</div>
					<div class="announcement-wrapper">
						<div class="thank-you-message"><?php echo $_REQUEST["thx"]; ?></div>
						<div class="thank-you-link"><?php
							// share answer
							if (isset($_REQUEST["s-sa"])) {
								echo "<a href=\"".getUrl("share-answer/".$_SESSION['a_selectedAnsweredTopic']['id'])."\">Share answer with someone else?</a>";

							// give answer
							} elseif (isset($_REQUEST["s-ga"])) {
								echo "<a href=\"".getUrl("give-your-answer/".$_SESSION['a_selectedAnsweredTopic']['id'])."\">Answer another question?</a>";

							// ask friend
							} elseif (isset($_REQUEST["s-af"])) {
								echo "<a href=\"".getUrl("ask-a-friend/".$_SESSION['a_selectedAnsweredTopic']['id'])."\">Ask another friend for their answer?</a>";

							// suggest topic
							} elseif (isset($_REQUEST["s-st"])) {
								echo "<a href=\"".getUrl("suggest-a-topic/".$_SESSION['a_selectedAnsweredTopic']['id'])."\">Suggest another topic?</a>";

							// do nothing if buying book or getting newsletter
							} else {
							}							
						?></div>
					</div>