<?php
require("class.phpmailer.php");
include("UrlLinker.php");

/* METHODS

PRIVATE  
function __openDB()
function __closeDB ($con)    
function __bool ($b_var)  
function __dbClean ($string, $con)
function __addAuthor ($con, $s_name, $s_email, $b_newsletter, $b_overwrite=false)
function __doesTopicExist ($con, $s_topic)
function __getAnswer ($con, $pk_answer)
function __getAnswerStatus ($con, $pk_answer)
function __getTopic ($con, $pk_topic) 
function __getTopicStatus ($con, $pk_topic)

READ-ONLY
function emailAnswer ($pk_answer, $s_topic, $s_name, $s_email, $s_friendName, $s_friendEmail, $s_message, $b_newsletter, $b_cc);
function emailTopic ($pk_topic, $s_topic, $s_name, $s_email, $s_friendName, $s_friendEmail, $s_message, $b_newsletter, $b_cc)
function getAnswer ($pk_answer)
function getAnswerCatgegory ($pk_answer)
function getAnswerFirst ($pk_answer)
function getAnswerPrevious ($pk_answer)
function getAnswerQuote ($pk_topic)
function getAnswerStatus ($pk_answer)
function getAnswerTopic ($pk_answer)
function getApprovedAnswers ($pk_topic)
function getApprovedTopics ()
function getAnsweredTopics () 
function getCategories()
function getCategoryTopics ($pk_category, $pk_excludedTopic="") 
function getFirstName($s_name)
function getMoods()
function getMostViewedAnswers()
function getRecentlyViewedAnswers()
function getRecentlyapprovedAnswers()
function getTopic ($pk_topic) 
function getTopicStatus ($pk_topic)
function getUrl($uri="", $b_currentPage=false)
function isApproved ($i_status)
function isAnswerApproved ($pk_answer)
function isDenied ($i_status)
function isNotFound ($i_status)
function isPending ($i_status)
function isTopicAnswered ($pk_topic)
function isTopicApproved ($pk_topic)
function emailClean ($string)
function htmlClean ($string)
function jsClean ($string)
function stringToUrl ($string)
function urlClean ($string)

READ/WRITE
function addAnswer ($pk_topic, $s_name, $s_email, $s_answer, $b_anonymous, $b_bookConsideration, $b_newsletter, $s_citationName="", $s_citationUrl="", $s_audio="")
function addAuthor ($s_name, $s_email, $b_newsletter)
function addTopic ($s_topic, $s_name, $s_email, $b_notifyauth, $b_newsletter)
function confirmInterview ($pk_topic, $s_name, $s_email, $s_biography, $b_legal, $b_anonymous, $b_newsletter)

ADMIN
function addCategory ($s_category, $s_description);
function addMood ($s_mood, $s_rgb, $s_fontColor);
function approveAnswer ($pk_answer, $pk_mood, $s_quote, $i_rating)
function approveTopic ($pk_topic, $pk_category, $s_topic)
function denyanswer ($pk_answer)
function denyTopic ($pk_topic)
function emailUser ($s_name, $s_email, $s_subject, $s_message)
function getSitemapData ()
function getTopicsWithUnapprovedAnswers ()
function getUnapprovedAnswers ($pk_topic)
function getUnapprovedTopics ()
function incrementViewCount($pk_answer)
function updateAnswer ($pk_answer, $s_answer, $s_quote, $i_rating, $s_citationName="", $s_citationUrl="" , $s_audio="")
function updateTopic ($pk_topic, $pk_category, $s_topic)

  -----------------------------------------------------------------*/


/* PRIVATE FUNCTIONS
  -----------------------------------------------------------------*/

// Private function: Make a MySQL connection
function __openDB() {

	extract(parse_url($_ENV["DATABASE_URL"]));
	
	return pg_connect("host=$host port=$port dbname=".substr($path, 1)." user=$user password=$pass");
}

// Private function: Close DB connection
function __closeDB ($con) {
	pg_close($con);
}

// Private function: Converts non-zero to 1 and zero/empty to 0
function __bool ($b_var) {
	if ($b_var) return 1;
	return 0;
}

// Private function: Sanitizes string for input into database
function __dbClean ($string, $con) {
	$abc = pg_escape_string($con, $string);
	return $abc;
}

// Private function: Add new author into author database; returns author id (overwrite flag should only be set to true when using verified name, e.g. from Facebook Connect)
function __addAuthor ($con, $s_name, $s_email, $b_newsletter, $b_overwrite=false) {
	$sql = "SELECT id FROM authors WHERE email='".__dbClean ($s_email, $con)."'";
	$result = pg_query($con, $sql);
	$result_row = pg_fetch_row($result);
	
	// if author already in database and we want to overwrite name
	if (($b_overwrite) && ($result_row)) {
		$pk_author = $result_row[0];
		$sql = "UPDATE authors SET name='".__dbClean($s_name, $con)."'";
		$where = " WHERE id='$pk_author'";
		if ($b_newsletter) { 
			$sql .= ", newsletter=".__bool($b_newsletter);
		}
		if (!pg_query($con, $sql . $where)) { die('Could not insert new author into database: ' . pg_last_error()); }
	} elseif (!$result_row) {
		$sql = "INSERT INTO authors (name, email, newsletter) VALUES ('".__dbClean($s_name, $con)."', '".__dbClean($s_email, $con)."', '" .__bool($b_newsletter). "')";
		if (!pg_query($con, $sql)) { die('Could not insert new author into database: ' . pg_last_error()); }
		
		$sql = "SELECT id FROM authors WHERE email='".__dbClean ($s_email, $con)."'";
		$result = pg_query($con, $sql);
		$result_row = pg_fetch_row($result);
	}

	return $result_row[0];
}


