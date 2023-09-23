<?php require_once($_SERVER['DOCUMENT_ROOT'].'/db/config.php');
	if (isset($_POST['orders'])){
		$name = $_POST['name']; // Ім'я
		$phone = $_POST['phone']; // Телефон
		$sticksNum = $_POST['sticksNum']; // Комплекти паличок
		$educSticksNum = $_POST['educSticksNum']; // Комплекти навчальних паличок
		$peopleNum = $_POST['peopleNum']; // Кількість людей
		$adressHouse = $_POST['adressHouse']; // Квартира або будинок
		$cityName = $_POST['cityName']; // Місто
		$streetName = $_POST['streetName']; // Вулиця
		$buildNum = $_POST['buildNum']; // Номер будинку
		$entranceNumb = $_POST['entranceNumb']; // Номер під'їзду
		$floorNumb = $_POST['floorNumb']; // Поверх
		$apartmentNumb = $_POST['apartmentNumb']; // Номер квартири
		$userMessage = $_POST['userMessage']; // Повідомлення замовника
		$jsonOrders = $_POST['orders']; // JSON Массив з замовленнями
		$userId = $loggedUser['login'] ? $loggedUserId : 'NULL';
		if (!$userMessage){$userMessage = 'Немає';}
		if($name && $phone && is_numeric($sticksNum) && is_numeric($educSticksNum) && is_numeric($peopleNum) && $adressHouse && $cityName && $streetName && $buildNum) { // Якщо всі поля заповнено
			$sql = "INSERT INTO `orders` (`name`, `phone`, `sticksNum`, `educSticksNum`, `peopleNum`, `adressHouse`, `cityName`, `streetName`, `buildNum`, `floorNumb`, `entranceNumb`, `apartmentNumb`, `userMessage`, `jsonOrders`, `userId`) VALUES ('$name', '$phone', '$sticksNum', '$educSticksNum', '$peopleNum', '$adressHouse', '$cityName', '$streetName', '$buildNum', '$entranceNumb', '$floorNumb', '$apartmentNumb', '$userMessage', '$jsonOrders', $userId);";
			$cnct->query($sql);

			function generateLetter($content) {
				$file = file_get_contents('empty_letter.html', true);
				$letter = explode('<!-- \separate -->', $file);
				return $letter[0] . $content . $letter[1];
			}

			$letter_content = " 
			<h1>Дякуємо!</h1>
			<p>Ваше замовлення успішно оформлено!😎</p>
			";

			$headers[] = 'MIME-Version: 1.0';
			$headers[] = 'Content-type: text/html; charset=utf-8';

			// Additional headers
			$headers[] = 'From: Sushirolls <notification@sushirolls.com>';
			$headers[] = 'X-Mailer: PHP/' . phpversion();

			mb_send_mail($loggedUser['mail'], 'Ви успішно оформили замовлення!', generateLetter($letter_content), implode("\r\n", $headers));
			header('location: /thanks_for_order.html');
		} else {
			$error = 'Невірно заповнена форма!';
		}
	}
 ?>
<!DOCTYPE html>
<html lang="ua">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" type="text/css" href="styles/main.css">
	<link rel="preconnect" href="https://fonts.gstatic.com">
	<link href="https://fonts.googleapis.com/css2?family=Lobster&display=swap" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Yanone+Kaffeesatz:wght@300&display=swap" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Yanone+Kaffeesatz:wght@300&display=swap" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Oswald:wght@300&display=swap" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@200&display=swap" rel="stylesheet">
	<title>Sushi Rolls</title>

	<!-- Dark mode -->
	<script type="text/javascript" src="js/dark_mode_switcher.js"></script>
	<!-- Components including -->
	<script type="text/javascript" src="js/component_import.js"></script>
