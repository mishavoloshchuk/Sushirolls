<?php require($_SERVER['DOCUMENT_ROOT']."/db/config.php"); ?>
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

	<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded" rel="stylesheet" />
	<title>Sushi Rolls</title>

	<!-- Dark mode -->
	<script type="text/javascript" src="js/dark_mode_switcher.js"></script>
	<!-- Components including -->
	<script type="text/javascript" src="js/component_import.js"></script>
</head>
<body>
	<include-component href="components\header.php" onload="updateOrdersCountIcon(); ordersList(); openModalOnLoad();"></include-component>
	<section>
		<div class="mainScreen">
			<div class="poster container row-flex">
				<div style="border-right: 3px solid var(--main-color-2);">
					<h1 class="msText">Найкращі суші<br> для тебе!</h1>				
				</div>
				<div style="text-align: center;" class="poster_sushi_img">
				<img src="images/sushi_img.png" alt="Найкращі суші!" style=" -webkit-filter: drop-shadow(3px 5px 25px var(--main-color));filter: drop-shadow(3px 5px 25px var(--main-color));">
				</div>
			</div>
			<section class="menu-buttons container unselectable">
				<div class="menu-button" selected="false" onclick="this.getAttribute('selected') == 'false' && menuPressButton('Роли');">
					<div class="inner-menu-button">
						<h2 class="menu-button-text">Роли</h2>
					</div>
				</div>
				<div class="menu-button" selected="false" onclick="this.getAttribute('selected') == 'false' && menuPressButton('Сети');">
					<div class="inner-menu-button">
						<h2 class="menu-button-text">Сети</h2>
					</div>
				</div>
				<div class="menu-button" selected="false" onclick="this.getAttribute('selected') == 'false' && menuPressButton('Салати');">
					<div class="inner-menu-button">
						<h2 class="menu-button-text">Салати</h2>
					</div>
				</div>
				<div class="menu-button" selected="false" onclick="this.getAttribute('selected') == 'false' && menuPressButton('Напої');">
					<div class="inner-menu-button">
						<h2 class="menu-button-text">Напої</h2>
					</div>
				</div>
				<div class="menu-button" selected="false" onclick="this.getAttribute('selected') == 'false' && menuPressButton('Акції');">
					<div class="inner-menu-button">
						<h2 class="menu-button-text">Акції</h2>
					</div>
				</div>
			</section>
		</div>
	</section>
	<!-- Product item -->
	<div class="product display-none">
		<div class="inner-product">
			<?php if ($loggedUser['status'] == 2): ?>
				<button class="admin_control_button admin_control_open_btn material-symbols-rounded" onclick="const sib = this.nextElementSibling; sib.show(); sib.hideTmt = setTimeout(() => sib.hide(), 5000);">more_horiz</button>
				<div class="admin_control" animstate='hidden' animationduration='200' onmouseleave="this.hide(); clearTimeout(this.hideTmt)">
					<button class="admin_control_button material-symbols-rounded" style="background-color: #F66; color: #222;" title="Видалити" onclick="deleteProduct(this.closest('.product').getAttribute('productId'))">delete</button>
					<button class="admin_control_button material-symbols-rounded" title="Редагувати" onclick="startEditProduct(this.closest('.product').getAttribute('productId'))">edit</button>
				</div>
			<? endif; ?>
			<div class="product-img unselectable">
				<img src="">
			</div>
			<div class="about-product">
				<div class="product-title">
					<h2>Сет "Сімейний"</h2>
				</div>
				<div class="product-description">
					<span>рол "Філадельфія мікс", рол "Сирний з лососем", рол "Філадельфія в кунжуті з лососем", рол "Сакура", рол "Аніме", рол "Філадельфія з мідією", імбир, васабі, соєвий соус</span>
				</div>
				<div class="cost-buy margin-top">
					<div class="product-cost">
						<h2>499</h2><h2> грн.</h2>
					</div>
					<div class="buy-button unselectable" onclick="buyButtonPress(this.closest('.product').getAttribute('productId'));">
						<h3>В кошик</h3>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- Discount item -->
	<div class="actionItem display-none">
		<div class="inner-product">
			<div class="action_background_img"></div>
			<div class="about-product">
				<div class="product-title">
					<h2>Сет "Сімейний"</h2>
				</div>
				<div class="product-description">
					<span>рол "Філадельфія мікс", рол "Сирний з лососем", рол "Філадельфія в кунжуті з лососем", рол "Сакура", рол "Аніме", рол "Філадельфія з мідією", імбир, васабі, соєвий соус</span>
				</div>
			</div>
		</div>
	</div>
	<!-- Products list -->
	<section class="container product-list" id='product-list'>
	</section>
	<section class="container about-us">
		<h1>Де замовити смачні та якісні суші?</h1>
		<p>
			Популярність японської їжі сьогодні надзвичайно велика. У кожному місті нашої країни можна знайти заклад, у меню якого є східні страви. Неперевершений екзотичний смак, апетитний аромат, привабливе оформлення та беззаперечна користь — ось секрет затребуваності цих делікатесів. Для створення суші використовується нехитрий набір продуктів, які у поєднанні утворюють оригінальний смак: практично необроблена риба чи інші дари моря, варений рис, пресовані водорості, ніжний тофу, свіжі овочі і оригінальні спеції. 
		</p>
		<h1>Чому варто зробити замовлення суші в SushiRolls?</h1>

		<p>Ми відповідально ставимося до вибору постачальників. Тому це компанії з ідеальною репутацією та багаторічним досвідом. Уся сировина перевозиться із залученням новітніх технологій та найсучаснішого обладнання. Це дозволяє нам отримувати тільки високоякісні продукти, з яких наші кваліфіковані кухарі роблять справжні делікатеси для вас. </p>

		<p>Щоб продукти потрібний час залишались свіжими та зберігали свої смакові властивості, кухня Кайфуй Суші оснащення найновішим устаткуванням. Холодильні та морозильні камери дозволяють зберігати сировину у належному стані. Навіть під час приготування, завдяки сучасним приладам, продукти тримають потрібну температуру. </p>
		<p>Переваги замовлення суші в SushiRolls:</p>
		<ul>
			<li>Відмінна якість їжі. На нашій кухні не буває напівфабрикатів — кухарі готують безпосередньо після отримання заявки виключно зі свіжої та якісної сировини.</li><br>
			<li>Першокласний сервіс. SushiRolls — це місце, де вам завжди раді. Тому наші співробітники приділяють максимум уваги кожному клієнту. Вони працюють швидко і злагоджено. Улюблені страви ви отримаєте за нетривалий проміжок часу. </li><br>
			<li>Різноманітність меню. Наша команда створила меню, в якому кожен знайде їжу собі до смаку: суші і роли у класичному виконанні та за покращеними рецептами, роли для вегетаріанців, суші-кейки, теплі роли, великі суші сети та страви для маленьких поціновувачів східної кухні.</li><br>
			<li>Доступні ціни. Вартість страв у Кайфуй абсолютно обґрунтована та орієнтована на клієнта.</li><br>
			<li>Можливість придбати їжу та зекономити. Ми знаємо, як ви любите суші. Тому проводимо акції та радуємо вам знижками.</li>
		</ul>
		<h1>Швидка доставка суші</h1>
		<p>Не маєте часу на обід? Замовляйте доставку. 

		Хочете вразити гостей але не бажаєте готувати самостійно? Замовляйте доставку. 
		
		Відпочиваєте з друзями і зголодніли? Замовляйте доставку.
		
		Наявність власної служби доставки дозволяє нашій компанії привозити страви у бідь-яке місце у точно зазначений час.</p>
		<p>Оформити заявку можна за телефоном чи через відповідний розділ сайту. Оператор з’ясує нюанси та передасть її кухарям. Без затримок вони візьмуться до роботи. Кур’єр отримає страви буквально «з-під ножа» та максимально швидко доправить клієнту.</p>
		<p>SushiRolls допоможе створити особливу атмосферу та посмакувати улюбленими делікатесами будь-де та у будь-який час!</p>
	</section>
	<!-- Footer -->
	<include-component href="components\footer.html"></include-component>
</body>
<script type="text/javascript" src="js/script.js"></script>
</html>