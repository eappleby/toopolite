<script type="text/javascript" charset="utf-8">
	// Google Analytis Tracking
	var _gaq = _gaq || [];
	_gaq.push(['_setAccount', 'UA-23749702-1']);
	_gaq.push(['_trackPageview']);

	(function() {
	var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
	ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
	var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
	})();
</script>
<!--
<script type="text/javascript" charset="utf-8">
	// Get Satisfaction feedback plugin
	var is_ssl = ("https:" == document.location.protocol);
	var asset_host = is_ssl ? "https://s3.amazonaws.com/getsatisfaction.com/" : "http://s3.amazonaws.com/getsatisfaction.com/";
	document.write(unescape("%3Cscript src='" + asset_host + "javascripts/feedback-v2.js' type='text/javascript'%3E%3C/script%3E"));

	var feedback_widget_options = {};

	feedback_widget_options.display = "overlay";  
	feedback_widget_options.company = "too_polite_to_ask";
	feedback_widget_options.placement = "left";
	feedback_widget_options.color = "#009418";
	feedback_widget_options.style = "idea";

	var feedback_widget = new GSFN.feedback_widget(feedback_widget_options);
</script> -->
<script>
	// Facebook asynchronous initialization
	window.fbAsyncInit = function() {
		FB.init({ 
			appId      : '158967034168646', // App ID
			cookie     : true,
			xfbml      : true,
			version    : 'v2.10'});
//		FB.UIServer.setActiveNode = function(a,b){FB.UIServer._active[a.id]=b;}

		// Facebook login updates in nav bar
		var fbConnect = document.getElementById('fbConnect');
		var answerLink = document.getElementById('answerLink');
		var askLink = document.getElementById('askLink');

		function updateButton(response) {
			if (response.authResponse) {
				//user is already logged in and connected
				FB.api('/me', function(user) {
					fbConnect.innerHTML = '<a class="navbar-other">Log out</a>';
					answerLink.innerHTML = '<a class="navbar-other" href="<?php echo getUrl("give-your-answer/".$_SESSION['a_selectedAnsweredTopic']['id']."/"); ?>">Answer</a>';
					askLink.innerHTML = '<a class="navbar-other" href="<?php echo getUrl("suggest-a-topic/".$_SESSION['a_selectedAnsweredTopic']['id']."/"); ?>">Ask</a>';
				});

<?php if ((isset($_REQUEST["ga"]))||(isset($_REQUEST["af"]))||(isset($_REQUEST["st"]))) : ?>
				// Now that the user is logged in, remove popup window that tells users to login if they want to give answer, ask friend or submit new topic
				document.getElementById('fbForceLogin').style.visibility = "hidden";
<?php endif ?>
				fbConnect.onclick = function() {
					FB.logout(function(response) {});
				};
				
			} else {
				//user is not connected to your app or logged out
				fbConnect.innerHTML = '<a class="navbar-login">Log in</a>';
				answerLink.innerHTML = 'Answer';
				askLink.innerHTML = 'Ask';

<?php if ((isset($_REQUEST["ga"]))||(isset($_REQUEST["af"]))||(isset($_REQUEST["st"]))) : ?>
				// Popup window that tells users to login if they want to give answer, ask friend or submit new topic
				document.getElementById('fbForceLogin').style.visibility = "visible";
<?php endif ?>
				fbConnect.onclick = function() {
					FB.login(function(response) {}, {scope:'email'});
				};
			}
		}

		// run once with current status and whenever the status changes
//		FB.getLoginStatus(updateButton);
//		FB.Event.subscribe('auth.statusChange', updateButton);
		
<?php if (isset($_REQUEST["ra"])) : ?>
		// Reload to avoid having to put entire Facebook module in Javascript
		FB.Event.subscribe('auth.login', function(response) {
			window.location.reload();
		});
		FB.Event.subscribe('auth.logout', function(response) {
			window.location.reload();
		});
		
		// This is the Facebook logout button from within Facebook sharing module
		document.getElementById("fbLogoutBtn").onclick = function () {
				FB.logout(function(response) {});
			};
			
		// Retrieves cookies to remember Facebook sharing settings
		setModuleVariables();
<?php endif ?>
	};
	
	// Facebook async code
  (function(d, s, id){
     var js, fjs = d.getElementsByTagName(s)[0];
     if (d.getElementById(id)) {return;}
     js = d.createElement(s); js.id = id;
     js.src = "https://connect.facebook.net/en_US/sdk.js";
     fjs.parentNode.insertBefore(js, fjs);
   }(document, 'script', 'facebook-jssdk'));

