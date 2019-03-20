<?php

/* URL LEGEND
 *
 * "t"       -- topic ID
 * "a"       -- answer ID
 * "u"       -- topic URL
 * "at"      -- launch approve topics page
 * "aa"      -- launch approve answers page
 * "ut"      -- launch update topics page
 * "ua"      -- launch update answers page
 * "s-..."   -- designated as a submitted page
 * "adm-..." -- designated as an admin page
 *
 **************************************************/

// If user is on home page for the first time
if (empty($_GET)) {
	$_REQUEST["adm-at"] = 1;

	// When the admin first goes to the Admin site, all data should be reset
	
	// Retrieve list of unapproved topics from the database (arranged in database alphabetically)
	$_SESSION['a_unapprovedTopics'] = getUnapprovedTopics();
	$_SESSION['i_numUnapprovedTopics'] = count($_SESSION['a_unapprovedTopics']); 
		
	// Default selected topic to first one in list
	$_SESSION['a_selectedUnapprovedTopic']['lcv'] = 0;
	$_SESSION['a_selectedUnapprovedTopic']['id'] = $_SESSION['a_unapprovedTopics'][0]['id'];
	
	// Retrieve list of topics with unapproved answers from the database
	$_SESSION['a_topicsWithUnapprovedAnswers'] = getTopicsWithUnapprovedAnswers();
	$_SESSION['i_numTopicsWithUnapprovedAnswers']=count($_SESSION['a_topicsWithUnapprovedAnswers']); 
	$_SESSION['a_selectedTopicWithUnapprovedAnswers']['lcv'] = 0;
	$_SESSION['a_selectedTopicWithUnapprovedAnswers']['id'] = $_SESSION['a_topicsWithUnapprovedAnswers'][0]['id'];

	// Retrieve list of unapproved answers for the selected topic
	$_SESSION['a_unapprovedAnswers'] = getUnapprovedAnswers($_SESSION['a_selectedTopicWithUnapprovedAnswers']['id']);
	$_SESSION['i_numUnapprovedAnswers']=count($_SESSION['a_unapprovedAnswers']); 
	
	// Default selected answer to the first one in list (sorted by most recent create date first)
	$_SESSION['a_selectedUnapprovedAnswer']['lcv']= 0;
	$_SESSION['a_selectedUnapprovedAnswer']['id']= $_SESSION['a_unapprovedAnswers'][0]['id'];
}

// If form in Approve Topics page is submitted
if (isset($_REQUEST["s-adm-at"])) {

	// If topic is being approved
	if ($_REQUEST["submit_button"] == "Approve") {

		// Approve topic (function approveTopic will email user if they asked to be notified upon approval)
		approveTopic ($_SESSION['a_selectedUnapprovedTopic']['id'], $_REQUEST["category_id"], $_REQUEST["new_topic"]);

		// Now that there are new approved topics, update the approved topics list
		$_SESSION['a_approvedTopics'] = getApprovedTopics();
		$_SESSION['i_numApprovedTopics'] = count($_SESSION['a_approvedTopics']); 
					
		// Set selected topic to first one in the list
		$_SESSION['a_selectedApprovedTopic']['lcv'] = 0;
		$_SESSION['a_selectedApprovedTopic']['id'] = $_SESSION['a_approvedTopics'][0]['id'];

	// Else if topic is being denied
	} elseif ($_REQUEST["submit_button"] == "Deny") {

		// Deny topic
		denyTopic ($_SESSION['a_selectedUnapprovedTopic']['id']);
	}

	$_REQUEST["adm-at"] = 1;

	// reload unapproved topics list
	$_SESSION['a_unapprovedTopics'] = getUnapprovedTopics();
	$_SESSION['i_numUnapprovedTopics'] = count($_SESSION['a_unapprovedTopics']); 	

	// If selected topic is not the last topic in the list, then select the topic that is in the same place in the list (e.g. if 3rd topic was selected, select topic that is now in the 3rd position)
	if ($_SESSION['a_selectedUnapprovedTopic']['lcv'] < $_SESSION['i_numUnapprovedTopics']) {
		$_REQUEST["t"] = $_SESSION['a_unapprovedTopics'][$_SESSION['a_selectedUnapprovedTopic']['lcv']]['id'];
		
	// If the topic approved/denied was not the last topic in the list
	} else if ($_SESSION['i_numUnapprovedTopics']>0) {
		$_REQUEST["t"] = $_SESSION['a_unapprovedTopics'][$_SESSION['a_selectedUnapprovedTopic']['lcv']-1]['id'];
	
	// No topics left to approve
	} else {
		// So shift over to update topics
		unset ($_REQUEST["adm-at"]);
		$_REQUEST["adm-ut"] = 1;
	}
}

