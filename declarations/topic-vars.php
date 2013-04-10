<?php 
$s_topicHeader_TL = "";
$s_urlVar_TL = getUrl();
$a_topics_TL = array();
$i_selectedTopicLcv_TL = 0;
$i_selectedTopicId_TL = 0;
$i_maxTopicsDisplayed = 19;

if (isset($_REQUEST["ga"])) {
	$s_topicHeader_TL = "Select a Topic";
	$s_urlVar_TL .= "give-your-answer/";
	$a_topics_TL = $_SESSION['a_approvedTopics'];
	$i_selectedTopicLcv_TL = $_SESSION['a_selectedApprovedTopic']['lcv'];
	$i_selectedTopicId_TL = $_SESSION['a_selectedApprovedTopic']['id'];
} elseif (isset($_REQUEST["af"])) {
	$s_topicHeader_TL = "Select a Topic";
	$s_urlVar_TL .= "ask-a-friend/";
	$a_topics_TL = $_SESSION['a_approvedTopics'];
	$i_selectedTopicLcv_TL = $_SESSION['a_selectedApprovedTopic']['lcv'];
	$i_selectedTopicId_TL = $_SESSION['a_selectedApprovedTopic']['id'];
} elseif (isset($_REQUEST["st"])) {
	$s_topicHeader_TL = "All Approved Topics";
	$s_urlVar_TL .= "suggest-a-topic/";
	$a_topics_TL = $_SESSION['a_approvedTopics'];
	$i_selectedTopicLcv_TL = $_SESSION['a_selectedApprovedTopic']['lcv'];
	$i_selectedTopicId_TL = $_SESSION['a_selectedApprovedTopic']['id'];
} elseif (isset($_REQUEST["itv"])) {
	$s_topicHeader_TL = "Select a Topic";
	$s_urlVar_TL .= "interview-confirmation/";
	$a_topics_TL = $_SESSION['a_approvedTopics'];
	$i_selectedTopicLcv_TL = $_SESSION['a_selectedApprovedTopic']['lcv'];
	$i_selectedTopicId_TL = $_SESSION['a_selectedApprovedTopic']['id'];
} elseif (isset($_REQUEST["adm-at"])) {
	$s_topicHeader_TL = "All Unapproved Topics";
	$s_urlVar_TL .= "admin/approve-topic/";
	$a_topics_TL = $_SESSION['a_unapprovedTopics'];
	$i_selectedTopicLcv_TL = $_SESSION['a_selectedUnapprovedTopic']['lcv'];
	$i_selectedTopicId_TL = $_SESSION['a_selectedUnapprovedTopic']['id'];
} elseif (isset($_REQUEST["adm-aa"])) {
	$s_topicHeader_TL = "Topics w/ Pending Answers";
	$s_urlVar_TL .= "admin/approve-answer/";
	$a_topics_TL = $_SESSION['a_topicsWithUnapprovedAnswers'];
	$i_selectedTopicLcv_TL = $_SESSION['a_selectedTopicWithUnapprovedAnswers']['lcv'];
	$i_selectedTopicId_TL = $_SESSION['a_selectedTopicWithUnapprovedAnswers']['id'];
} elseif (isset($_REQUEST["adm-ut"])) {
	$s_topicHeader_TL = "All Approved Topics";
	$s_urlVar_TL .= "admin/update-topic/";
	$a_topics_TL = $_SESSION['a_approvedTopics'];
	$i_selectedTopicLcv_TL = $_SESSION['a_selectedApprovedTopic']['lcv'];
	$i_selectedTopicId_TL = $_SESSION['a_selectedApprovedTopic']['id'];
} elseif (isset($_REQUEST["adm-ua"])) {
	$s_topicHeader_TL = "Topics w/ Approved Answers";
	$s_urlVar_TL .= "admin/update-answer/";
	$a_topics_TL = $_SESSION['a_answeredTopics'];
	$i_selectedTopicLcv_TL = $_SESSION['a_selectedAnsweredTopic']['lcv'];
	$i_selectedTopicId_TL = $_SESSION['a_selectedAnsweredTopic']['id'];
}
$s_topicHeader_TL = htmlClean ($s_topicHeader_TL);
?>