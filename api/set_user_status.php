<?php require_once($_SERVER['DOCUMENT_ROOT'].'/db/config.php'); 
	$response = array("status" => "error", "error" => false);
	$userEmail = $_GET['userEmail'];
	$statusIdGet = $_GET['statusId'];
	if ($loggedUser && $loggedUser['status'] == 2){ // If logged user is admin

		$targetUser = $cnct->query("SELECT * FROM users WHERE mail = '$userEmail'")->fetch_assoc();
		if ($targetUser){
			if ($targetUser['status'] == 2){ // If admin
				$response['error'] = 'the_administrator_status_cannot_be_changed';
			} else {
				// Check email
				if (!trim($userEmail)){ $response['error'] = "email_is_missing"; }

				// Check status
				if (!trim($statusIdGet) && !$cnct->query("SELECT * FROM user_status WHERE id = $statusIdGet")->fetch_assoc()) {
					$response['error'] = "wrong_status";
				}

				if (!$response['error']){
					$targetUserId = $targetUser['id'];

					$cnct->query("UPDATE users SET status = $statusIdGet WHERE users.id = $targetUserId");

					$response['affected_rows'] = $cnct->affected_rows;
					$response['info'] = $statusIdGet;
					$response['status'] = 'success';
				}
			}
		} else {	
			$response['error'] = 'user_with_given_email_is_not_exist';
		}
	} else {
		$response['error'] = 'no_permission';		
	}	
	
	echo(json_encode($response));
?>