// If form in Update Topics page is submitted
if (isset($_REQUEST["s-adm-ut"])) {

	// If topic is being update
	if ($_REQUEST["submit_button"] == "Update") {

		// Update topic
		updateTopic ($_SESSION['a_selectedApprovedTopic']['id'], $_REQUEST["category_id"], $_REQUEST["new_topic"]);

	// Else if topic are being remove
	} elseif ($_REQUEST["submit_button"] == "Remove") {

		// Deny topic (neither topic, nor answers will be deleted from the database, but future updates will need to be done directly in database)
		denyTopic ($_SESSION['a_selectedApprovedTopic']['id']);
	}

	// Relaunch update topic window
	$_REQUEST["adm-ut"] = 1;

	// reload approved topics list
	$_SESSION['a_approvedTopics'] = getApprovedTopics();
	$_SESSION['i_numApprovedTopics'] = count($_SESSION['a_approvedTopics']); 	

	// Move the topic selector forward one place if updating topic that is not the last in the approved topic list
	if (($_REQUEST["submit_button"] == "Update") && ($_SESSION['a_selectedApprovedTopic']['lcv'] != $_SESSION['i_numApprovedTopics']-1)) {
		$_REQUEST["t"] = $_SESSION['a_approvedTopics'][$_SESSION['a_selectedApprovedTopic']['lcv']+1]['id'];
		
	// Keep the topic selector in place if removing a topic that is not the last topic in the approved topic list
	} else if ($_SESSION['a_selectedApprovedTopic']['lcv'] != $_SESSION['i_numApprovedTopics']-1) {
		$_REQUEST["t"] = $_SESSION['a_approvedTopics'][$_SESSION['a_selectedApprovedTopic']['lcv']]['id'];

	// Move the topic selector back one if updating or removing the last topic in the approved topic list
	} else {
		$_REQUEST["t"] = $_SESSION['a_approvedTopics'][$_SESSION['a_selectedApprovedTopic']['lcv']-1]['id'];
	}
	
	// Reload answered topics' list from database (there is IF/THEN check when going to homepage that will reload topic list)
	unset($_SESSION['a_answeredTopics']);

	// Set Open Graph meta tags	
	$_SESSION["meta-description"] = "Admin section: Update Topics";
	$_SESSION["meta-title"] = "Update Topics - Too Polite To Ask";
}

