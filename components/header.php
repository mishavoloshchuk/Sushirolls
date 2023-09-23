<?php require($_SERVER['DOCUMENT_ROOT']."/db/config.php"); ?>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,300,0,0" rel="stylesheet"/>
<header>
	<div class="container unselectable">
		<div class="innerHeader container row-flex">
			<div class="logo-outer">
				<h1 class="logo" onclick="document.location.href = '/'">SushiRolls</h1>
			</div>
			<ul class="header_nav_menu_desktop">
				<!-- Menu -->
				<li><a href="/#product-list" onclick="menuPressButton('Роли')">Страви</a></li>
				<!-- Sales -->
				<li class="action"><a href="/#product-list" onclick="sessionStorage.setItem('menuPressButton', 'Акції'); menuPressButton('Акції');">Акції</a></li>
				<!-- Delivery -->
				<li><a href="delivery&paynment.html">Доставка\Оплата</a></li>
				<!-- Contacts -->
				<li><a href="contacts.html">Контакти</a></li>
				<!-- Reviews -->
				<li><a href="reviews.php">Відгуки</a></li>
				<!-- Light/dark mode switcher -->
				<li onclick="switchDarkMode();" class="dark_mode_switcher">
					<a>
						<span class="material-symbols-rounded" title="Змінити тему">
							<span class="light">light_mode</span>
							<span class="dark">dark_mode</span>
						</span>
					</a>
				</li>
				<!-- Orders basket -->
				<li onclick="showSideMenu('orders');" title="Кошик">
					<div class="order-count-icon">0</div>
					<a><span class="material-symbols-rounded">shopping_cart</span></a>
				</li>
				<?php if ($loggedUser): ?>
					<!-- User menu -->
					<li class="userIsLogged">
						<a onclick="showSideMenu('user_menu')" title="Обліковий запис"><span class="material-symbols-rounded span_icon">account_circle</span></a>
					</li>
				<?php else: ?>
					<!-- Login -->
					<li class="userIsNotLogged"><a onclick="showModal('login_form');">Увійти <span  class="material-symbols-rounded">login</span></a></li>
				<?php endif; ?>
			</ul>
			<div class="header_mobile_menu_button">
				<span class="material-symbols-rounded" onclick="showSideMenu('mobile_nav_menu');">menu</span>
			</div>
		</div>
	</div>
</header>

<!-- Mobile side menu -->
<section id="mobile_nav_menu" class="side_menu_wrapper" animstate="hidden" animationDuration="500">
	<div class="side_menu">
		<div class="inner_side_menu">
			<div class="side_menu_section_title">
				<h1>Меню сайту</h1>
			</div>

			<!-- Close button -->
			<div class="close_side_menu_cross" onclick="hideSideMenu('mobile_nav_menu');">⨉</div>

			<section class="side_menu_section">
				<ul class="header_nav_menu_mobile">
					<!-- Menu -->
					<li><a href="/#product-list" onclick="hideSideMenu('mobile_nav_menu'); menuPressButton('Роли');">Страви</a></li>
					<!-- Sales -->
					<li><a href="/#product-list" onclick="hideSideMenu('mobile_nav_menu'); sessionStorage.setItem('menuPressButton', 'Акції'); menuPressButton('Акції');">Акції</a></li>
					<!-- Delivery -->
					<li><a href="delivery&paynment.html">Доставка\Оплата</a></li>
					<!-- Contacts -->
					<li><a href="contacts.html">Контакти</a></li>
					<!-- Reviews -->
					<li><a href="reviews.php">Відгуки</a></li>
					<!-- Light/dark mode switcher -->
					<li style="padding: 4px" onclick="switchDarkMode();" class="dark_mode_switcher">
						<a>Перемкнути тему</a> 
						<span class="material-symbols-rounded">
							<span class="light">light_mode</span>
							<span class="dark">dark_mode</span>
						</span>
					</li>
					<!-- Orders basket -->
					<li onclick="hideSideMenu('mobile_nav_menu');showSideMenu('orders');">
						<div class="order-count-icon">0</div>
						<a>Кошик</a> 
						<span class="material-symbols-rounded">shopping_cart</span>
					</li>
					<?php if ($loggedUser): ?>
						<!-- Logout -->
						<li class="userIsLogged" onclick="hideSideMenu('mobile_nav_menu'); showSideMenu('user_menu')">
							<a title="Обліковий запис"><? echo $loggedUser['login']; ?></a> <span class="material-symbols-rounded span_icon">account_circle</span>
						</li>
					<?php else: ?>
						<!-- Login -->
						<li class="userIsNotLogged" onclick="hideSideMenu('mobile_nav_menu'); showModal('login_form');"><a>Увійти</a><span class="material-symbols-rounded">login</span></li>
					<?php endif; ?>
				</ul>
				
			</section>
		</div>
	</div>
	<div class="tint" onclick="hideSideMenu('mobile_nav_menu')"></div>