// Private Function: Returns the topic ID if topic exists and FALSE if topic does not exist
function __doesTopicExist ($con, $s_topic) {
	$sql = "SELECT id FROM topics WHERE topic='".__dbClean ($s_topic, $con)."'";
	$result = pg_query($con, $sql);
	$result_row = pg_fetch_row($result);
	
	if ($result_row) { return $result_row[0]; }
	return FALSE;
}

// Return associated answer information when given answer pkey
function __getAnswer ($con, $pk_answer) {
	$result_array = array();
	$sql = "SELECT answers.answer, topics.topic, topics.topic_url, authors.name, authors.email, answers.anonymous, answers.citation_name, answers.citation_url, answers.audio, moods.rgb, answers.dt_created, answers.dt_updated, views.viewcount FROM answers, topics, authors, moods, views WHERE answers.topicid=topics.id AND answers.authorid=authors.id AND answers.moodid=moods.id AND answers.id=views.answerid AND answers.id='$pk_answer'";
	$result = pg_query($con, $sql);
	$result_row = pg_fetch_row($result);
	if ($result_row) {
		$result_array["answer"] = mb_convert_encoding($result_row[0], "HTML-ENTITIES", "UTF-8");
		$result_array["topic"] = mb_convert_encoding($result_row[1], "HTML-ENTITIES", "UTF-8");
		$result_array["url"] = $result_row[2];
		$result_array["name"] = $result_row[3];
		$result_array["email"] = $result_row[4];
		$result_array["anonymous"] = $result_row[5];
		$result_array["citation_name"] = $result_row[6];
		$result_array["citation_url"] = $result_row[7];
		$result_array["audio"] = $result_row[8];
		$result_array["rgb"] = $result_row[9];
		$result_array["dt_created"] = $result_row[10];
		$result_array["dt_updated"] = $result_row[11];
		$result_array["viewcount"] = $result_row[12];
	}
	
	return $result_array;
}

// Returns -1 if answer is not found, 0 if answer is pending, 1 if approved and 2 if denied
function __getAnswerStatus ($con, $pk_answer) {
	$sql = "SELECT status FROM answers WHERE id='".__dbClean ($pk_answer, $con)."'";
	$result = pg_query($con, $sql);
	$result_row = pg_fetch_row($result);
	
	if ($result_row) return $result_row[0];
	return 3;
}

// return topic, url, name, email, notify, dt_created, and dt_updated when given topic id
function __getTopic ($con, $pk_topic) {
	$result_array = array();
	$sql = "SELECT topics.topic, topics.topic_url, authors.name, authors.email, topics.notifyauth, topics.dt_created, topics.dt_updated FROM topics, authors WHERE topics.authorid=authors.id AND topics.id='$pk_topic'";
	$result = pg_query($con, $sql);
	$result_row = pg_fetch_row($result);
	if ($result_row) {
		$result_array["topic"] = mb_convert_encoding($result_row[0], "HTML-ENTITIES", "UTF-8");
		$result_array["url"] = $result_row[1];
		$result_array["name"] = $result_row[2];
		$result_array["email"] = $result_row[3];
		$result_array["notify"] = $result_row[4];
		$result_array["dt_created"] = $result_row[5];
		$result_array["dt_updated"] = $result_row[6];
	}
	return $result_array;
}

// Returns 0 if topic is pending, 1 if approved and 2 if denied
function __getTopicStatus ($con, $pk_topic) {
	$sql = "SELECT status FROM topics WHERE id='".__dbClean ($pk_topic, $con)."'";
	$result = pg_query($con, $sql);
	$result_row = pg_fetch_row($result);

	if ($result_row) return $result_row[0];
	return 3;
}

/* READ-ONLY FUNCTIONS
  ----------------------------------------------------------------- */

// Send friend an email tellng them about an answer that they would be interested in reading
function emailAnswer ($pk_answer, $s_topic, $s_name, $s_email, $s_friendName, $s_friendEmail, $s_message, $b_newsletter, $b_cc) {
	addAuthor ($s_name, $s_email, $b_newsletter);

	$mail = new PHPMailer();
    
	$s_stockMessage =  "Dear ".getFirstName(htmlClean($s_friendName)).",<br><br>";
	$s_stockMessage .= "Too Polite To Ask is a website dedicated to understanding others and your friend, ".htmlClean($s_name).", thought that you may appreciate reading about what it feels like to ".htmlClean($s_topic).".<br><br>";
	$s_stockMessage .= "<i>(from ".htmlClean($s_name).")</i><br>";
	$s_stockMessage .= emailClean(htmlClean($s_message))."<br><br>";
	$s_stockMessage .= "<a href=\"".getUrl("what-it-feels-like-to-".stringToUrl($s_topic)."/$pk_answer/")."\">Click here to view the answer</a><br><br>";
	$s_stockMessage .= "This message was sent to you by Too Polite To Ask.  ";
	$s_stockMessage .= "To be notified of upcoming Too Polite To Ask products and news, <a href=\"".getUrl("get-newsletter/")."\">subscribe</a> to our mailings";

    $mail->ClearAddresses(); 
    $mail->ClearAttachments();
    $mail->IsHTML(true);
	
	$mail->AddReplyTo("info@toopolite.com", "Admin");
	$mail->From = "info@toopolite.com";
	$mail->FromName = "Too Polite To Ask";

	$mail->AddAddress($s_friendEmail, $s_friendName);
	if ($b_cc) { $mail->AddCC($s_email, $s_name); }

	$mail->Subject = htmlClean($s_name)." would like to share a story with you";
	$mail->Body = $s_stockMessage;
	$mail->WordWrap = 50;
	
	if(!$mail->Send()){
		echo "Message could not be sent. <p>";
		echo "Mailer Error: " . $mail->ErrorInfo;
		exit;
	}
}

