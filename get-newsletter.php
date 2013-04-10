					<div class="announcement-wrapper">
						<div class="announcement-header">
							<h1>Sign Up For News and Updates</h1>
						</div>
					</div>
					<div class="announcement-wrapper">
						<div class="announcement-info">
							If you would like to be notified of the e-book release and other related news <br>just give us your info, and we will keep you posted
						</div>
					</div>
					<div class="announcement-wrapper">			
						<form action="<?php echo getUrl("received-newsletter-request/"); ?>" onsubmit="return validate_form(this);" method="post">
							<div class="input-line">
								<input id="username" name="username" type="text" value="<?php echo $s_username_FM; ?>" style="color:#8a8a8a" onfocus="if(this.value=='<?php echo jsClean($s_username_FM); ?>'){this.value='';this.style.color='#000';}" onblur="if(this.value==''){this.value='<?php echo jsClean($s_username_FM); ?>';this.style.color='#8a8a8a'}">
								<input id="email" name="email" type="text" value="<?php echo $s_email_FM; ?>" style="color:#8a8a8a" onfocus="if(this.value=='<?php echo jsClean($s_email_FM); ?>'){this.value='';this.style.color='#000';}" onblur="if(this.value==''){this.value='<?php echo jsClean($s_email_FM); ?>';this.style.color='#8a8a8a'}">
								<input type="submit" value="Submit">
							</div>
						</form>
					</div>
					<div class="footer bottom">
						<a href="<?php echo getUrl("about-tpta/"); ?>" class="first">about tpta</a>
						<a href="<?php echo getUrl("terms-of-service/"); ?>">terms & conditions</a>
						<a href="<?php echo getUrl("copyright-compliance-policy/"); ?>">copyright</a>
						<a href="<?php echo getUrl("privacy-policy/"); ?>">privacy policy</a>
						<a href="<?php echo getUrl("content-submission-agreement/"); ?>">user content submission agreement</a>
					</div>