// If form in Approve Answers page is submitted
if (isset($_REQUEST["s-adm-aa"])) {

	// If answer is being approved
	if ($_REQUEST["submit_button"] == "Approve") {

		if ($_REQUEST["citation_name"]==$s_citationName_FM) { $_REQUEST["citation_name"]=""; }
		if ($_REQUEST["citation_url"]==$s_citationUrl_FM) { $_REQUEST["citation_url"]=""; }
		if ($_REQUEST["audio"]==$s_audio_FM) { $_REQUEST["audio"]=""; }
	
		// Approve Answer
		approveAnswer ($_SESSION['a_selectedUnapprovedAnswer']['id'], $_REQUEST["mood_id"], $_REQUEST["quote"], $_REQUEST["rating"]);
		
		// Update answer in database
		updateAnswer ($_SESSION['a_selectedUnapprovedAnswer']['id'], $_REQUEST["answer_text"], $_REQUEST["mood_id"], $_REQUEST["quote"], $_REQUEST["rating"], $_REQUEST["citation_name"], $_REQUEST["citation_url"], $_REQUEST["audio"]);

		// Variable that represents whether the topic previously had any approved answers
		$b_didTopicPreviouslyHaveApprovedAnswers = FALSE;

		// Cycle through all answered topics
		foreach ($_SESSION['a_answeredTopics'] as $lcv => $a_answeredTopic) {
		
			// Check to see if topic that answer was just approved in, had other answers that were previously approved
			if ($a_answeredTopic['id'] == $_SESSION['a_selectedTopicWithUnapprovedAnswers']['id']) {
				$b_didTopicPreviouslyHaveApprovedAnswers = TRUE;				
			}
		}

		// If the topic previously did not have any approved answers
		if (!$b_didTopicPreviouslyHaveApprovedAnswers) {
		
			// Reload answered topics array from database so that it includes topic with newly approved answer
			$_SESSION['a_answeredTopics'] = getAnsweredTopics ();
			$_SESSION['i_numAnsweredTopics']=count($_SESSION['a_answeredTopics']); 
			
			// Shuffle topics to be consistent with the homepage design
			shuffle($_SESSION['a_answeredTopics']);
		}

		// Select topic with newly approved answer in answered topic list
		$_SESSION['a_selectedAnsweredTopic']['id']=$_SESSION['a_selectedTopicWithUnapprovedAnswers']['id'];
		foreach ($_SESSION['a_answeredTopics'] as $i_count => $a_answeredTopic) {
			if ($a_answeredTopic['id'] == $_SESSION['a_selectedAnsweredTopic']['id']){
				$_SESSION['a_selectedAnsweredTopic']['lcv'] = $i_count;
			}
		}
		
		// Reload approved answers for topic from database
		$_SESSION['a_approvedAnswers'] = getApprovedAnswers($_SESSION['a_selectedAnsweredTopic']['id']);
		$_SESSION['i_numApprovedAnswers'] = count($_SESSION['a_approvedAnswers']);
	
	// If answer is being denied
	} elseif ($_REQUEST["submit_button"] == "Deny") {
		denyAnswer ($_SESSION['a_selectedUnapprovedAnswer']['id']);
	} 

	// Relaunch answer approve window
	$_REQUEST["adm-aa"] = 1;
	
	// if there are still unapproved answers for selected topic
	if (count($_SESSION['a_unapprovedAnswers'])>1) {
	
		// update unapproved answers for that topic so that it no longer includes the answer that was just approved or denied
		$_SESSION['a_unapprovedAnswers'] = getUnapprovedAnswers($_SESSION['a_selectedTopicWithUnapprovedAnswers']['id']);
		$_SESSION['i_numUnapprovedAnswers'] = count($_SESSION['a_unapprovedAnswers']);
		
		// reset selected unapproved answer to 0
		$_SESSION['a_selectedUnapprovedAnswer']['lcv']= 0;
		$_SESSION['a_selectedUnapprovedAnswer']['id']= $_SESSION['a_unapprovedAnswers'][0]['id'];		
		
	// if the answer that was just approved or denied was the only unapproved answer in the topic
	} else {
	
		// update list of topics with unapproved answers so that it no longer includes the topic of the answer that was just approved or denied
		$_SESSION['a_topicsWithUnapprovedAnswers'] = getTopicsWithUnapprovedAnswers();
		$_SESSION['i_numTopicsWithUnapprovedAnswers'] = count($_SESSION['a_topicsWithUnapprovedAnswers']); 

		// If selected topic is not the last topic in the list, then select the topic that is in the same place in the list (e.g. if 3rd topic was selected, select topic that is now in the 3rd position)
		if ($_SESSION['a_selectedTopicWithUnapprovedAnswers']['lcv'] < $_SESSION['i_numTopicsWithUnapprovedAnswers']) {
			$_REQUEST["t"] = $_SESSION['a_topicsWithUnapprovedAnswers'][$_SESSION['a_selectedTopicWithUnapprovedAnswers']['lcv']]['id'];							 
			
		// If the answer approved/denied was not from the last topic in the list
		} else if ($_SESSION['i_numTopicsWithUnapprovedAnswers']>0) {
			$_REQUEST["t"] = $_SESSION['a_topicsWithUnapprovedAnswers'][$_SESSION['a_selectedTopicWithUnapprovedAnswers']['lcv']-1]['id'];
		
		// No topics left to approve
		} else {
			// So shift over to update topics
			unset ($_REQUEST["adm-aa"]);
			$_REQUEST["adm-ua"] = 1;
		}
	}

	// Set Open Graph meta tags	
	$_SESSION["meta-description"] = "Admin section: Approve Answers";
	$_SESSION["meta-title"] = "Approve Answers - Too Polite To Ask";
}