// Send friend an email asking them to submit an answer to a topic
function emailTopic ($pk_topic, $s_topic, $s_name, $s_email, $s_friendName, $s_friendEmail, $s_message, $b_newsletter, $b_cc) {
	$con = __openDB();
	__addAuthor ($con, $s_name, $s_email, $b_newsletter);
	__closeDB($con);

	$mail = new PHPMailer();
    
	$s_stockMessage =  "Dear ".getFirstName(htmlClean($s_friendName)).",<br><br>";
	$s_stockMessage .= "Too Polite To Ask is a website dedicated to understanding others and your friend, ".htmlClean($s_name).", has asked if you will share your experience on what it feels like to ".htmlClean($s_topic).".<br><br>";
	$s_stockMessage .= "<i>(from ".htmlClean($s_name).")</i><br>";
	$s_stockMessage .= emailClean(htmlClean($s_message))."<br><br>";
	$s_stockMessage .= "<a href=\"".getUrl("give-your-answer/$pk_topic/")."\">Click here to submit your answer</a><br><br>";
	$s_stockMessage .= "This message was sent to you by Too Polite To Ask.  ";
	$s_stockMessage .= "To be notified of upcoming Too Polite To Ask products and news, <a href=\"".getUrl("get-newsletter/")."\">subscribe</a> to our mailings";

    $mail->ClearAddresses(); 
    $mail->ClearAttachments();
    $mail->IsHTML(true);
	
	$mail->From = "info@toopolite.com";
	$mail->FromName = "Too Polite To Ask";

	$mail->AddAddress($s_friendEmail, $s_friendName);
	if ($b_cc) { $mail->AddCC($s_email, $s_name); }

	$mail->Subject = htmlClean($s_name)." asks if you will share your story";
	$mail->Body = $s_stockMessage;
	$mail->WordWrap = 50;

	if(!$mail->Send()){
		echo "Message could not be sent. <p>";
		echo "Mailer Error: " . $mail->ErrorInfo;
		exit;
	}
}

// Return associated answer information when given answer pkey
function getAnswer ($pk_answer) {
	$con = __openDB();
	
	$result_array = __getAnswer ($con, $pk_answer);
	
	__closeDB($con);
	
	return $result_array;
}

// Return category that is associated with an answer when given answer pkey
function getAnswerCategory ($pk_answer) {
	$con = __openDB();
	
	$result_array = array();
	$sql = "SELECT category FROM answers, topics, categories WHERE answers.id='$pk_answer' AND answers.topicid=topics.id AND topics.categoryid=categories.id";
	$result = pg_query($con, $sql);
	$result_row = pg_fetch_row($result);
	
	__closeDB($con);
	
	if ($result_row) { return $result_row[0]; }
	return "";
}

// Return associated answer information as it was first submitted when given answer pkey
function getAnswerFirst ($pk_answer) {
	$con = __openDB();
	
	$result_array = array();
	$sql = "SELECT answer_first FROM answers WHERE id='$pk_answer'";
	$result = pg_query($con, $sql);
	$result_row = pg_fetch_row($result);
	
	__closeDB($con);
	
	if ($result_row) { return $result_row[0]; }
	return "";
}

// Return associated answer information as it was prior to the last update when given answer pkey
function getAnswerPrevious ($pk_answer) {
	$con = __openDB();
	
	$result_array = array();
	$sql = "SELECT answer_previous FROM answers WHERE id='$pk_answer'";
	$result = pg_query($con, $sql);
	$result_row = pg_fetch_row($result);
	
	__closeDB($con);
	
	if ($result_row) { return mb_convert_encoding($result_row[0], "HTML-ENTITIES", "UTF-8"); }
	return "";
}

// Return quote from first answer in topic, and topic_url when given answer pkey
function getAnswerQuote ($pk_topic) {
	$con = __openDB();
		
	$sql = "SELECT quote FROM answers WHERE rating=(SELECT MAX(rating) FROM answers WHERE topicid='$pk_topic') AND topicid='$pk_topic'";
	$result = pg_query($con, $sql);
	$result_row = pg_fetch_row($result);
	if ($result_row) {
		$result_array["quote"] = mb_convert_encoding($result_row[0], "HTML-ENTITIES", "UTF-8");
		$result_array["url"] = $result_row[1];
	}

	__closeDB($con);
	
	return $result_array;
}

// Returns 0 if answer is pending, 1 if approved and 2 if denied
function getAnswerStatus ($pk_answer) {
	$con = __openDB();

	$i_status = __getAnswerStatus($con, $pk_answer);
	
	__closeDB($con);
	
	return $i_status;
}

// Return topicid when given answer pkey
function getAnswerTopic ($pk_answer) {
	$con = __openDB();
		
	$sql = "SELECT topicid FROM answers WHERE id='$pk_answer'";
	$result = pg_query($con, $sql);
	$result_row = pg_fetch_row($result);
	
	__closeDB($con);
	
	if ($result_row) { return $result_row[0]; }
	return -1;
}