<?php if (isset($_REQUEST["ra"])) : ?>
	document.getElementById("color-bar").style.height = document.getElementById("reading-pane").clientHeight+"px";
	
	function setCookie(c_name,value,exdays) {
		var exdate=new Date();
		exdate.setDate(exdate.getDate() + exdays);
		
		var c_value=escape(value) + ((exdays==null) ? "" : "; expires="+exdate.toUTCString());
		document.cookie=c_name + "=" + c_value + ";domain=.toopolite.herokuapp.com;path=/";;
	}

	function getCookie(c_name) {
		var i,x,y,ARRcookies=document.cookie.split(";");
		for (i=0;i<ARRcookies.length;i++){
			x=ARRcookies[i].substr(0,ARRcookies[i].indexOf("="));
			y=ARRcookies[i].substr(ARRcookies[i].indexOf("=")+1);
			x=x.replace(/^\s+|\s+$/g,"");
			if (x==c_name) {
				return unescape(y);
			}
		}
		return;
	}

	// Retrieves cookies to remember Facebook sharing settings
	function setModuleVariables() {
		var reminder=getCookie("reminderToggle");
		if ((reminder==null) || (reminder==1)){
			document.getElementById("beReminded").checked = true;
		} else {
			document.getElementById("beReminded").checked = false;
		}

		var social=getCookie("socialToggle");
		if ((social==null) || (social==0)){
			document.getElementById("socialToggle1").innerHTML = "Social <b>OFF</b>";
			document.getElementById("socialToggle2").innerHTML = "Social <b>OFF</b>";
			document.getElementById("socialToggle3").innerHTML = "Turn Social ON";
		} else {
			document.getElementById("socialToggle1").innerHTML = "Social <b>ON</b>";
			document.getElementById("socialToggle2").innerHTML = "Social <b>ON</b>";
			document.getElementById("socialToggle3").innerHTML = "Turn Social OFF";
			shareArticle();
		}
	}

	function updateActivityCount () {
		document.getElementById("fbActivityCount").innerHTML = "Recent Activity (" + (document.getElementById("fbRecentActivity").childNodes.length - 4) + ")";
	}
	
	function deleteActivity (activityId) {
		FB.api(activityId, 'delete', function(response) {
			if (!response) {
//				alert('Received no response for /' + activityId + ', delete');
			} else if (response.error) {
//				alert('Error occurred: '+ JSON.stringify(response.error, null, " "));
			} else {
				var activityDiv = document.getElementById(activityId);
				activityDiv.parentNode.removeChild(activityDiv);
				updateActivityCount ();
//				alert("activity deleted: "+activityId);
			}	
		});
	}

	function notifyUser (id) {
		if (document.getElementById("beReminded").checked) {
			document.getElementById("removeFromFB").onclick=function(){ deleteActivity(id); hideModule("myNotificationMsg");};
			document.getElementById("turnOffFBSharing").onclick=function(){ toggleSocial(); hideModule("myNotificationMsg");};
			document.getElementById("turnOffNotify").onclick=function(){ toggleReminder(); hideModule("myNotificationMsg");};
			document.getElementById("mySocialSharing").style.visibility = "hidden";
			document.getElementById("myActivityModule").style.visibility = "hidden";
			document.getElementById("myNotificationMsg").style.visibility = "visible";
		} else {
//			alert ("do not notify user");
		}
	}

	function addArticleToList (id) {
		var article = document.createElement ("newArticle");
		article.id = id;
		article.className="fbPastActivity";
		var spacer = document.createElement ("newSpacer");
		spacer.className = "spacer";
		var links = document.createElement ("newLinks");
		links.innerHTML = "  <a href=\"" + document.location + "\"><b>What does it feel like to <?php echo htmlClean ($_SESSION['a_answeredTopics'][$_SESSION['a_selectedAnsweredTopic']['lcv']]['topic']);?>?</b> &nbsp;1 second ago</a>";
		links.innerHTML += "  <a onclick=\"deleteActivity('" + id + "')\" class=\"deleteActivity\" href=\"#\"><span>x</span></a>";
		article.appendChild(spacer);
		article.appendChild(links);
		
		var header = document.getElementById("fbActivityHeader");
		if (header.nextSibling) {
			header.parentNode.insertBefore(article, header.nextSibling);
		} else {
			header.parentNode.appendChild(article);
		}
		
		updateActivityCount ();
	}
	
	function shareArticle() {
		setTimeout(
			function () {
				FB.api('/me/news.reads&article='+document.location, 'post', function(response) {
					if (!response) {
//						alert('Received no response for /me/news.reads&article='+document.location);
					} else if (response.error) {
//						alert('Error occurred: '+ JSON.stringify(response.error, null, " "));
					} else {
						notifyUser (response.id);
						addArticleToList (response.id);
//						alert('Post was successful! Action ID: ' + response.id);
					}
				});
			}, 10000);
	}
	
	function toggleReminder() {
		var reminder=getCookie("reminderToggle");
		if ((reminder==null) || (reminder==0)){
			reminder = 1;
		} else {
			reminder = 0;
			document.getElementById("beReminded").checked = false;
		}
		setCookie("reminderToggle",reminder,365);
	}
	
	function toggleSocial() {
		var social=getCookie("socialToggle");
		if ((social==null) || (social==0)){
			social = 1;
			document.getElementById("socialToggle1").innerHTML = "Social <b>ON</b>";
			document.getElementById("socialToggle2").innerHTML = "Social <b>ON</b>";
			document.getElementById("socialToggle3").innerHTML = "Turn Social OFF";
			shareArticle();
		} else {
			social = 0;
			document.getElementById("socialToggle1").innerHTML = "Social <b>OFF</b>";
			document.getElementById("socialToggle2").innerHTML = "Social <b>OFF</b>";
			document.getElementById("socialToggle3").innerHTML = "Turn Social ON";
		}
		setCookie("socialToggle",social,365);
	}