</section>

<!-- Login modal -->
<div class="modal" id="login_form" animstate="hidden" animationDuration="300">
	<div class="modal_wrapper">
		<!-- Close button -->
		<div class="close_side_menu_cross" onclick="hideModal(this.closest('.modal').getAttribute('id'));">⨉</div>

		<div class="login_form_wrapper">
			<h1>Авторизація</h1>
			<form action="" onsubmit="login(event);">
				<div class="loader" showed="false"></div>
				<span class="error" showed="false"></span>
				<input type="email" name="email" class="textInput" placeholder="Пошта" required>
				<input type="password" name="password" class="textInput" placeholder="Пароль" required>
				<label for="remember">Запам'ятати мене</label>
				<input type="checkbox" name="remember">
				<button type="submit" class="orderButton">Увійти</button>
				<span>Не маєте облікового запису?</span>
				<a onclick="hideModal('login_form'); showModal('registration_form')">Реєстрація</a>
			</form>
		</div>
	</div>
	<div class="tint" onclick="hideModal('login_form');"></div>
</div>

<!-- Registration modal -->
<div class="modal" id="registration_form" animstate="hidden" animationDuration="300">
	<div class="modal_wrapper">
		<!-- Close button -->
		<div class="close_side_menu_cross" onclick="hideModal(this.closest('.modal').getAttribute('id'));">⨉</div>

		<div class="login_form_wrapper">
			<h1>Реєстрація</h1>
			<form action="" onsubmit="registration(event);">
				<div class="loader" showed="false"></div>
				<span class="error" showed="false"></span>
				<input type="text" name="login" class="textInput" placeholder="Ім'я користувача (логін)" required>
				<input type="email" name="email" class="textInput" placeholder="Пошта" required>
				<input type="password" name="password" class="textInput" placeholder="Пароль" required>
				<input type="password" name="repeatPassword" class="textInput" placeholder="Повторіть пароль" required>
				<button type="submit" class="orderButton">Зареєструватися</button>
				<span>Вже маєте облікови запис?</span>
				<a onclick="hideModal('registration_form'); showModal('login_form');">Увійти</a>
			</form>
		</div>
	</div>
	<div class="tint" onclick="hideModal('registration_form')"></div>
</div>

<?php if ($loggedUser['status'] == 2): ?>

	<!-- Add or edit product modal -->
	<div class="modal" id="add_or_edit_product_modal" animstate="hidden" animationDuration="300">
		<div class="modal_wrapper">
			<!-- Close button -->
			<div class="close_side_menu_cross" onclick="hideModal(this.closest('.modal').getAttribute('id'));">⨉</div>

			<div class="login_form_wrapper">
				<h1 class="modal_title">Нова страва</h1>
				<form action="" onsubmit="productCreateOrEditSubmit(event)" onreset="
				getById('new_category_name').style.display = 'none';
				this.querySelector('.image_preview').setAttribute('src', 'icons\\image-gallery.png');">
					<div class="loader" showed="false"></div>
					<span class="error" showed="false"></span>
					<input type="text" name="title" class="textInput" placeholder="Назва страви" required>

					<label class="image_selector" for="product_img">
						<label onclick="this.closest('.image_selector').click()">
							Виберіть зображення:
						</label>
						<img src="icons\image-gallery.png" class="image_preview">
						<input type="file" name="image" id="product_img" accept=".jpg, .jpeg, .png" style="display: none;" onchange="setImagePreview(this, this.previousElementSibling);">
					</label>

					<input type="number" name="cost" class="textInput" placeholder="Ціна (грн)" required>

					<!-- Type select -->
					<select name="type" class="selectInput" required onchange="setCategoriesSelect(this.value); this.nextElementSibling.style.display = 'initial';">

					</select>

					<!-- Category select -->
					<select name="category" class="selectInput categorySelect" style="display: none;" required onchange="
						// Display new category name input
						this.nextElementSibling.style.display = this.value == 'new_category' ? 'initial' : 'none';
					">

					</select>
					<input type="text" id="new_category_name" name="newCategoryName" class="textInput" placeholder="Назва нової категорії" style="display: none">

					<br><br>
					<textarea name="description" class="text_area" rows="5" placeholder="Опис страви" required></textarea>
					<button type="submit" name="submit" value="create" class="orderButton">Створити</button>
				</form>
			</div>
		</div>
		<div class="tint" onclick="hideModal('add_or_edit_product_modal')"></div>
	</div>

	<!-- Change user status modal -->
	<div class="modal" id="change_user_status_modal" animstate="hidden" animationDuration="300">
		<div class="modal_wrapper">
			<!-- Close button -->
			<div class="close_side_menu_cross" onclick="hideModal(this.closest('.modal').getAttribute('id'));">⨉</div>

			<div class="login_form_wrapper">
				<h1 class="modal_title">Змінити статус</h1>
				<form action="" onsubmit="event.preventDefault(); setUserStatus(this.user_email.value, this.status_id.value);">
					<div class="loader" showed="false"></div>
					<span class="error" showed="false"></span>
					<input type="email" name="user_email" class="textInput" placeholder="Електронна адреса користувача" required>

					<!-- Status select -->
					<select name="status_id" class="selectInput" required>
						<option value="" disabled selected>Вибрати статус</option>
						<? 
							$sql = $cnct->query("SELECT * FROM user_status WHERE id != 2");
							while($user_status = $sql->fetch_assoc()){
								echo "<option value='".$user_status['id']."'>".$user_status['statusName']."</option>";
							}
						?>
					</select>

					<button type="submit" name="submit" value="create" class="orderButton">Змінити</button>
				</form>
			</div>
		</div>
		<div class="tint" onclick="hideModal(this.closest('.modal').getAttribute('id'));"></div>
	</div>