// Return array of answer pkeys when given topic pkey
function getApprovedAnswers ($pk_topic) {
	$con = __openDB();
		
	$result_array = array();
	$sql = "SELECT answers.id, answers.answer, answers.quote, answers.rating, authors.name, answers.anonymous, answers.citation_name, answers.citation_url, answers.audio, answers.moodid, answers.dt_created, answers.dt_updated FROM answers, authors WHERE answers.authorid = authors.id AND answers.topicid='$pk_topic' AND answers.status='1' ORDER BY answers.rating DESC";
	$result = pg_query($con, $sql);
	while($result_row = pg_fetch_row($result)) {
		$temp_array = array();
		$temp_array["id"] = $result_row[0];
		$temp_array["answer"] = mb_convert_encoding($result_row[1], "HTML-ENTITIES", "UTF-8");
		$temp_array["quote"] = mb_convert_encoding($result_row[2], "HTML-ENTITIES", "UTF-8");
		$temp_array["rating"] = $result_row[3];
		$temp_array["name"] = $result_row[4];
		$temp_array["anonymous"] = $result_row[5];
		$temp_array["citation_name"] = $result_row[6];
		$temp_array["citation_url"] = $result_row[7];
		$temp_array["audio"] = $result_row[8];
		$temp_array["moodid"] = $result_row[9];
		$temp_array["dt_created"] = $result_row[10];
		$temp_array["dt_updated"] = $result_row[11];
		$result_array[] = $temp_array;
	}
	
	__closeDB($con);
	
	return $result_array;
}

// Return array of approved topic pkeys (for topics that either have or do not have answers)
function getApprovedTopics () {
	$con = __openDB();

	$topic_array = array();	
	$result_array = array();
	$sql = "SELECT DISTINCT topics.id, topics.topic, topics.categoryid, answers.status, authors.name, topics.dt_created, topics.dt_updated FROM authors, topics LEFT JOIN answers ON topics.id=answers.topicid WHERE topics.authorid=authors.id AND topics.status='1' ORDER BY topics.topic";
	$result = pg_query($con, $sql);
	while($result_row = pg_fetch_row($result)) {
		if (!isset($topic_array[$result_row[0]])) {
			$temp_array = array();
			$temp_array["id"] = $result_row[0];
			$temp_array["topic"] = mb_convert_encoding($result_row[1], "HTML-ENTITIES", "UTF-8");
			$temp_array["categoryid"] = $result_row[2];
			$temp_array["answered"] = (isApproved($result_row[3]));
			$temp_array["name"] = $result_row[4];
			$temp_array["dt_created"] = $result_row[5];
			$temp_array["dt_updated"] = $result_row[6];
			$result_array[] = $temp_array;
			$topic_array[$result_row[0]] = ($temp_array["answered"]) ? 1 : 2;
		} else if (($topic_array[$result_row[0]] == 2) && (isApproved($result_row[3]))) {
			$temp_array["answered"] = TRUE;
		}
	}
	
	__closeDB($con);
	
	return $result_array;
}

// Return array of approved topics for topics that have answers
function getAnsweredTopics () {
	$con = __openDB();
	
	$result_array = array();
	$sql = "SELECT DISTINCT topics.id, topics.topic, topics.topic_url, topics.categoryid FROM topics, answers WHERE topics.id=answers.topicid AND answers.status='1' ORDER BY topics.topic";
	$result = pg_query($con, $sql);
	while($result_row = pg_fetch_row($result)) {
		$temp_array = array();
		$temp_array["id"] = $result_row[0];
		$temp_array["topic"] = mb_convert_encoding($result_row[1], "HTML-ENTITIES", "UTF-8");
		$temp_array["url"] = $result_row[2];
		$temp_array["categoryid"] = $result_row[3];
		$result_array[] = $temp_array;
	}
	
	__closeDB($con);
	
	return $result_array;
}

// Return array of categories from categories database
function getCategories () {
	$con = __openDB();

	$result_array = array();
	$sql = "SELECT id, category, description FROM categories ORDER BY id";
	$result = pg_query($con, $sql);
	while($result_row = pg_fetch_row($result)) {
		$temp_array = array();
		$temp_array["id"] = $result_row[0];
		$temp_array["category"] = $result_row[1];
		$temp_array["description"] = $result_row[2];
		$result_array[] = $temp_array;
	}
	
	__closeDB($con);
	
	return $result_array;
}

// Return array of approved topics for topics that have answers in category (also passes top rated approved answer in each topic)
function getCategoryTopics ($pk_category, $pk_excludedTopic="-1") {
	$con = __openDB();
	
	$topic_array = array();
	$result_array = array();
	$sql = "SELECT topics.id, topics.topic, topics.topic_url, answers.id FROM topics, answers WHERE topics.id=answers.topicid AND answers.status='1' AND topics.categoryid='$pk_category' AND topics.id!='$pk_excludedTopic' ORDER BY answers.rating DESC LIMIT 10";
	$result = pg_query($con, $sql);
	while($result_row = pg_fetch_row($result)) {
		if (!isset($topic_array[$result_row[0]])) {
			$temp_array = array();
			$temp_array["id"] = $result_row[0];
			$temp_array["topic"] = $result_row[1];
			$temp_array["url"] = $result_row[2];
			$temp_array["answerid"] = $result_row[3];
			$result_array[] = $temp_array;
			$topic_array[$result_row[0]] = 1;
		}
	}
	
	__closeDB($con);
	
	return $result_array;
}

