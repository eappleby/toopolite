				<div id="whitewash"></div>
				<div id="overlay">
					<div class="close-button">
						<a href="<?php echo getUrl(); ?>">X</a>
					</div>
					<?php
					if (isset($_REQUEST["ra"])) { include ("read-answer.php"); }
					else if (isset($_REQUEST["sa"])) { include ("share-answer.php"); }
					else if (isset($_REQUEST["bb"])) { include ("buy-book.php"); }
					else if (isset($_REQUEST["gn"])) { include ("get-newsletter.php"); }
					else if (isset($_REQUEST["thx"])) { include ("thank-you.php"); }
					else if (isset($_REQUEST["err"])) { include ("error.php"); }
					else if ((isset($_REQUEST["abt"]))||(isset($_REQUEST["csa"]))||(isset($_REQUEST["tos"]))||(isset($_REQUEST["ccp"]))||(isset($_REQUEST["ppo"]))||(isset($_REQUEST["agu"]))) { include ("legal.php"); }
					else { 
						include ("update-using-topic-list.php"); 
						print "		<div id=\"fbForceLogin\"";
						if ($user){
							print " style=\"visibility:hidden;\" ";
						}
						print "><div id=\"whitewash52\"></div><div id=\"fbLoginPopup\"><h3>Must be logged in to view this page</h3><div class=\"fb-login-button\" data-show-faces=\"false\" data-width=\"200\" data-max-rows=\"1\">Log in with Facebook</div></div></div>";
					}
					?>
				</div>