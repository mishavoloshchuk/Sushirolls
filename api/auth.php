<?php require($_SERVER['DOCUMENT_ROOT']."/db/config.php");

	$data = $_POST;
	$errors = array();
	$email = $data['email'];
	$password = $data['password'];

	$response = array("status" => "init", "error" => false);

	// Check login
	if (trim($email)==''){ $response['error'] = "email_is_missing"; }

	// Check password
	if ($password == ''){ $response['error'] = "password_is_missing"; }


	if ( $response['error'] == false ){

		$loggedUser = $cnct->query("SELECT users.*, statusName FROM `users` INNER JOIN user_status ON status = user_status.id  WHERE users.mail = '$email'");

		if ($loggedUser){
			$loggedUser = $loggedUser->fetch_assoc();
			if (password_verify($password, $loggedUser['password'])){
				$loggedUserId = $loggedUser['id'];
				$_SESSION['user_id'] = $loggedUser['id'];

				// Set cookie
				if ($data['remember'] == 'true'){
					setcookie('user_id', $loggedUser['id'], [
						'expires' => time()+60*60*24*365,
						'path' => '/',
						'samesite' => 'Lax',
					]);
					$response['info'] = $_COOKIE['user_id'];
				} else {
					$response['info'] = 'cookie was not setted';
				}
				$response['status'] = 'success';
				$response['loggedUser'] = $loggedUser;
				$response['remember'] = $data['remember'];
			} else {
				$response['error'] = 'email_or_password_is_wrong';
			}
		} else {
			$response['error'] = 'email_or_password_is_wrong';
		}
	}
	if ($response['error']) { $response['status'] = 'error'; unset($loggedUser, $loggedUserId); }
	echo json_encode($response);
?>