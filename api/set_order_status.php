<?php require_once($_SERVER['DOCUMENT_ROOT'].'/db/config.php'); 
	$response = array("status" => "error", "error" => false);
	$orderId = $_GET['orderId'];
	$orderStatusIdGET = $_GET['orderStatusId'];
	$reason = $_GET['reason'];

	$order = $cnct->query("SELECT * FROM orders WHERE id = $orderId")->fetch_assoc();

	if ($order){
		if ($loggedUser){
			$allow_change_status = false;

			$allow_change_status = 
				(in_array($order['statusId'], $loggedUser['canSetStatusIfOrderStatus']) 
				&& in_array($orderStatusIdGET, $loggedUser['avlblOrderStatusesToSet']))
				|| $orderStatusIdGET == 5 && $order['userId'] == $loggedUserId;

			// if ($orderStatusIdGET == 6 && !trim($reason)) {
			// 	$response['error'] = 'reason_is_missing';
			// }
			if (!$response['error'] && $allow_change_status){
				$workerId = "";
				switch($loggedUser['status']){
					case 3: // Cook
						$workerId = ", cookId = $loggedUserId";
						break;
					case 4: // Courier
						$workerId = ", courierId = $loggedUserId";
						break;
				}
				$sql = "UPDATE orders SET statusId = $orderStatusIdGET $workerId WHERE id = $orderId";
				$cnct->query($sql);

				$affectedRows = $cnct->affected_rows;
				$response['affected_rows'] = $affectedRows;
				$response['status'] = 'success';
			} else {
				$response['error'] = 'no_permission';
				$response['status'] = 'error';				
			}

			// // Set response status
			// if ($affectedRows > 0) {
			// 	$response['status'] = 'success';
			// }
			// else if ($affectedRows == 0) {
			// 	$response['error'] = 'order_status_was_not_changed';
			// }
			// else if ($affectedRows < 0) {
			// 	$response['error'] = 'request_returned_error';
			// }
		} else {
			$response['error'] = 'no_permission';		
		}	
	} else {	
		$response['error'] = 'order_with_given_id_is_not_exist';
	}
	
	echo(json_encode($response));
?>