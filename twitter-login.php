<?php if ($_SESSION['a_approvedTopics'][$_SESSION['a_selectedApprovedTopic']['lcv']]['topic'] == "have 1M+ Twitter followers"): ?>
		<span id="twitterCheckmark" style="white-space: nowrap; font-weight: normal; font-size: 11px;"></span>
		<script src="http://platform.twitter.com/anywhere.js?id=zbiUzUcp30gJRzaSLvKIxQ&v=1" type="text/javascript"></script>
		<script type="text/javascript">			
			logChange = function (T) {
				if (T.isConnected()) {
					if (T.currentUser.data("followers_count") <1000000) {
						document.getElementById("twitterCheckmark").innerHTML = "<img src=\"<?php echo getUrl("images/twitter-rejected.png"); ?>\"><1M followers ";
						document.getElementById('answerSubmitButton').innerHTML="<div style=\"width:70px;height:13px;background:#cbcbcb\;padding: 7px 5px;margin-top:13px; font-weight:bold;font-size:11px;cursor:pointer;cursor:hand;\"><a style=\"border:0;color:#2298ca;\" id=\"twitterLogout\"><img style=\"float:left;margin-right:6px;\"src=\"<?php echo getUrl("images/twitter-bird.png"); ?>\">logout</a></div>";
					} else {
						document.getElementById("twitterCheckmark").innerHTML = "<img src=\"<?php echo getUrl("images/twitter-verified.png"); ?>\">verified (<a style=\"border:0;color:#2298ca;\" id=\"twitterLogout\">logout</a>)";
						document.getElementById('answerSubmitButton').innerHTML="<input id=\"submitButton\" type=\"submit\" value=\"Submit\">";
					}
					document.getElementById("twitterLogout").onclick = function() {
						twttr.anywhere.signOut();
					}
				} else {
					document.getElementById("twitterCheckmark").innerHTML = "<img src=\"<?php echo getUrl("images/twitter-confirm.png"); ?>\">";
					document.getElementById('answerSubmitButton').innerHTML="<div style=\"width:70px;height:13px;background:#c6e0ec\;padding: 7px 5px;margin-top:13px; font-weight:bold;font-size:11px;cursor:pointer;cursor:hand;\"><a style=\"border:0;color:#2298ca;\" id=\"twitterLogin\"><img style=\"float:left;margin-right:2px;\"src=\"<?php echo getUrl("images/twitter-bird.png"); ?>\">confirm</a></div>";
					document.getElementById("twitterLogin").onclick = function() {
						T.signIn();
					}
				};					
			};
		
			twttr.anywhere(function (T) {
				document.getElementById('twitterCheckmark').parentNode.style.marginTop = "6px";
				
				logChange (T);

				// triggered when auth completed successfully
				T.bind("authComplete", function (e, user) {
					logChange (T);
				});

				// triggered when user logs out
				T.bind("signOut", function (e) {
					logChange (T);
				});
			});
		</script>
<?php endif; ?>