// Return first characters in string up until the first space.  If no space is present, return entire string.
function getFirstName ($s_name) {
	$s_firstName = strtok($s_name, " ");
	if ($s_firstName) 
		return $s_firstName;
	return $s_name;
}

// Return array of moods from moods database
function getMoods () {
	$con = __openDB();

	$result_array = array();
	$sql = "SELECT id, mood, rgb, font_color FROM moods ORDER BY id";
	$result = pg_query($con, $sql);
	while($result_row = pg_fetch_row($result)) {
		$temp_array = array();
		$temp_array["id"] = $result_row[0];
		$temp_array["mood"] = $result_row[1];
		$temp_array["rgb"] = $result_row[2];
		$temp_array["font_color"] = $result_row[3];
		$result_array[] = $temp_array;
	}
	
	__closeDB($con);
	
	return $result_array;
}

// Return array of answers that have the most views
function getMostViewedAnswers() {
	$con = __openDB();
	
	$topic_array = array();
	$result_array = array();
	$sql = "SELECT topics.id, topics.topic_url, answers.id, topics.topic, views.viewcount FROM topics, answers, views WHERE topics.id=answers.topicid AND answers.id=views.answerid AND answers.status='1' ORDER BY views.viewcount DESC LIMIT 10";
	$result = pg_query($con, $sql);
	while($result_row = pg_fetch_row($result)) {
		if (!isset($topic_array[$result_row[0]])) {
			$temp_array = array();
			$temp_array["url"] = $result_row[1];
			$temp_array["id"] = $result_row[2];
			$temp_array["topic"] = $result_row[3];
			$result_array[] = $temp_array;
			$topic_array[$result_row[0]] = 1;
		}
	}
	
	__closeDB($con);
	
	return $result_array;
}

// Return array of answers that have been recently viewed
function getRecentlyViewedAnswers() {
	$con = __openDB();
	
	$topic_array = array();
	$result_array = array();
	$sql = "SELECT topics.id, topics.topic_url, answers.id, topics.topic, views.dt_updated FROM topics, answers, views WHERE topics.id=answers.topicid AND answers.id=views.answerid AND answers.status='1' ORDER BY views.dt_updated DESC LIMIT 10";
	$result = pg_query($con, $sql);
	while($result_row = pg_fetch_row($result)) {
		if (!isset($topic_array[$result_row[0]])) {
			$temp_array = array();
			$temp_array["url"] = $result_row[1];
			$temp_array["id"] = $result_row[2];
			$temp_array["topic"] = $result_row[3];
			$result_array[] = $temp_array;
			$topic_array[$result_row[0]] = 1;
		}
	}
	
	__closeDB($con);
	
	return $result_array;
}

// Return array of approved answers that have been most recently created
function getRecentlyapprovedAnswers() {
	$con = __openDB();
	
	$topic_array = array();
	$result_array = array();
	$sql = "SELECT topics.id, topics.topic_url, answers.id, topics.topic, answers.dt_created FROM topics, answers WHERE topics.id=answers.topicid AND answers.status='1' ORDER BY answers.dt_created DESC LIMIT 10";
	$result = pg_query($con, $sql);
	while($result_row = pg_fetch_row($result)) {
		if (!isset($topic_array[$result_row[0]])) {
			$temp_array = array();
			$temp_array["url"] = $result_row[1];
			$temp_array["id"] = $result_row[2];
			$temp_array["topic"] = mb_convert_encoding($result_row[3], "HTML-ENTITIES", "UTF-8");
			$result_array[] = $temp_array;
			$topic_array[$result_row[0]] = 1;
		}
	}
	
	__closeDB($con);
	
	return $result_array;
}

// Return topic, topic_url, author, author's email and notifyauth flag when given topic pkey
function getTopic ($pk_topic) {
	$con = __openDB();
		
	$result_array = __getTopic($con, $pk_topic);
	
	__closeDB($con);
	
	return $result_array;
}

// Returns 0 if topic is pending, 1 if approved and 2 if denied
function getTopicStatus ($pk_topic) {
	$con = __openDB();

	$i_status = __getTopicStatus($con, $pk_topic);
	
	__closeDB($con);
	
	return $i_status;
}

// Return string of Too Polite To Ask domain (e.g. "http://toopolite.com/")
function getUrl ($uri="", $b_currentPage=false) {
	if ($b_currentPage) {
		return "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; 
	}
	if (substr($uri, 0, 1) == "/") {
		return "http://".$_SERVER['HTTP_HOST'].$uri;
	}
	return "http://".$_SERVER['HTTP_HOST']."/".$uri;
}

// return TRUE if status=1
function isApproved ($i_status) {
	return ($i_status==1);
}

// If answer has been approved, return TRUE, else return FALSE
function isAnswerApproved ($pk_answer) {
	$con = __openDB();

	$i_status = __getAnswerStatus($con, $pk_answer);
	
	__closeDB($con);
	
	if ($i_status == 1) { return TRUE; }
	return FALSE;
}

// return TRUE if status=2
function isDenied ($i_status) {
	return ($i_status==2);
}

// return TRUE if status=3
function isNotFound ($i_status) {
	return ($i_status==3);
}

// return TRUE if status=0
function isPending ($i_status) {
	return ($i_status==0);
}

// If topic has been answered, return TRUE, else return FALSE
function isTopicAnswered ($pk_topic) {
	$con = __openDB();

	$result_array = array();
	$sql = "SELECT topics.id FROM topics, answers WHERE topics.id='$pk_topic' AND topics.id=answers.topicid AND answers.status='1' LIMIT 1";
	$result = pg_query($con, $sql);
	$result_row = pg_fetch_row($result);
	
	__closeDB($con);
	
	if ($result_row) return TRUE;
	return FALSE;
}