// If form in Update Answers page is submitted
if (isset($_REQUEST["s-adm-ua"])) {

	// If the answer is being updated
	if ($_REQUEST["submit_button"] == "Update") {

		if ($_REQUEST["citation_name"]==$s_citationName_FM) { $_REQUEST["citation_name"]=""; }
		if ($_REQUEST["citation_url"]==$s_citationUrl_FM) { $_REQUEST["citation_url"]=""; }
		if ($_REQUEST["audio"]==$s_audio_FM) { $_REQUEST["audio"]=""; }
		
		// Update answer in database
		updateAnswer ($_SESSION['a_selectedApprovedAnswer']['id'], $_REQUEST["answer_text"], $_REQUEST["mood_id"], $_REQUEST["quote"], $_REQUEST["rating"], $_REQUEST["citation_name"], $_REQUEST["citation_url"], $_REQUEST["audio"]);

		// If topic has more approved answers and the answer that was just updated is not the last answer of the topic
		if ($_SESSION['i_numApprovedAnswers']>$_SESSION['a_selectedApprovedAnswer']['lcv']+1) {
		
			// Set the selected answer to the next answer in the answer list
			$_SESSION['a_selectedApprovedAnswer']['id'] = $_SESSION['a_approvedAnswers'][($_SESSION['a_selectedApprovedAnswer']['lcv']+1)]['id'];
			$_SESSION['a_approvedAnswers'] = getApprovedAnswers($_SESSION['a_selectedAnsweredTopic']['id']);
			$_SESSION['i_numApprovedAnswers'] = count($_SESSION['a_approvedAnswers']);
			
			// Set the selected answer lcv to correspond to the answer id just selected
			foreach ($_SESSION['a_approvedAnswers'] as $i_count => $a_approvedAnswer) {
				if ($a_approvedAnswer['id'] == $_SESSION['a_selectedApprovedAnswer']['id']){
					$_SESSION['a_selectedApprovedAnswer']['lcv'] = $i_count;
				}
			}
			
		// If topic is the last answer in the answer list
		} else {
		
			// If the topic selected is not the last topic in the topic list
			if ($_SESSION['i_numAnsweredTopics']>$_SESSION['a_selectedAnsweredTopic']['lcv']+1) {

				// Set the selected topic to the next topic in the topic list
				$_SESSION['a_selectedAnsweredTopic']['lcv'] = $_SESSION['a_selectedAnsweredTopic']['lcv'] + 1;

			// If the topic is the last topic in the topic list
			} else {

				// Set the selected topic to the next topic in the topic list
				$_SESSION['a_selectedAnsweredTopic']['lcv'] = $_SESSION['a_selectedAnsweredTopic']['lcv'] - 1;
			
			}
			
			// Update the topic ID to correspond to the topic LCV that was just selected
			$_SESSION['a_selectedAnsweredTopic']['id'] = $_SESSION['a_answeredTopics'][$_SESSION['a_selectedAnsweredTopic']['lcv']]['id'];
			
			// Refresh answer list for newly selected topic
			$_SESSION['a_approvedAnswers'] = getApprovedAnswers($_SESSION['a_selectedAnsweredTopic']['id']);
			$_SESSION['i_numApprovedAnswers'] = count($_SESSION['a_approvedAnswers']);
			
			// Default selected answer to first answer in answer list
			$_SESSION['a_selectedApprovedAnswer']['lcv'] = 0;
		}

	// If the answer is being removed
	} else if ($_REQUEST["submit_button"] == "Remove") {
	
		// Deny answer in the database (answers are never deleted)
		denyAnswer ($_SESSION['a_selectedApprovedAnswer']['id']);
		
		// If there is at least one more answer in the answer list
		if ($_SESSION['i_numApprovedAnswers']>1) {
					
			// Refresh the answer list from the database
			$_SESSION['a_approvedAnswers'] = getApprovedAnswers($_SESSION['a_selectedAnsweredTopic']['id']);
			$_SESSION['i_numApprovedAnswers'] = count($_SESSION['a_approvedAnswers']);				
			
			// If the answer was the last answer in the previous answer list
			if ($_SESSION['i_numApprovedAnswers']<=$_SESSION['a_selectedApprovedAnswer']['lcv']) {
				
				// Select the answer immediately preceding the answer removed
				$_SESSION['a_selectedApprovedAnswer']['lcv'] = $_SESSION['a_selectedApprovedAnswer']['lcv'] - 1;	
			}	
			
		// If the answer removed was the only answer in the answer list
		} else {
			
			// Refresh the topic list from the database
			$_SESSION['a_answeredTopics'] = getAnsweredTopics();
			$_SESSION['i_numAnsweredTopics'] = count($_SESSION['a_answeredTopics']);
			
			// Shuffle topics to be consistent with the homepage design
			shuffle($_SESSION['a_answeredTopics']);
		
			// If the topic was the last topic in the previous topic list
			if ($_SESSION['a_selectedAnsweredTopic']['lcv']+1>$_SESSION['i_numAnsweredTopics']) {
			
				// Select the topic immediately preceding the topic removed
				$_SESSION['a_selectedAnsweredTopic']['lcv'] = $_SESSION['a_selectedAnsweredTopic']['lcv'] - 1;	
			}
			
			// Update the topic ID to correspond to the topic LCV of the updated topic list
			$_SESSION['a_selectedAnsweredTopic']['id'] = $_SESSION['a_answeredTopics'][$_SESSION['a_selectedAnsweredTopic']['lcv']]['id'];
			
			// Refresh answer list for newly selected topic
			$_SESSION['a_approvedAnswers'] = getApprovedAnswers($_SESSION['a_selectedAnsweredTopic']['id']);
			$_SESSION['i_numApprovedAnswers'] = count($_SESSION['a_approvedAnswers']);
			
			// Default selected answer to first answer in answer list
			$_SESSION['a_selectedApprovedAnswer']['lcv'] = 0;			
		}
	}

	// Update the answer ID to correspond to the answer LCV of the updated answer list
	$_SESSION['a_selectedApprovedAnswer']['id'] = $_SESSION['a_approvedAnswers'][$_SESSION['a_selectedApprovedAnswer']['lcv']]['id'];	
	
	// Relaunch update approve window
	$_REQUEST["adm-ua"] = 1;
	$_REQUEST["t"] = $_SESSION['a_selectedAnsweredTopic']['id'];

	// Set Open Graph meta tags	
	$_SESSION["meta-description"] = "Admin section: Update Answer";
	$_SESSION["meta-title"] = "Update Answers - Too Polite To Ask";
}

