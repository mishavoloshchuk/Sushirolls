<?php require($_SERVER['DOCUMENT_ROOT']."/db/config.php");
	$data = $_GET;
	$typeName = $data['type'];
	$categoryName = $data['category'];

	$response = array("status" => "init", "error" => false);

	// Check type
	$typeId = $cnct->query("SELECT * FROM product_types WHERE typeName = '$typeName'")->fetch_assoc()['id'];
	if (!$typeId) { $responce['error'] = 'type_does_not_exists'; }

	// // Check category
	// $categoryId = $cnct->query("SELECT * FROM product_categories WHERE categoryName = '$categoryName'")->fetch_assoc()['id'];
	// if (!$categoryId) { $responce['error'] = 'category_does_not_exists'; }

	if (!$responce['error']) {
		// echo "$data . $typeName . $categoryName |";
		$sql = "SELECT products.*, product_categories.categoryName, product_types.typeName FROM products 
		INNER JOIN product_types ON products.typeId = product_types.id
		INNER JOIN product_categories ON products.categoryId = product_categories.id
		WHERE products.typeId = $typeId
		ORDER BY categoryName
		";
		$query_result = $cnct->query($sql);
		while ($query_result && $product = $query_result->fetch_assoc()) {
			$responce['products'][] = $product;
		}
		$responce['status'] = 'success';

	}

	header('Content-Type: application/json');

	echo json_encode($responce);
?>