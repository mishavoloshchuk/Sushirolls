<?php require($_SERVER['DOCUMENT_ROOT']."/db/config.php");
	$data = $_GET;

	$response = array("status" => "init", "error" => false);

	if (!$responce['error']) {
		$responce['product_types'] = $cnct->query("SELECT * FROM product_types")->fetch_all(MYSQLI_ASSOC);
		$responce['product_categories'] = $cnct->query("SELECT * FROM product_categories")->fetch_all(MYSQLI_ASSOC);
		$responce['status'] = 'success';
	}

	header('Content-Type: application/json');

	echo json_encode($responce);
?>