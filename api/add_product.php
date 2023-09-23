<?php 
	require($_SERVER['DOCUMENT_ROOT']."/db/config.php");

	/**
	 * Generate a random string, using a cryptographically secure 
	 * pseudorandom number generator (random_int)
	 *
	 * This function uses type hints now (PHP 7+ only), but it was originally
	 * written for PHP 5 as well.
	 * 
	 * For PHP 7, random_int is a PHP core function
	 * For PHP 5.x, depends on https://github.com/paragonie/random_compat
	 * 
	 * @param int $length      How many characters do we want?
	 * @param string $keyspace A string of all possible characters
	 *                         to select from
	 * @return string
	 */
	function random_str(
		int $length = 64,
		string $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'
	): string {
		if ($length < 1) {
			throw new \RangeException("Length must be a positive integer");
		}
		$pieces = [];
		$max = mb_strlen($keyspace, '8bit') - 1;
		for ($i = 0; $i < $length; ++$i) {
		    $pieces []= $keyspace[random_int(0, $max)];
		}
		return implode('', $pieces);
	}

	$data = $_POST;

	$mode = $data['modalMode'];
	$editProductId = $data['editProductId'];
	$title = $data['title'];
	$cost = $data['cost'];
	$category = $data['category'];
	$newCategoryName = $data['newCategoryName'];
	$type = $data['type'];
	// $newTypeName = $data['newTypeName'];
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

	// // Check product type
	// else if (trim($type) == '' || ($type == 'new_type' && trim($newTypeName) == '')){ $response['error'] = "type_is_missing"; }

	// Check if product type exists
	else if ( $type != 'new_type' 
		&& $cnct->query("SELECT typeName FROM product_types WHERE id = '$type'")->fetch_assoc()['typeName'] == ''
	){ 
		$response['error'] = "type_not_found";
	}

	// Check image
	else if ($mode == 'create'
		&& !( $image
		&& $image['error'] == UPLOAD_ERR_OK
		&& @is_array(getimagesize($image['tmp_name']) ) 
	)){
		$response['error'] = "image_is_missing";
	}

	if (!$response['error']){
		// Add image if creating product or if update image
		if ($mode == 'create' || ($mode == 'edit' && $image)){
			$extension = pathinfo($image["name"], PATHINFO_EXTENSION);
			$filename = random_str(10) . '.' . $extension;
			$imgPath = '../images/product_images/'.$filename;
			while (file_exists($imgPath)) {
				$filename = random_str(10) . '.' . $extension;
				$imgPath = '../images/product_images/'.$filename;
			}
			// Save upleaded file
			move_uploaded_file($image["tmp_name"], $imgPath);
		}

		// Delete image from server if received an image
		if ($mode == 'edit'){
			// Get image src from edit object
			$oldImgsrc = $cnct->query("SELECT imgsrc FROM products WHERE id = '$editProductId'")->fetch_assoc()['imgsrc'];
			if ($image){
				@unlink($oldImgsrc);
			} else {
				$imgPath = $oldImgsrc;
			}
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

		// Add new category
		if ($category == 'new_category'){
			$existCategoryId = $cnct->query("SELECT * FROM product_categories WHERE categoryName = '$newCategoryName' AND typeId = $typeId")->fetch_assoc()['id'];
			// If category already exists
			if ($existCategoryId) {
				$categoryId = $existCategoryId;
			} else {
				$cnct->query("INSERT INTO product_categories (categoryName, typeId) VALUES ('$newCategoryName', $type)");
				$categoryId = $cnct->query("SELECT LAST_INSERT_ID() AS lastId")->fetch_assoc()['lastId'];
			}
		} else {
			$categoryId = $category;
		}

		if ($mode == "create"){
			$cnct->query("INSERT INTO products (`title`, `description`, `cost`, `imgsrc`, `categoryId`, `typeId`) VALUES ('$title', '$description', '$cost', '$imgPath', '$categoryId', '$typeId');");
			$response['status'] = 'success';
		}
		else if ($mode == "edit"){
			$cnct->query("UPDATE products SET `title` = '$title', `description` = '$description', `cost` = '$cost', `categoryId` = '$categoryId', `typeId` = '$typeId', `imgsrc` = '$imgPath' WHERE id = '$editProductId'");
			$response['status'] = 'success';
		}

	} else {
		$response['status'] = 'error';
	}
	echo(json_encode($response));
?>