// If an admin wants to approve topics
if (isset($_REQUEST["adm-at"])) {
	
	// If this is the first time the user or admin has loaded the list of unapproved topics
	if(!isset($_SESSION['a_unapprovedTopics'])) {

		// Retrieve list of unapproved topics from the database (arranged in database alphabetically)
		$_SESSION['a_unapprovedTopics'] = getUnapprovedTopics();
		$_SESSION['i_numUnapprovedTopics'] = count($_SESSION['a_unapprovedTopics']); 
			
		// Default selected topic to first one in list
		$_SESSION['a_selectedUnapprovedTopic']['lcv'] = 0;
		$_SESSION['a_selectedUnapprovedTopic']['id'] = $_SESSION['a_unapprovedTopics'][0]['id'];
	}
	
	// if admin wants to approve a particular topic
	if (isset($_REQUEST["t"])) {

		// Set topic id to value passed in URL
		$_SESSION['a_selectedUnapprovedTopic']['id']= $_REQUEST["t"];
		
		// Set the selected topic lcv to correspond to the topic id passed in URL
		foreach ($_SESSION['a_unapprovedTopics'] as $i_count => $a_unapprovedTopic) {
			if ($a_unapprovedTopic['id'] == $_SESSION['a_selectedUnapprovedTopic']['id']){
				$_SESSION['a_selectedUnapprovedTopic']['lcv'] = $i_count;
			}
		}
	}

	// If this is the first time the admin has loaded the list of categories
	if(!isset($_SESSION['a_categories'])) {
		$_SESSION['a_categories'] = getCategories();
		$_SESSION['i_numCategories'] = count($_SESSION['a_categories']);
	}
	$_SESSION['a_selectedCategory']['lcv'] = 0;
	$_SESSION['a_selectedCategory']['id'] = $_SESSION['a_categories'][0]['id'];

	// Set Open Graph meta tags	
	$_SESSION["meta-description"] = "Admin section: Approve Topics";
	$_SESSION["meta-title"] = "Approve Topics - Too Polite To Ask";
}

