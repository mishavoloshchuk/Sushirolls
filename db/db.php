<?php 
session_start();

$servername = $config['db']['server'];
$username = $config['db']['user'];
$password = $config['db']['password'];

// Create cnctection
$cnct = new mysqli($servername, $username, $password);
// Check cnctection
if ($cnct->cnctect_error) {
	die("cnctection failed: " . $cnct->cnctect_error);
}

$cnct->set_charset("utf8mb4");
$cnct->select_db($config['db']['database_name']);

$loggedUser = array();
$loggedUserId = null;

if ($_COOKIE['user_id']){
	$_SESSION['user_id'] = $_COOKIE['user_id'];
}

if($_SESSION['user_id']){
	$loggedUserId = $_SESSION['user_id'];
	$loggedUser = $cnct->query("SELECT users.*, statusName FROM `users` INNER JOIN user_status ON status = user_status.id  WHERE users.id = '$loggedUserId'")->fetch_assoc();

	// Order change status permissions
	switch ($loggedUser['status']){
		case 1: // Client
			$loggedUser['avlblOrderStatusesToSet'] = [5];
			$loggedUser['canSetStatusIfOrderStatus'] = [1];
			break;
		case 2: // Admin
			$loggedUser['avlblOrderStatusesToSet'] = [1, 6];
			$loggedUser['canSetStatusIfOrderStatus'] = [1, 2, 3, 4];
			break;
		case 3: // Cook
			$loggedUser['avlblOrderStatusesToSet'] = [3, 4, 6];
			$loggedUser['canSetStatusIfOrderStatus'] = [1, 3, 4];
			break;
		case 4: // Courier
			$loggedUser['avlblOrderStatusesToSet'] = [2, 6, 7];
			$loggedUser['canSetStatusIfOrderStatus'] = [2, 3];
			break;		
	}
}

?>