// If topic has been approved, return TRUE, else return FALSE
function isTopicApproved ($pk_topic) {
	$con = __openDB();

	$i_status = __getTopicStatus($con, $pk_topic);
	
	__closeDB($con);
	
	if ($i_status == 1) { return TRUE; }
	return FALSE;
}

// Sanitizes string for output in email
function emailClean ($string) {
	$search = array("\n");
	$replace = array("<br>");
	return str_replace($search, $replace, $string);
}

// Sanitizes string for output in HTML
function htmlClean ($string) {
	return htmlspecialchars($string);
}
  
// Sanitizes string for output in Javascript
function jsClean ($string) { 
	$search = array("\\","\0","\n","\r","\x1a","'",'"');
	$replace = array("\\\\","\\0","\\n","\\r","\Z","\'",'\"');
	return str_replace($search, $replace, $string);
}

// Replaces all spaces with dashes and removes all special characters
function stringToUrl ($string) {
	$patterns = array();
	$patterns[0] = '/[^a-zA-Z0-9\s]/';
	$patterns[1] = '/[\s]/';
	$replacements = array();
	$replacements[0] = '';
	$replacements[1] = '-';
	return preg_replace($patterns, $replacements, $string);
}

// Sanitizes string for output in Javascript
function urlClean ($string) { 
  return urlencode ($string);
}

/* WRITE FUNCTIONS
  ----------------------------------------------------------------- */

// Add new answer into answer database
function addAnswer ($pk_topic, $s_name, $s_email, $s_answer, $b_anonymous, $b_bookConsideration, $b_newsletter, $s_citationName="", $s_citationUrl="", $s_audio="") {
	$con = __openDB();
	
	$pk_author = __addAuthor ($con, $s_name, $s_email, $b_newsletter, true);
	$sql = "INSERT INTO answers (topicid, answer, answer_first, answer_previous, authorid, anonymous, book, citation_name, citation_url, audio) VALUES ('$pk_topic', '".__dbClean($s_answer, $con)."', '".__dbClean($s_answer, $con)."', '".__dbClean($s_answer, $con)."', '$pk_author', '" . __bool($b_anonymous) . "', '" . __bool($b_book) . "', '".__dbClean($s_citationName, $con)."', '".__dbClean($s_citationUrl, $con)."', '".__dbClean($s_audio, $con)."')";
	if (!pg_query($con, $sql)) { die('Could not insert new answer into database: ' . pg_last_error()); }	
	
	__closeDB($con);

	emailUser ("New Answer Added", "eappleby@toopolite.com", $s_name." submitted new answer", $s_answer);	
}

// Add new author into author database (if author already in database, name and email is updated; newsletter can only go from FALSE to TRUE)
function addAuthor ($s_name, $s_email, $b_newsletter) {
	$con = __openDB();
	
	$pk_author = __addAuthor ($con, $s_name, $s_email, $b_newsletter);
	
	__closeDB($con);
	
	return $pk_author;
}

// Add new topic into topic database (will still need to be approved by admin) (return -1 if add successful and if topic already exists, will return the ID of that topic)
function addTopic ($s_topic, $s_name, $s_email, $b_notifyauth, $b_newsletter) {
	$con = __openDB();
	
	$pk_existingTopic = __doesTopicExist ($con, $s_topic);
	if (!$pk_existingTopic) {
		$pk_author = __addAuthor ($con, $s_name, $s_email, $b_newsletter);	
		$sql = "INSERT INTO topics (topic, topic_url, authorid, notifyauth) VALUES ('".__dbClean($s_topic, $con)."', '" . stringToUrl(__dbClean($s_topic, $con)) ."', '$pk_author', " . __bool($b_notifyauth) . ")";
		if (!pg_query($con, $sql)) { die('Could not insert new topic into database: ' . pg_last_error()); }
	}
	__closeDB($con);
	
	if (!$pk_existingTopic) {
		emailUser ("New Topic Added", "eappleby@toopolite.com", $s_name." submitted new topic", $s_topic);
	} else {
		emailUser ("New Topic Submitted, but Already Exists", "eappleby@toopolite.com", $s_name." submitted new topic", $s_topic);
	}
	
	return $pk_existingTopic;
}

// Confirm interviewee information and approval of terms of service and privacy policy
function confirmInterview ($pk_topic, $s_name, $s_email, $s_biography, $b_legal, $b_anonymous, $b_newsletter) {
	$con = __openDB();
	
	$pk_author = __addAuthor ($con, $s_name, $s_email, $b_newsletter);
	$sql = "INSERT INTO interviews (topicid, name, biography, anonymous, legal) VALUES ('$pk_topic', '".__dbClean($s_name, $con)."', '".__dbClean($s_biography, $con)."', " . __bool($b_anonymous) . ", " . __bool($b_legal) .")";
	if (!pg_query($con, $sql)) { die('Could not insert interview information into database: ' . pg_last_error()); }	
	
	__closeDB($con);

	emailUser ("Interview Information Received", "eappleby@toopolite.com", $s_name." submitted information", $s_biography);	
}

/* ADMIN FUNCTIONS
  ----------------------------------------------------------------- */

// Add new category into categories database
function addCategory ($s_category, $s_description) {
	$con = __openDB();
	
	$sql = "INSERT INTO categories (category, description) VALUES ('".__dbClean($s_category, $con)."', '".__dbClean($s_description, $con)."')";
	if (!pg_query($con, $sql)) { die('Could not insert new category into database: ' . pg_last_error()); }	
	
	__closeDB($con);
}

