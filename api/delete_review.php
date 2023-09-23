<?php require_once($_SERVER['DOCUMENT_ROOT'].'/db/config.php'); 
	$response = array("status" => "init", "error" => false);
	if ($loggedUser){
		$cnct->query("DELETE FROM reviews WHERE authorId = $loggedUserId");
		$affectedRows = $cnct->affected_rows;
		$response['affected_rows'] = $affectedRows;
		// Set response status
		if ($affectedRows > 0) {
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