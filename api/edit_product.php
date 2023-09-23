<?php 
	require($_SERVER['DOCUMENT_ROOT']."/db/config.php");

	$data = $_POST;
	$title = $data['title'];
	$cost = $data['cost'];
	$category = $data['category'];
	$newCategoryName = $data['newCategoryName'];
	$type = $data['type'];
	$newTypeName = $data['newTypeName'];
	$description = $data['description'];
	$image = $_FILES['image'];

	$response = array("status" => "init", "error" => false);


	// Check title
	if (trim($title) == ''){ $response['error'] = "title_is_missing"; }

	// Check cost
	else if (trim($cost) == ''){ $response['error'] = "cost_is_missing"; }

	// Check description
	else if (trim($description) == ''){ $response['error'] = "description_is_missing"; }

	// Check category
	else if (trim($category) == '' || ($category == 'new_category' && trim($newCategoryName) == '')){ $response['error'] = "category_is_missing"; }

	// Check if category exists
	else if ( $category != 'new_category' 
		&& $cnct->query("SELECT categoryName FROM product_categories WHERE id = '$category'")->fetch_assoc()['categoryName'] == ''
	){ 
		$response['error'] = "category_not_found"; 
	}

	// Check product type
	else if (trim($type) == '' || ($type == 'new_type' && trim($newTypeName) == '')){ $response['error'] = "type_is_missing"; }

	// Check if product type exists
	else if ( $type != 'new_type' 
		&& $cnct->query("SELECT typeName FROM product_types WHERE id = '$type'")->fetch_assoc()['typeName'] == ''
	){ 
		$response['error'] = "type_not_found";
	}

	// Check image
	else if (!($image
		&& $image['error'] == UPLOAD_ERR_OK
		&& @is_array(getimagesize($image['tmp_name'])) 
	)){
		$response['error'] = "image_is_missing";
	}

	if (!$response['error']){
		// Add image
		// Get last product ID
		$lastProductId = $cnct->query("SELECT id FROM products ORDER BY id DESC LIMIT 1")->fetch_assoc()['id'];
		$newProductId = $lastProductId + 1;
		$filename = $newProductId . '_' . $image["name"];
		$imgPath = '../images/product_images/'.$filename;
		// Save upleaded file
		move_uploaded_file($image["tmp_name"], $imgPath);

		// Add new category
		if ($category == 'new_category'){
			$existCategoryId = $cnct->query("SELECT * FROM product_categories WHERE categoryName = '$newCategoryName'")->fetch_assoc()['id'];
			// If category already exists
			if ($existCategoryId) {
				$categoryId = $existCategoryId;
			} else {
				$cnct->query("INSERT INTO product_categories (categoryName) VALUES ('$newCategoryName')");
				$categoryId = $cnct->query("SELECT LAST_INSERT_ID() AS lastId")->fetch_assoc()['lastId'];
			}
		} else {
			$categoryId = $category;
		}
		// Add new product type
		if ($type == 'new_type'){
			$existTypeId = $cnct->query("SELECT * FROM product_types WHERE typeName = '$newTypeName'")->fetch_assoc()['id'];
			// If category already exists
			if ($existTypeId) {
				$typeId = $existTypeId;
			} else {
				$cnct->query("INSERT INTO product_types (typeName) VALUES ('$newTypeName')");
				$typeId = $cnct->query("SELECT LAST_INSERT_ID() AS lastId")->fetch_assoc()['lastId'];
			}
		} else {
			$typeId = $type;
		}
		$cnct->query("INSERT INTO products (`title`, `description`, `cost`, `imgsrc`, `categoryId`, `typeId`) VALUES ('$title', '$description', '$cost', '$imgPath', '$categoryId', '$typeId');");

		$response['status'] = 'success';
	} else {
		$response['status'] = 'error';
	}
	echo(json_encode($response));
?>