</head>
<body>
	<include-component href="components\header.php" onload="updateOrdersCountIcon(); ordersList();"></include-component>
	<div class="margin-zone"></div>

	<section class="container orderSection select-color">
		<h1>Оформлення замовлення</h1>
		<?php if ($error){echo '<center><h3 style="color: #ff5533">'.$error.'</h3></center>'; $error = '';}?>
		<form class="orderContainer" method="POST" action="" id="orderForm">
			<div class="orderColumnContainer">
				<h3>Інформація</h3>
				<!-- Ім'я -->
				<input required="" id="nameInput" type="text" placeholder="Ім'я" class="textInput" form="orderForm" name="name" value=<? echo @$loggedUser['login']; ?>><br>
				<!-- Телефон -->
				<input required="" id="phoneInput" type="text" placeholder="Телефон" class="textInput" form="orderForm" name="phone"><br>
				<!-- Комплектів паличок -->
				<div class="ordNumItem">
					<label for="sticksInput">Комплектів паличок: </label>
					<input id="sticksInput" type="number" class="numbBox" value="1" min="0" name="sticksNum" form="orderForm">				
				</div>
				<!-- Комплектів навчальних паличок -->
				<div class="ordNumItem">
					<label for="sticksEducInput">Комплектів<br> навчальних паличок: </label>
					<input id="sticksEducInput" type="number" class="numbBox" value="0" min="0" name="educSticksNum" form="orderForm">
				</div>
				<!-- Кількість людей -->
				<div class="ordNumItem">
					<label for="peopleNum">Кількість людей: </label>
					<input id="peopleNum" type="number" class="numbBox" value="1" min="1" name="peopleNum" form="orderForm">
				</div>
			</div>
			<div class="orderColumnContainer">
				<h3>Адреса</h3>
				<!-- Квартира або будинок -->
				<select class="selectInput select-color" name="adressHouse" oninput="this.parentNode.querySelector('div').style.display = this.value=='Квартира'?'inherit':'none';">
					<option value="Квартира">Квартира</option>
					<option value="Будинок">Будинок</option>
				</select>
				<!-- Місто -->
				<select name="cityName" form="orderForm" class="selectInput select-color">
					<option value="Чернівці">Чернівці</option>
					<option value="Чернівці">Івано-Франківськ</option>
				</select><br>
				<!-- Вулиця -->
				<input required="" type="text" placeholder="Вулиця" class="textInput" name="streetName"><br>
				<!-- Номер будинку -->
				<input required="" type="text" placeholder="Номер будинку" class="textInput" name="buildNum"><br>
				<div>
					<!-- Під'їзд -->
					<input type="text" placeholder="Під'їзд" class="textInput" name="entranceNumb"><br>
					<!-- Поверх -->
					<input type="text" placeholder="Поверх" class="textInput" name="floorNumb"><br>
					<!-- Номер квартири -->
					<input type="text" placeholder="Номер квартири" class="textInput" name="apartmentNumb"><br>
				</div>
				<textarea class="text_area" cols="100" placeholder="Повідомлення" form="orderForm" name="userMessage"></textarea><br><br>
			</div>
			<div class="orderColumnContainer orders">
				<div class="ordersInner">
					<h3>Кошик</h3>
					<div class="orderItem display-none">
						<div class="innerOrderItem">
							<div class="ordImg"></div>
							<div class="abouOrder">
								<div class="flex-space-between display-flex">
									<h3 class="orderTitle">Сет "Сімейний"</h3> <h3 class="deleteOrder unselectable" onclick="deleteOrder(this.getAttribute('ordid'))">⨉</h3>
								</div>
								<div class="countAndCost flex-space-between display-flex">
									<p>Кількість: <input type="number" min="1" max="1000" class="numbBox" value="1" oninput="updateOrderCosts(this);"></p><h3 class="hryvna ordCost">499</h3>
								</div>
							</div>
						</div>
					</div>		

					<div id="ordersBoxMakeOrder" class="user_orders">
						<div class="ordersEmpty">
							<p>Немає замовлень!</p>
						</div>
						<div class="orders_items">
							<!-- Orders... -->		
						</div>
						<div class="totalCost">
							<br>
							<br>
							<div class="flex-space-between display-flex">
								<p class="margin-none">Сума:</p><p class="margin-none"><b class="hryvna ordersTotalCost"></b></p>
							</div> <hr> <br>
							<div class="flex-space-between display-flex">
								<p class="margin-none">Доставка:</p><p class="margin-none"><b class="ordersFreeDelivery">Безкоштовна</b></p>
							</div> <hr> <br>
							<div class="flex-space-between display-flex">
								<p class="margin-none"><b>Всього:</b></p><p class="margin-none"><b class="hryvna ordersTotalCostWithDelivery"></b></p>
							</div>
							<br><button class="orderButton unselectable" id='orderButton' name="orders">Замовити</button>
						</div>
					</div>
				</div>
			</div>
		</form><br><br><br>
	</section>
	<!-- Footer -->
	<include-component href="components\footer.html"></include-component>
</body>
<script type="text/javascript" src="js/script.js"></script>
<script type="text/javascript">
	orderButton.value = sessionStorage.getItem('userOrders');
</script>
</html>