<?php if (count($a_articlesRead['data'])>5) : ?>
	function showMoreActivity (num) {
		x = document.getElementById("fbRecentActivity").childNodes
		var count=0;
		for (i=0;i<x.length;i++) {
			if (x[i].className == "fbPastActivity") {
				count += 1;
				if ((count<num) || (count>=num+5)) {
					x[i].style.display="none";
				} else {
					x[i].style.display="block";
				}
			}
		}
		if (num==1) {
			document.getElementById("leftNav").className = "navBox left inactive";
			document.getElementById("arrowLeft").className = "arrowLeft inactive";
			
			document.getElementById("rightNav").className = "navBox right active";
			document.getElementById("arrowRight").className = "arrowRight active";
			
			document.getElementById("leftNav").onclick=function() {};
		} else {
			document.getElementById("leftNav").className = "navBox left active";
			document.getElementById("arrowLeft").className = "arrowLeft active";

			document.getElementById("leftNav").onclick=function() {showMoreActivity(num-5);};
			if (num+5<=count) {
				document.getElementById("rightNav").className = "navBox right active";
				document.getElementById("arrowRight").className = "arrowRight active";
			} else {
				document.getElementById("rightNav").className = "navBox right inactive";
				document.getElementById("arrowRight").className = "arrowRight inactive";
			}
		}
		if (num+5<=count) {
			document.getElementById("activityDisplay").innerHTML = num + "-" + (num+4);
			document.getElementById("rightNav").onclick=function() {showMoreActivity(num+5);};
		} else {
			document.getElementById("activityDisplay").innerHTML = num + "-" + count;
			document.getElementById("rightNav").onclick=function() {};
		}
	}
<?php endif ?>
	
<?php endif ?>
</script>

<?php if (isset($_REQUEST["ra"])): ?>
<script type="text/javascript" src="https://platform.twitter.com/widgets.js"></script>
<script type="text/javascript" src="https://apis.google.com/js/plusone.js"></script>
<?php endif; ?>