// Add new mood into moods database
function addMood ($s_mood, $s_rgb, $s_fontColor="FFF") {
	$con = __openDB();
	
	$sql = "INSERT INTO moods (mood, rgb, font_color) VALUES ('".__dbClean($s_mood, $con)."', '".__dbClean($s_rgb, $con)."', '".__dbClean($s_fontColor, $con)."')";
	if (!pg_query($con, $sql)) { die('Could not insert new mood into database: ' . pg_last_error()); }	
	
	__closeDB($con);
}

// Set answer to approved and provide quote
function approveAnswer ($pk_answer, $pk_mood, $s_quote, $i_rating=0) {
	$con = __openDB();
	
	$sql = "UPDATE answers SET status='1', moodid='$pk_mood', quote='".__dbClean($s_quote, $con)."', rating='".__dbClean($i_rating, $con)."' WHERE id='$pk_answer'";
	if (!pg_query($con, $sql)) { die('Could not approve answer in database: ' . pg_last_error()); }
	
	$a_answer = __getAnswer ($con, $pk_answer);

	__closeDB($con);

	// Notify user that answer is approved
	$s_subject =  "Your answer has been approved on Too Polite To Ask";
	
	// also have topic url once the .htaccess is all set up
	$s_message =  "Dear ".getFirstName(htmlClean($a_answer["name"])).",<br><br>";
	$s_message .= "The answer you submitted to the topic \"What does it feel like to ".htmlClean($a_answer["topic"])."?\" has been approved.<br><br>";
//	$s_message .= "If you would like to make any substantial changes, just re-submit a new answer.<br><br>";
	$s_message .= "<a href=\"".getUrl("what-it-feels-like-to-".$a_answer["url"]."/$pk_answer/")."\">Click here to view your answer</a><br>";
	$s_message .= "<a href=\"".getUrl("share-answer/$pk_answer/")."\">Click here to share your answer with friends</a><br><br>";
	$s_message .= "This message was sent to you by Too Polite To Ask.  ";
	$s_message .= "To be notified of upcoming Too Polite To Ask products and news, <a href=\"".getUrl("get-newsletter/")."\">subscribe</a> to our mailings";

	emailUser ($a_answer["name"], $a_answer["email"], $s_subject, $s_message);
}

// Set topic to approved and update related fields when given topic pkey 
function approveTopic ($pk_topic, $pk_category, $s_topic) {
	$con = __openDB();
	
	$sql = "UPDATE topics SET status='1', categoryid='$pk_category', topic='".__dbClean($s_topic, $con)."', topic_url='" . stringToUrl(__dbClean($s_topic, $con)) ."' WHERE id='$pk_topic'";
	if (!pg_query($con, $sql)) { die('Could not approve topic in database: ' . pg_last_error()); }
	
	$a_topic = __getTopic ($con, $pk_topic);
	
	__closeDB($con);
	
	// If user wants to be notified when topic is approved
	if ($a_topic["notify"]) {
		$s_subject =  "Your topic has been approved on Too Polite To Ask";
	
		// also have topic url once the .htaccess is all set up
		$s_message =  "Dear ".getFirstName(htmlClean($a_topic["name"])).",<br><br>";
		$s_message .= "The topic you submitted \"What does it feel like to ".htmlClean($a_topic["topic"])."?\" has been approved.<br><br>";
		$s_message .= "<a href=\"".getUrl("give-your-answer/$pk_topic/")."\">Click here to share your experience</a><br>";
		$s_message .= "<a href=\"".getUrl("ask-a-friend/$pk_topic/")."\">Click here to ask a friend to share their experience</a><br><br>";
		$s_message .= "This message was sent to you by Too Polite To Ask.  ";
		$s_message .= "To be notified of upcoming Too Polite To Ask products and news, <a href=\"".getUrl("get-newsletter/")."\">subscribe</a> to our mailings";

		emailUser ($a_topic["name"], $a_topic["email"], $s_subject, $s_message);
	}
}

// Set answer to denied in when given answer pkey 
function denyanswer ($pk_answer) {
	$con = __openDB();
	
	$sql = "UPDATE answers SET status='2' WHERE id='$pk_answer'";
	if (!pg_query($con, $sql)) { die('Could not deny answer from database: ' . pg_last_error()); }
	
	__closeDB($con);
}

// Set topic to denied in database when given topic pkey 
function denyTopic ($pk_topic) {
	$con = __openDB();
	
	$sql = "UPDATE topics SET status='2' WHERE id='$pk_topic'";
	if (!pg_query($con, $sql)) { die('Could not deny topic from database: ' . pg_last_error()); }
	
	__closeDB($con);
}

// Notify user about approved topic or answer, or just send them a promotional email
function emailUser ($s_name, $s_email, $s_subject, $s_message) {
	$mail = new PHPMailer();
    
    $mail->ClearAddresses(); 
    $mail->ClearAttachments();
    $mail->IsHTML(true);

	$mail->From = "info@toopolite.com";
	$mail->FromName = "Too Polite To Ask";

	$mail->AddAddress($s_email, $s_name);

	$mail->Subject = $s_subject;
	$mail->Body = $s_message;
	$mail->WordWrap = 50;

	if(!$mail->Send()){
		echo "Message could not be sent. <p>";
		echo "Mailer Error: " . $mail->ErrorInfo;
		exit;
	}
}

