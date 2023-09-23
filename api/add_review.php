<?php 
	require($_SERVER['DOCUMENT_ROOT']."/db/config.php");

	$data = $_POST;

	$mode = $data['modalMode'];
	$author_name = $data['author_name'];
	$rating = $data['rating'];
	$review_text = $data['review_text'];

	$response = array("status" => "init", "error" => false);


	// Check review user name
	if (trim($author_name) == ''){ $response['error'] = "user_name_is_missing"; }

	// Check rating
	else if (!(is_numeric(trim($rating)) && $rating <= 5)){ $response['error'] = "rating_is_missing"; }

	// Check if review is already exists
	if ($mode == 'create' && $cnct->query("SELECT authorId FROM reviews WHERE authorId = $loggedUserId")->fetch_assoc()['authorId']){
		$response['error'] = "review_is_already_exists";
	}

	if (!$response['error']){
		if ($mode == "create"){
			$cnct->query("INSERT INTO reviews (author_name, description, rating, authorId) VALUES ('$author_name', '$review_text', '$rating', '$loggedUserId');");
			$response['status'] = 'success';
		}
		else if ($mode == "edit"){
			$cnct->query("UPDATE reviews SET `author_name` = '$author_name', `description` = '$review_text', `date` = NOW() WHERE authorId = $loggedUserId");
			$response['status'] = 'success';
		}

		if ($cnct->affected_rows != 1){
			$response['status'] = 'error';
			$response['error'] = $cnct->affected_rows."affected_rows";
		}

	} else {
		$response['status'] = 'error';
	}
	echo(json_encode($response));
?>