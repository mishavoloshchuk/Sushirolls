<?php require($_SERVER['DOCUMENT_ROOT']."/db/config.php");
	session_destroy();
	unset($_COOKIE['user_id']);
	setcookie('user_id', null, [
		'expires' => time() + 1,
		'path' => '/',
		'samesite' => 'Lax'
	]);
?>