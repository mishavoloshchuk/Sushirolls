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
		<h1>Мої замовлення</h1>
		<?php 
		$orders = $cnct->query("SELECT orders.*, order_status.status FROM `orders` INNER JOIN order_status ON statusId = order_status.id WHERE userId = $loggedUserId ORDER BY FIELD(statusId, 1, 2, 3, 4, statusId), `date` DESC LIMIT 100");
		while ($order = $orders->fetch_assoc()){ 
			?>
			<div class="order" status="<? echo $order['statusId']; ?>">
				<div class="order_status_section">
					<h3><? echo $order['status']; ?></h3>
					<? if ($order['statusId'] == '1'): ?>
						<a onclick="setOrderStatus(<? echo $order['id']; ?>, 5)">Відмінити замовлення</a>
					<? endif; ?>
				</div>
				<hr><br>

				<div class="order_info">
					<section class="order_info_vert_section">
						<b>Інформація:</b>
						<p>
							Адреса доставки: 
							<b><?
								echo "м. ".$order['cityName'].', вул. '.$order['streetName'].' '.$order['buildNum'];
								if ($order['adressHouse'] == 'Квартира'){
									echo ', під\'їзд: '.$order['entranceNumb'].', Поверх: '.$order['floorNumb'].', квартира № '.$order['apartmentNumb'];
								}
							?></b>
						</p>		
						<p>Комплекти паличок: <b><?echo $order['sticksNum'];?></b></p>
						<p>Комплекти навчальних паличок: <b><?echo $order['educSticksNum'];?></b></p>
						<p>Кількість людей: <b><?echo $order['peopleNum'];?></b></p>
						<p>Повідомлення: <b><?echo $order['userMessage'];?></b></p>
						<h3><?echo explode('.', $order['date'])[0];?></h3>
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