<? endif; ?>
<section id="orders" class="side_menu_wrapper" animstate="hidden" animationDuration="500">
	<div class="side_menu">
		<div class="inner_side_menu">
			<div class="side_menu_section_title">
				<h1>Кошик</h1>
			</div>

			<div class="close_side_menu_cross" onclick="hideSideMenu('orders')">⨉</div>
			<section class="side_menu_section">
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

				<div id="ordersBox" class="user_orders">
					<div class="ordersEmpty">
						<br>
						<p>Ще немає замовлень</p>
						<br>
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
						</div> <hr> <br>
						<div class="orderButton unselectable" onclick="document.location.href = 'order.php'">Оформити замовлення</div><br>
					</div>
				</div>
			</section>
		</div>
	</div>
	<div class="tint" onclick="hideSideMenu('orders')"></div>
</section>

<!-- ================ User menu ================== -->
<?php if ($loggedUser): ?>
	<section id="user_menu" class="side_menu_wrapper" animstate="hidden" animationDuration="500">
		<div class="side_menu">
			<div class="inner_side_menu">
				<div class="side_menu_section_title">
					<h1><? 
						echo $loggedUser['login']; 
						// Show account status
						if ($loggedUser['status'] != 1) { echo ' ('.$loggedUser['statusName'].')'; }
					?></h1>
				</div>

				<!-- Close button -->
				<div class="close_side_menu_cross" onclick="hideModal(this.closest('.side_menu_wrapper').getAttribute('id'));">⨉</div>

				<section class="side_menu_section">
					<div class="user_menu_item">
						<a href="order_history.php">Мої замовлення</a>
					</div>

					<div class="user_menu_item">
						<a class="side_menu_button unselectable" onclick="logout(); hideSideMenu('user_menu');">
							Вийти
						</a>
					</div>
				</section>

				<? switch ($loggedUser['status']): 
					case 2: ?>
						<div class="side_menu_section_title">
							<h1>Адміністрування сайту</h1>
						</div>

						<section class="side_menu_section">
							<div class="user_menu_item">
								<a 
								onclick="hideSideMenu('user_menu'); 
								setCreateOrEditModalValues('add_or_edit_product_modal', {modalTitle: 'Нова страва', modalButtonInnerHTML: 'Створити', modalMode: 'create'}); 
								showModal('add_or_edit_product_modal');">Додати страву на сайт</a>
							</div>
							<div class="user_menu_item">
								<a href="orders_for_personal.php">Переглянути всі замовлення користувачів</a>
							</div>
							<div class="user_menu_item" onclick="hideSideMenu('user_menu'); showModal('change_user_status_modal');">
								<a href="#">Змінити статус користувачу</a>
							</div>
						</section>
					<? break; case 3: ?>
						<div class="side_menu_section_title">
							<h1>Панель кухара</h1>
						</div>

						<section class="side_menu_section">
							<div class="user_menu_item">
								<a href="orders_for_personal.php">Переглянути доступні замовлення</a>
							</div>
						</section>
					<? break; case 4: ?>
						<div class="side_menu_section_title">
							<h1>Панель кур'єра</h1>
						</div>

						<section class="side_menu_section">
							<div class="user_menu_item">
								<a href="orders_for_personal.php">Переглянути замовлення, очікуючі доставки</a>
							</div>
						</section>
				<? endswitch; ?>
			</div>
		</div>
		<div class="tint" onclick="hideSideMenu('user_menu')"></div>
	</section>
<? endif; ?>