// If an admin wants to update topics
if (isset($_REQUEST["adm-ut"])) {
	
	// If this is the first time the user or admin has loaded the list of approved topics
	if(!isset($_SESSION['a_approvedTopics'])) {
	
		// Retrieve list of approved topics from the database (arranged in database alphabetically)
		$_SESSION['a_approvedTopics'] = getApprovedTopics();
		$_SESSION['i_numApprovedTopics'] = count($_SESSION['a_approvedTopics']); 
		
		// Default selected topic to first one in list
		$_SESSION['a_selectedApprovedTopic']['lcv'] = 0;
		$_SESSION['a_selectedApprovedTopic']['id'] = $_SESSION['a_approvedTopics'][0]['id'];
	}
	
	// if admin wants to update a particular topic
	if (isset($_REQUEST["t"])) {
	
		// Set topic id to value passed in URL
		$_SESSION['a_selectedApprovedTopic']['id']= $_REQUEST["t"];
		
		// Set the selected topic lcv to correspond to the topic id passed in URL
		foreach ($_SESSION['a_approvedTopics'] as $i_count => $a_approvedTopic) {
			if ($a_approvedTopic['id'] == $_SESSION['a_selectedApprovedTopic']['id']){
				$_SESSION['a_selectedApprovedTopic']['lcv'] = $i_count;
			}
		}
	}

	// If this is the first time the admin has loaded the list of categories
	if(!isset($_SESSION['a_categories'])) {
		$_SESSION['a_categories'] = getCategories();
		$_SESSION['i_numCategories'] = count($_SESSION['a_categories']);
	}	
	
	// Select the previously selected category
	$_SESSION['a_selectedCategory']['id'] = $_SESSION['a_approvedTopics'][$_SESSION['a_selectedApprovedTopic']['lcv']]['categoryId'];

	// Set the selected category lcv to correspond to the category id associated with topic
	foreach ($_SESSION['a_categories'] as $i_count => $a_category) {
		if ($a_category['id'] == $_SESSION['a_selectedCategory']['id']){
			$_SESSION['a_selectedCategory']['lcv'] = $i_count;	
		}
	}
	
	// Set Open Graph meta tags	
	$_SESSION["meta-description"] = "Admin section: Update Topics";
	$_SESSION["meta-title"] = "Update Topics - Too Polite To Ask";
}

