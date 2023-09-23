<?php 
	require($_SERVER['DOCUMENT_ROOT']."/db/config.php");

	$data = $_POST;
	$login = $data['login'];
	$email = $data['email'];
	$password = $data['password'];
	$response = array("status" => "init", "error" => false);

	// Check login
	if (trim($login)==''){ $response['error'] = "login_is_missing"; }

	// Check password
	if ($password == ''){ $response['error'] = "password_is_missing"; }

	// Check email
	if ($email == ''){ $response['error'] = "email_is_missing"; }

	// Check if email address already exists
	if ($cnct->query("SELECT COUNT(*) AS 'mc' FROM `users` WHERE `mail` = '$email'")->fetch_assoc()['mc'] > 0){$response['error'] = "emali_address_is_already_exists";}

	if (!$response['error']){
		$password = password_hash($password, PASSWORD_DEFAULT);
		$cnct->query("INSERT INTO `users` (`login`, `password`, `mail`) VALUES ('$login', '$password', '$email');");
		$response['status'] = 'success';
		$response['login'] = $login;
		$_SESSION['user'] = $login;
	} else {
		$response['status'] = 'error';
	}

	echo(json_encode($response));
?>