// Return array of answer IDs, corresponding topic url, last modification dates, and ranking
function getSitemapData () {
	$con = __openDB();

	$result_array = array();
	$sql = "SELECT answers.id, topics.topic_url, answers.dt_updated, answers.rating FROM answers, topics WHERE answers.topicid=topics.id AND answers.status='1'";
	$result = pg_query($con, $sql);
	while($result_row = pg_fetch_row($result)) {
		$temp_array = array();
		$temp_array["id"] = $result_row[0];
		$temp_array["url"] = $result_row[1];
		$temp_array["dt_updated"] = $result_row[2];
		$temp_array["rating"] = $result_row[3];
		$result_array[] = $temp_array;
	}

	__closeDB($con);
	
	return $result_array;
}

// Return array of topic pkeys for topics that have unapproved answers
function getTopicsWithUnapprovedAnswers () {
	$con = __openDB();

	$result_array = array();
	$sql = "SELECT DISTINCT topics.id, topics.topic FROM topics, answers WHERE topics.id=answers.topicid AND answers.status='0' ORDER BY topics.topic";
	$result = pg_query($con, $sql);
	while($result_row = pg_fetch_row($result)) {
		$temp_array = array();
		$temp_array["id"] = $result_row[0];
		$temp_array["topic"] = $result_row[1];
		$result_array[] = $temp_array;
	}
	
	__closeDB($con);
	
	return $result_array;
}

// Return array of unapproved answers pkeys
function getUnapprovedAnswers ($pk_topic) {
	$con = __openDB();
		
	$result_array = array();
	$sql = "SELECT answers.id, answers.answer, authors.name, answers.anonymous, answers.citation_name, answers.citation_url, answers.audio, answers.dt_created, answers.dt_updated FROM answers, authors WHERE answers.authorid = authors.id AND answers.topicid='$pk_topic' AND answers.status='0' ORDER BY answers.dt_created DESC";
	$result = pg_query($con, $sql);
	while($result_row = pg_fetch_row($result)) {
		$temp_array = array();
		$temp_array["id"] = $result_row[0];
		$temp_array["answer"] = $result_row[1];
		$temp_array["name"] = $result_row[2];
		$temp_array["anonymous"] = $result_row[3];
		$temp_array["citation_name"] = $result_row[4];
		$temp_array["citation_url"] = $result_row[5];
		$temp_array["audio"] = $result_row[6];
		$temp_array["dt_created"] = $result_row[7];
		$temp_array["dt_updated"] = $result_row[8];
		$result_array[] = $temp_array;
	}
	
	__closeDB($con);
	
	return $result_array;
}

// Return array of unapproved topic pkeys along with topic string
function getUnapprovedTopics () {
	$con = __openDB();

	$result_array = array();
	$sql = "SELECT topics.id, topics.topic, authors.name, topics.dt_created FROM topics, authors WHERE topics.authorid=authors.id AND status='0' ORDER BY topics.dt_created DESC, topics.topic";
	$result = pg_query($con, $sql);
	while($result_row = pg_fetch_row($result)) {
		$temp_array = array();
		$temp_array["id"] = $result_row[0];
		$temp_array["topic"] = $result_row[1];
		$temp_array["name"] = $result_row[2];
		$temp_array["dt_created"] = $result_row[3];
		$result_array[] = $temp_array;
	}
	
	__closeDB($con);
	
	return $result_array;
}

// Increment view count for given answer ID
function incrementViewCount ($pk_answer) {
	$con = __openDB();

	$sql = "UPDATE views SET viewcount=viewcount+1 WHERE answerid='$pk_answer'";
	if (!pg_query($con, $sql)) { die('Could not increment view count in database: ' . pg_last_error()); }
	
	__closeDB($con);
}

// Update approved answer in answer database
function updateAnswer ($pk_answer, $s_answer, $pk_mood, $s_quote, $i_rating, $s_citationName="", $s_citationUrl="", $s_audio="") {
	$con = __openDB();
	
	$a_answer = __getAnswer ($con, $pk_answer);
	$s_answerPrevious = $a_answer["answer"];
	if ($a_answer["citation_name"]) { $s_answerPrevious .= "\n\n".$a_answer["citation_name"]; }
	if ($a_answer["citation_url"]) { $s_answerPrevious .= "\n".$a_answer["citation_url"]; }
	if ($a_answer["audio"]) { $s_answerPrevious .= "\n".$a_answer["audio"]; }

	$sql = "UPDATE answers SET answer='".__dbClean($s_answer, $con)."', answer_previous='".__dbClean($s_answerPrevious, $con)."', moodid='$pk_mood', quote='".__dbClean($s_quote, $con)."', rating='".__dbClean($i_rating, $con)."', citation_name='".__dbClean($s_citationName, $con)."', citation_url='".__dbClean($s_citationUrl, $con)."', audio='".__dbClean($s_audio, $con)."' WHERE id='$pk_answer'";
	if (!pg_query($con, $sql)) { die('Could not update answer in database: ' . pg_last_error()); }
	
	__closeDB($con);
}

// Update approved topic in topic database
function updateTopic ($pk_topic, $pk_category, $s_topic) {
	$con = __openDB();

	$sql = "UPDATE topics SET categoryid='$pk_category', topic='".__dbClean($s_topic, $con)."', topic_url='" . stringToUrl(__dbClean($s_topic, $con)) ."' WHERE id='$pk_topic'";
	if (!pg_query($con, $sql)) { die('Could not update topic in database: ' . pg_last_error()); }

	__closeDB($con);
}

?>