// If admin wants to approve answers
if (isset($_REQUEST["adm-aa"])) {

	// If this is the first time the admin has loaded the list of topics with unapproved answers
	if(!isset($_SESSION['a_topicsWithUnapprovedAnswers'])) {
	
		// Retrieve list of topics with unapproved answers from the database
		$_SESSION['a_topicsWithUnapprovedAnswers'] = getTopicsWithUnapprovedAnswers();
		$_SESSION['i_numTopicsWithUnapprovedAnswers']=count($_SESSION['a_topicsWithUnapprovedAnswers']); 
		$_SESSION['a_selectedTopicWithUnapprovedAnswers']['lcv'] = 0;
		$_SESSION['a_selectedTopicWithUnapprovedAnswers']['id'] = $_SESSION['a_topicsWithUnapprovedAnswers'][0]['id'];

		// Retrieve list of unapproved answers for the selected topic
		$_SESSION['a_unapprovedAnswers'] = getUnapprovedAnswers($_SESSION['a_selectedTopicWithUnapprovedAnswers']['id']);
		$_SESSION['i_numUnapprovedAnswers']=count($_SESSION['a_unapprovedAnswers']); 
		
		// Default selected answer to the first one in list (sorted by most recent create date first)
		$_SESSION['a_selectedUnapprovedAnswer']['lcv']= 0;
		$_SESSION['a_selectedUnapprovedAnswer']['id']= $_SESSION['a_unapprovedAnswers'][0]['id'];
	}

	// If this is the first time the admin has loaded the list of moods
	if(!isset($_SESSION['a_moods'])) {
		$_SESSION['a_moods'] = getMoods();
		$_SESSION['i_numMoods'] = count($_SESSION['a_moods']);
	}
	$_SESSION['a_selectedMood']['lcv'] = 0;
	$_SESSION['a_selectedMood']['id'] = $_SESSION['a_moods'][0]['id'];
	
	// If the admin is selecting a new topic id
	if (isset($_REQUEST["t"])) {
	
		// If the admin is selecting a new topic from the one previously viewed
		if ($_SESSION['a_selectedTopicWithUnapprovedAnswers']['id'] != $_REQUEST["t"]) {

			// Set selected topic to value passed in URL
			$_SESSION['a_selectedTopicWithUnapprovedAnswers']['id']= $_REQUEST["t"];
			
			// Set the selected topic lcv to correspond to the topic id chosen
			foreach ($_SESSION['a_topicsWithUnapprovedAnswers'] as $i_count => $a_topicWithUnapprovedAnswer) {
				if ($a_topicWithUnapprovedAnswer['id'] == $_SESSION['a_selectedTopicWithUnapprovedAnswers']['id']){
					$_SESSION['a_selectedTopicWithUnapprovedAnswers']['lcv'] = $i_count;
				}
			}

			// Retrieve new list of unapproved answers for the selected topic
			$_SESSION['a_unapprovedAnswers'] = getUnapprovedAnswers($_SESSION['a_selectedTopicWithUnapprovedAnswers']['id']);
			$_SESSION['i_numUnapprovedAnswers']=count($_SESSION['a_unapprovedAnswers']); 
			
			// Default selected answer to the first one in list (sorted by most recent create date first)
			$_SESSION['a_selectedUnapprovedAnswer']['lcv']= 0;
			$_SESSION['a_selectedUnapprovedAnswer']['id']= $_SESSION['a_unapprovedAnswers'][0]['id'];		
		}
	}
		
	// If admin is selecting a new unapproved answer within the same topic
	if (isset($_REQUEST["a"])) {
	
		// Set the answer id to value passed in URL
		$_SESSION['a_selectedUnapprovedAnswer']['id']= $_REQUEST["a"];
		
		// Set the selected answer lcv to correspond to the answer id passed in URL
		foreach ($_SESSION['a_unapprovedAnswers'] as $i_count => $a_unapprovedAnswer) {
			if ($a_unapprovedAnswer['id'] == $_SESSION['a_selectedUnapprovedAnswer']['id']){
				$_SESSION['a_selectedUnapprovedAnswer']['lcv'] = $i_count;
			}
		}
	}

	// Set Open Graph meta tags	
	$_SESSION["meta-description"] = "Admin section: Approve Topics";
	$_SESSION["meta-title"] = "Approve Answers - Too Polite To Ask";
}

