<?php require_once($_SERVER['DOCUMENT_ROOT'].'/db/config.php'); ?>
<!DOCTYPE html>
<html lang="ua">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" type="text/css" href="../styles/main.css">
	<link rel="preconnect" href="https://fonts.gstatic.com">
	<link href="https://fonts.googleapis.com/css2?family=Lobster&display=swap" rel="stylesheet">
	<link rel="preconnect" href="https://fonts.gstatic.com">
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
	<section class="container about-us select-color">
		<h1>Замовлення користувачів</h1>
		<?php 

		$userStatusId = $loggedUser['status'];

		$userAvlblOrderStatusesToSet = $loggedUser['avlblOrderStatusesToSet'];
		$userAvlblOrderStatusesToSetString = implode(',', $userAvlblOrderStatusesToSet);

		$userCanSetStatusIfOrderStatus = $loggedUser['canSetStatusIfOrderStatus'];
		$userCanSetStatusIfOrderStatusString = implode(',', $userCanSetStatusIfOrderStatus);

		if ($userStatusId == 2){
			$whereCondition = '';
		} else {
			$whereCondition = "WHERE orders.statusId IN ($userCanSetStatusIfOrderStatusString)";
		}

		switch ($loggedUser['status']){
			case 2: // Admin
				$sql = "SELECT orders.*, order_status.status FROM `orders` INNER JOIN order_status ON statusId = order_status.id ORDER BY FIELD(statusId, 1, 2, 3, 4, statusId), `date` ASC";
				break;
			case 3: // Cook
				$sql = "SELECT orders.*, order_status.status FROM `orders` 
					INNER JOIN order_status ON statusId = order_status.id 
					WHERE orders.statusId = 1 OR (orders.cookId = $loggedUserId AND orders.statusId IN ($userCanSetStatusIfOrderStatusString)) 
					ORDER BY FIELD(statusId, 4, 3, 1, statusId), `date` ASC";
				break;
			case 4: // Courier
				$sql = "SELECT orders.*, order_status.status FROM `orders` 
					INNER JOIN order_status ON statusId = order_status.id 
					WHERE orders.statusId = 3 OR (orders.courierId = $loggedUserId AND orders.statusId IN ($userCanSetStatusIfOrderStatusString))
					ORDER BY FIELD(statusId, 2, 3, statusId), `date` ASC";
				break;
		}
		$orders = $cnct->query($sql);
		while ($order = $orders->fetch_assoc()){
				$isUserAuthor = $order['userId'] == $loggedUserId;
			?>
			<div class="order <?php if ($order['completed']){echo 'completedCol';}?>" status="<? echo $order['statusId']; ?>">
				<div class="order_status_section">
					<h3><? echo $order['status']; ?></h3>
					<? if (in_array($order['statusId'], $loggedUser['canSetStatusIfOrderStatus']) ): ?>
						<form class="flex_row" onsubmit="setOrderStatus(<? echo $order['id']; ?>, this.mark_as.value);">
							<select class="selectInput" name="mark_as">
								<option value="" selected disabled>Змінити статус:</option>
								<?
									$availableOptions = $cnct->query("SELECT * FROM order_status WHERE id IN ($userAvlblOrderStatusesToSetString) OR ('$isUserAuthor' AND id = 5)");
									while ($ordStatus = $availableOptions->fetch_assoc()) {
										if ($ordStatus['id'] == $order['statusId']) continue;
										$ordStatusId = $ordStatus['id'];
										$ordStatusName = $ordStatus['status'];
										echo "<option value='$ordStatusId'>$ordStatusName</option>";
									}
									
								?>
							</select>
							<button type="submit" class="orderButton unselectable" style="padding: 5px 13px">OK</button>
						</form>
					<? endif; ?>
				</div>
				<hr><br>

				<div class="order_info">
					<section class="order_info_vert_section">
						<b>Інформація:</b>
						<p>Ідентифікатор замовлення: <b><? echo $order['id']; ?></b></p>
						<? // Інформіція для кур'єра та адміністратора
						if ($userStatusId == 4 || $userStatusId == 2): ?>
							<p>Адреса доставки: 
								<b><?
									echo "м. ".$order['cityName'].', вул. '.$order['streetName'].' '.$order['buildNum'];
									if ($order['adressHouse'] == 'Квартира'){
										echo ', під\'їзд: '.$order['entranceNumb'].', Поверх: '.$order['floorNumb'].', квартира № '.$order['apartmentNumb'];
									}
								?></b>
							</p>
							<p>Ім'я замовника: <b><? echo $order['name']; ?></b></p>
						<? 
						endif; 
						// Інформіція для кухара та адміністратора
						if ($userStatusId == 3 || $userStatusId == 2): ?>
							<p>Комплекти паличок: <b><?echo $order['sticksNum'];?></b></p>
							<p>Комплекти навчальних паличок: <b><?echo $order['educSticksNum'];?></b></p>
							<p>Кількість людей: <b><?echo $order['peopleNum'];?></b></p>
						<? endif; ?>

						<p>Повідомлення: <b><?echo $order['userMessage'];?></b></p>
						<p>Дата та час: <b><?echo explode('.', $order['date'])[0];?></b></p>
					</section>
					<section class="order_info_vert_section">
						<b>Замовлення:</b>
						<div class="ordersBox">
							<?php 
							$totalCost = 0;
							$ordersList = json_decode($order["jsonOrders"], true);
							foreach($ordersList as $oi){ ?>
								<div class="orderItem" style="width: 100%;">
									<div class="innerOrderItem">
										<div class="ordImg" style="background-image: url(<?echo $oi['imgsrc'];?>);"></div>
										<div class="abouOrder">
											<div class="flex-space-between display-flex">
												<h3 class="orderTitle"><?echo $oi['title'];?></h3>
											</div>
											<div class="countAndCost flex-space-between display-flex">
												<p>Кількість: <b><?echo $oi['count'];?></b></p><h3 class="hryvna ordCost"><?echo $oi['cost']*$oi['count']; $totalCost+=$oi['cost']*$oi['count']; ?></h3>
											</div>
										</div>
									</div>
								</div>
							<?php } ?>
						</div>
						<p>Сума: <b><?echo $totalCost; ?> грн</b> | Доставка: <b><?if($totalCost >= 300){echo 'Безкоштовно';}else{echo '50 грн.';} ?> </b></p>
						<h3>Всього: <?if($totalCost >= 300){echo $totalCost;}else{echo $totalCost + 50;} ?> грн</h3>
					</section>
				</div>
			</div>
		<?php } ?>
		<br><br>
	</section>
	<!-- Footer -->
	<include-component href="components\footer.html"></include-component>
</body>
<script type="text/javascript" src="js/script.js"></script>
</html>