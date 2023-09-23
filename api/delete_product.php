<?php require($_SERVER['DOCUMENT_ROOT']."/db/config.php");
	$response = array("status" => "init", "error" => false);
	$productDeleteId = $_GET['deleteId'];
	if ($loggedUser['status'] == 2){ // If user is admin
		$productToDelete = $cnct->query("SELECT * FROM products WHERE id = $productDeleteId")->fetch_assoc();
		$cnct->query("DELETE FROM products WHERE id = $productDeleteId");
		$affectedRows = $cnct->affected_rows;
		$response['affected_rows'] = $affectedRows;
		// Set response status
		if ($affectedRows > 0) {
			unlink($productToDelete['imgsrc']);
			$response['status'] = 'success';
		}
		else if ($affectedRows == 0) {
			$response['status'] = 'error';
			$response['error'] = 'product_was_not_deleted';
		}
		else if ($affectedRows < 0) {
			$response['status'] = 'error';
			$response['error'] = 'request_returned_error';
		}
	} else {
		$response['error'] = 'no_permission';
		$response['status'] = 'error';
	}
	echo(json_encode($response));
?>