// If admin wants to update answers
if (isset($_REQUEST["adm-ua"])) {
	
	// If the admin is selecting a new topic id
	if (isset($_REQUEST["t"])) {
		
		// Check if topic is approved
		if (isTopicApproved($_REQUEST["t"])) {
	
			// If the admin is selecting a new topic from the one previously viewed
			if ($_SESSION['a_selectedAnsweredTopic']['id'] != $_REQUEST["t"]) {

				// Set selected topic to value passed in URL
				$_SESSION['a_selectedAnsweredTopic']['id']= $_REQUEST["t"];
				
				foreach ($_SESSION['a_answeredTopics'] as $i_count => $a_answeredTopic) {
					if ($a_answeredTopic['id'] == $_SESSION['a_selectedAnsweredTopic']['id']){
						$_SESSION['a_selectedAnsweredTopic']['lcv'] = $i_count;
					}
				}

				// Retrieve new list of approved answers for the selected topic
				$_SESSION['a_approvedAnswers'] = getApprovedAnswers($_SESSION['a_selectedAnsweredTopic']['id']);
				$_SESSION['i_numApprovedAnswers'] = count($_SESSION['a_approvedAnswers']);
				
				// Default selected answer to the first one in list (sorted by most recent create date first)
				$_SESSION['a_selectedApprovedAnswer']['lcv']= 0;
				$_SESSION['a_selectedApprovedAnswer']['id']= $_SESSION['a_approvedAnswers'][0]['id'];
			}
		}
	}
	
	// If admin is selecting a new approved answer within the same topic
	if (isset($_REQUEST["a"])) {
	
		// Check if answer is approved
		if (isAnswerApproved($_REQUEST["a"])) {

			// Set the answer id to value passed in URL
			$_SESSION['a_selectedApprovedAnswer']['id']= $_REQUEST["a"];
			
			// Set the selected answer lcv to correspond to the answer id passed in URL
			foreach ($_SESSION['a_approvedAnswers'] as $i_count => $a_approvedAnswer) {
				if ($a_approvedAnswer['id'] == $_SESSION['a_selectedApprovedAnswer']['id']){
					$_SESSION['a_selectedApprovedAnswer']['lcv'] = $i_count;
				}
			}
		}
	}

	// If this is the first time the admin has loaded the list of moods
	if(!isset($_SESSION['a_moods'])) {
		$_SESSION['a_moods'] = getMoods();
		$_SESSION['i_numMoods'] = count($_SESSION['a_moods']);
	}	

	// Select the previously selected mood
	$_SESSION['a_selectedMood']['id'] = $_SESSION['a_approvedAnswers'][$_SESSION['a_selectedApprovedAnswer']['lcv']]['moodId'];

	// Set the selected mood lcv to correspond to the mood id associated with answer
	foreach ($_SESSION['a_moods'] as $i_count => $a_mood) {
		if ($a_mood['id'] == $_SESSION['a_selectedMood']['id']){
			$_SESSION['a_selectedMood']['lcv'] = $i_count;	
		}
	}

	// Set Open Graph meta tags	
	$_SESSION["meta-description"] = "Admin section: Update Answer";
	$_SESSION["meta-title"] = "Update Answers - Too Polite To Ask";
}

?>