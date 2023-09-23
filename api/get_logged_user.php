<?php require($_SERVER['DOCUMENT_ROOT']."/db/config.php");
	$request = array();
	if ($loggedUser){
		$request = $loggedUser;
		unset($request['password']);
	}
	echo(json_encode($request));
?>