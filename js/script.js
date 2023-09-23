let actions = [
	{
		title: '🚚Безкоштовна доставка💵',
		description: 'Ми пропонуємо безкоштовну доставку при замовленні на 250 грн або більше. Вона швидка та надійна, і ваше замовлення буде доставлене прямо до ваших дверей.',
		imgsrc: 'images/free_delivery.jpg'},
	{
		title: '🎉З днем нарождення!🎈',
		description: 'Друже, твій День народження - це свято і для нас! Тому кайфуємо весь тиждень з тобою, твоїми друзями, колегами, котиками, цуциками, папужками... З усіма, кого ти будеш пригощати. Забудь про "оселедець під шубою" та моркву по-корейськи - бери суші у #shushirolls й свято буде ВОГОНЬ! Знижка 15% на твій День народження + 3 дні до та 3 дні після.',
		imgsrc: 'images/HappyBirthday.jpg'},
];

let loggedUser = {};
// .then((responceLoggedUser) => {
// 	loggedUser = responceLoggedUser;
// 	setUserInterface();
// })
let products = [];

let openedMenu = 'Сет';

// Animation
Element.prototype.show = function (){
	if (this.animState !== "hidden" && this.animState !== undefined) return;
	const animDuration = this.getAttribute('animationDuration');
	this.setAttribute('animState', 'showing');
	this.animState = 'showing';
	clearTimeout(this.timeout);
	this.timeout = setTimeout(() => {this.setAttribute('animState', 'showed'); this.animState = 'showed';}, animDuration);
}
Element.prototype.hide = function (){
	if (this.animState !== "showed" && this.animState !== undefined) return;
	const animDuration = this.getAttribute('animationDuration');
	this.setAttribute('animState', 'hiding');
	this.animState = 'hiding';
	clearTimeout(this.timeout);
	this.timeout = setTimeout(() => {this.setAttribute('animState', 'hidden'); this.animState = 'hidden'}, animDuration);
}

function setUserInterface(){
	const notLoggedInterface = document.querySelectorAll('.userIsNotLogged');
	const loggedInterface = document.querySelectorAll('.userIsLogged');

	if (loggedUser.login){ // Show logged user interface
		loggedInterface.forEach((elem)=>{
			elem.className = elem.className.replace(' display-none', '');
			try {
				const onSetUI = Function('user', elem.getAttribute('onsetui')).bind(elem);
				onSetUI && onSetUI(loggedUser);
			} catch(e) {

			}
		});
		notLoggedInterface.forEach((elem)=>{
			elem.className += ' display-none';
		});	
	} else { // Show not logged user interface
		notLoggedInterface.forEach((elem)=>{
			elem.className = elem.className.replace(' display-none', '');
		});	
		loggedInterface.forEach((elem)=>{
			elem.className += ' display-none';
		});	
	}
}

document.querySelectorAll('.userIsNotLogged')

function getById(id) {
   var el = document.getElementById(id);
   if (!el) {
	    throw new ReferenceError(id + " is not defined");
   }
   return el;
}

const errNames = {
	'email_or_password_is_wrong': 'Перевірте правильність введених даних',
	'image_is_missing': 'Вкажіть зображення',
	'type_is_missing': 'Вкажіть тип продукту',
	'category_is_missing': 'Вкажіть категорію',
	'rating_is_missing': 'Виберіть оцінку',
	'0affected_rows': 'Нічого не змінилося',
	'user_with_given_email_is_not_exist': 'Користувача з вказаною поштою не знайдено',
	'the_administrator_status_cannot_be_changed': 'Не можна змінювати статус адміністратору',
}

function registration(event){
	event.preventDefault();
	// Modal elements
	const form = event.target;
	const loader = form.querySelector('.loader');
	const error = form.querySelector('.error');
	// Set loader
	loader.setAttribute('showed', 'true');

	// Check if passwords match
	if (form.password.value == form.repeatPassword.value){
		const formData = new FormData();

		formData.append('login', form.login.value);
		formData.append('email', form.email.value);
		formData.append('password', form.password.value);

		fetch("api/register.php", {
			method: "POST",
			body: formData
		}).then((response) => response.json())
		.then((data) => {
			loader.setAttribute('showed', 'false');
			if (data.status == 'success'){
				hideModal('registration_form');
				showModal('login_form');
			}
			// console.log(data)
		})
		.catch((err) => {
			console.error(err);
		})
	}
}

function login(event){
	event.preventDefault();
	// Modal elements
	const form = event.target;
	const loader = form.querySelector('.loader');
	const error = form.querySelector('.error');
	// Set loader
	loader.setAttribute('showed', 'true');


	const formData = new FormData();

	formData.append('email', form.email.value);
	formData.append('password', form.password.value);
	formData.append('remember', form.remember.checked);

	fetch("api/auth.php", {
		method: "POST",
		body: formData
	}).then((response) => response.json())
	.then((data) => {
		loader.setAttribute('showed', 'false');
		if (data.status == 'success'){
			loggedUser = data.loggedUser;
			hideModal('login_form');
			// setUserInterface();
			location.reload();
		}
		else if (data.status == 'error'){
			error.innerHTML = errNames[data.error];
			error.setAttribute('showed', true);
			setTimeout(() => error.setAttribute('showed', false), 2000);
		}
	})
	.catch((err) => {
		console.error(err);
	})
}

function logout(){
	fetch("api/logout.php")
	.then(() => {
		loggedUser = {};
		location.reload();
		// setUserInterface();
	})
	.catch((err) => {
		throw err;
	})
}

function add_review(event){
	event.preventDefault();
	// Modal elements
	const form = event.target;
	const loader = form.querySelector('.loader');
	const error = form.querySelector('.error');
	// Set loader
	loader.setAttribute('showed', 'true');

	const formData = new FormData();
	const modalMode = event.target.getAttribute('modalMode');

	formData.append('modalMode', modalMode);
	formData.append('author_name', form.author_name.value);
	formData.append('rating', form.querySelector('.rating').getAttribute('value'));
	formData.append('review_text', form.review_text.value);

	fetch("api/add_review.php", {
		method: "POST",
		body: formData
	}).then((response) => response.json())
	.then((data) => {
		loader.setAttribute('showed', 'false');
		if (data.status == 'success'){
			loggedUser = data.loggedUser;
			hideModal('add_review_modal');
			// setUserInterface();
			location.reload();
		}
		else if (data.status == 'error'){
			error.innerHTML = errNames[data.error] || data.error;
			error.setAttribute('showed', true);
			setTimeout(() => error.setAttribute('showed', false), 2000);
		}
	})
	.catch((err) => {
		console.error(err);
	})
}

async function setCategories(type, selectElem = getById("add_or_edit_product_modal").querySelector('.categorySelect')){

}

function productCreateOrEditSubmit(event){
	event.preventDefault();
	// Modal elements
	const form = event.target;
	const loader = form.querySelector('.loader');
	const error = form.querySelector('.error');

	const formData = new FormData();
	const modalMode = event.target.getAttribute('modalMode');

	loader.setAttribute('showed', 'true');

	formData.append('modalMode', modalMode);
	formData.append('editProductId', form.submit.value);

	formData.append('title', form.title.value);
	formData.append('image', form.image.files[0]);
	formData.append('cost', form.cost.value);
	formData.append('category', form.category.value);
	formData.append('newCategoryName', form.newCategoryName.value);
	formData.append('type', form.type.value);
	formData.append('description', form.description.value);

	fetch("api/add_product.php", {
		method: "POST",
		body: formData
	}).then((response) => response.json())
	.then((data) => {
		loader.setAttribute('showed', 'false');
		if (data.status == 'success'){
			if (modalMode == 'create'){
				alert('Страву успішно додано!');
			} else {
				alert('Страву успішно змінено!');
			}
			hideModal('add_or_edit_product_modal');
			showProductsList(openedMenu);
			form.reset();
		} else {
			error.innerHTML = errNames[data.error] || data.error;
			error.setAttribute('showed', true);				
			setTimeout(() => error.setAttribute('showed', false), 2000);
		}
		// console.log(data);
	})
	.catch((err) => {
		console.error(err);
	});
}

function deleteProduct(productId) {
	if (confirm('Ви впевнені, що хочете видалити продукт з меню?')){	
		fetch("api/delete_product.php?" + convertToGET({deleteId: productId}))
		.then(response => response.json())
		.then(data => {
			if (data.status == 'success'){
				showProductsList(openedMenu);
			}
			else if (data.status == 'error'){
				console.error(data.error);
			}
		})
		.catch((err) => {
			throw err;
		});	
	}
}

function editProductRequest(formData){
	fetch("api/edit_product.php", {
		method: "POST",
		body: formData
	}).then((response) => response.json())
	.then((data) => {
		loader.setAttribute('showed', 'false');
		if (data.status == 'success'){
			alert('Страву успішно додано!');
			hideModal('add_or_edit_product_modal');
			showProductsList(openedMenu);
			form.reset();
		} else {
			error.innerHTML = errNames[data.error] || data.error;
			error.setAttribute('showed', true);				
			setTimeout(() => error.setAttribute('showed', false), 2000);
		}
		// console.log(data);
	})
	.catch((err) => {
		console.error(err);
	});	
}

async function startEditProduct(productId){
	const editProduct = getProduct(productId, products);
	await setCreateOrEditModalValues('add_or_edit_product_modal', {
		modalTitle: 'Змінення страви',
		modalButtonInnerHTML: 'Змінити',
		modalMode: 'edit',
		editProductId: editProduct.id,
		title: editProduct.title,
		image: editProduct.imgsrc,
		cost: editProduct.cost,
		type: editProduct.typeId,
		category: editProduct.categoryId,
		description: editProduct.description
	});
	showModal('add_or_edit_product_modal');
}
let categoriesList = [];
function setCategoriesSelect(type, categoriesElement = getById("add_or_edit_product_modal").querySelector(".categorySelect")){
	// Add categories to options list
	categoriesElement.innerHTML = '';
	categoriesElement.innerHTML += '<option value="new_category">Створити нову категорію</option>';
	categoriesElement.innerHTML += '<option value="" disabled selected>Вибрати категорію</option>';
	for (let category of categoriesList){
		if (category.typeId == type){
			categoriesElement.innerHTML += `<option value="${category.id}">${category.categoryName}</option>`;
		}
	}
}
async function setCreateOrEditModalValues(modalId, params){
	const modalElement = getById(modalId);
	const formElement = modalElement.querySelector('form');
	const typesList = formElement.type;
	const categoriesContainer = formElement.category;

	// Clear categories and types options
	typesList.innerHTML = categoriesContainer.innerHTML = '';

	// Get categories and types from server
	const typesAndCategories = await fetch("api/get_types_and_categories.php")
		.then(response => response.json());

	categoriesList = typesAndCategories.product_categories;
	
	// Add types to options list
	typesList.innerHTML += '<option value="" disabled selected>Вибрати тип страви</option>';
	for (let type of typesAndCategories.product_types){
		typesList.innerHTML += `<option value="${type.id}">${type.typeName}</option>`;
	}

	// Reset form
	formElement.reset();

	// Set form parameters
	for (let paramName in params){
		const value = params[paramName];
		switch (paramName){
		case 'modalTitle':
			modalElement.querySelector('.modal_title').innerHTML = value;
			break;
		case 'modalButtonInnerHTML':
			formElement.submit.innerHTML = value;
			break;
		case 'modalMode':
			formElement.setAttribute('modalMode', value);
			formElement.category.style = value == 'create' ? 'display: none' : '';
			setCategoriesSelect(params.type);
			break;
		case 'image':
			formElement.querySelector('.image_preview').src = value;
			break;
		case 'editProductId':
			formElement.submit.value = value;
			break;

		default:
			formElement[paramName].value = value;
		}
	}
}

function deleteReview(){
	if (confirm('Ви впевнені, що хочете видалити ваш відгук?')){	
		fetch("api/delete_review.php?")
		.then(response => response.json())
		.then(data => {
			if (data.status == 'success'){
				location.reload();
			}
			else if (data.status == 'error'){
				console.error(data.error);
			}
		})
		.catch((err) => {
			throw err;
		});	
	}	
}

function setOrderStatus(orderId, orderStatusId, reason){
	fetch( "api/set_order_status.php?" + convertToGET({
		orderId: orderId,
		orderStatusId: orderStatusId,
		reason: reason
	}) ).then((response) => response.json())
	.then((data) => {
		console.log(data);
		location.reload();
	})
	.catch((err) => {
		console.error(err);
	});
}

function setUserStatus(userEmail, statusId){
	fetch( "api/set_user_status.php?" + convertToGET({
		userEmail: userEmail,
		statusId: statusId
	}) ).then((response) => response.json())
	.then((data) => {
		// console.log(data);
		if (data.status == 'error'){
			const errorElem = getById('change_user_status_modal').querySelector('.error');
			errorElem.innerHTML = errNames[data.error] || data.error;
			errorElem.setAttribute('showed', true);				
			setTimeout(() => errorElem.setAttribute('showed', false), 2000);
		}
	})
	.catch((err) => {
		console.error(err);
	});
}

async function getProducts({type = 'Сет', category = '*'}){
	const options = {
		type: type,
		category: category
	}
	const url = 'api/get_products.php?' + convertToGET(options);
	// const data = new Promise((resolve, reject) => {

	// })
	return await fetch(url)
	.then((response) => response.json())
	.then (data => {
		return data;
	})
}

function setImagePreview(input, imgElem){
	const [image] = input.files;

	if (image){
		imgElem.src = URL.createObjectURL(image);
	}
}

// async function getLoggedUser(){
// 	const response = await fetch("api/get_logged_user.php");
// 	return await response.json();
// }

function convertToGET(obj){
	return Object.keys(obj).map(function(key) {
		return key + '=' + obj[key];
	}).join('&');
}



let scrollWidth = getScrollWidth();
window.addEventListener('resize', () => {
	scrollWidth = getScrollWidth();
});
let unlockScrollTimeout;

function lockScroll(){
	clearTimeout(unlockScrollTimeout);
	document.body.style.cssText = `overflow-y: hidden; margin-left: -${scrollWidth/2}px;`;
}
function unlockScroll(){
	document.body.style.cssText = 'overflow-y: auto; margin-left: 0;';
}
function getScrollWidth(){
	return innerWidth - document.body.offsetWidth;
}


function showModal (modalId){
	const modalElement = getById(modalId);
	modalElement.show();
	lockScroll();
}
function hideModal (modalId){
	const modalElement = getById(modalId);
	modalElement.hide();
	unlockScroll();
}

function showSideMenu (menuId){
	const sideMenuElem = getById(menuId);
	lockScroll();
	sideMenuElem.show();
}
function hideSideMenu (menuId){
	const sideMenuElem = getById(menuId);
	if (sideMenuElem.getAttribute('animstate') == 'showed'){
		sideMenuElem.hide();
		unlockScrollTimeout = setTimeout(unlockScroll, +sideMenuElem.getAttribute('animationDuration'));	
	}
}


const userOrders = JSON.parse(window.sessionStorage.getItem('userOrders')) || [];

function menuPressButton(button){
	switch (button){
	case 'Роли':
		showProductsList('Рол'); setMenuButtonState(0); break;
	case 'Сети':
		showProductsList('Сет'); setMenuButtonState(1); break;
	case 'Салати':
		showProductsList('Салат'); setMenuButtonState(2); break;
	case 'Напої':
		showProductsList('Напій'); setMenuButtonState(3); break;
	case 'Акції':
		actionList(); setMenuButtonState(4); break;
	}

	sessionStorage['menu'] = button;
	if (sessionStorage['redirect']){
		setTimeout(()=>{window.scrollTo(0, 400)}, 500);
		sessionStorage['redirect'] = '';
	}
}

function setMenuButtonState(buttonNum, buttonsWrapper = document.querySelector('.menu-buttons')){
	if (!buttonsWrapper) return;
	for (let i in buttonsWrapper.children){
		const btn = buttonsWrapper.children[i];
		if (typeof btn == 'object') btn.setAttribute('selected', i == buttonNum);
	}
}

async function showProductsList(type){
	const productsContainer = document.getElementById('product-list');

	if (!productsContainer) return;

	const data = await getProducts({type: type});
	products = data.products;
	let category = '';
	const title = document.createElement('h1');
	title.className = 'productsCatogotyTitle';
	productsContainer.innerHTML = ''; // Clear products container
	if (products){
		openedMenu = type;
		for (let product of products){
			if (product.categoryName != category) {
				category = title.innerHTML = product.categoryName;
				productsContainer.appendChild(title.cloneNode(true));
			}
			const e = document.querySelector('.product').cloneNode(true);
			const symb = '«»';
			e.className = 'product';
			e.querySelector('.product-title h2').innerHTML = product.typeName + ' ' + symb[0] + product.title + symb[1];
			try{
				e.querySelector('.product-description span').innerHTML = product.description;
			} catch {
				e.querySelector('.product-description span').innerHTML = '';
			}
			e.querySelector('.product-cost h2').innerHTML = product.cost;
			e.querySelector('.product-img img').src = product.imgsrc;
			e.setAttribute('productId', product.id);
			productsContainer.appendChild(e.cloneNode(true));
		}
	} else {
		productsContainer.innerHTML = '<h1 style="text-align: center; grid-column: 1/-1;">Нічого немає...</h1>';
	}

}

async function actionList(){
	const discounts = actions;
	const title = document.createElement('h1');
	title.innerHTML = "Акції";
	const productsContainer = document.getElementById('product-list');
	productsContainer.innerHTML = '';
	productsContainer.appendChild(title.cloneNode(true));
	if (discounts){
		for (let discount of discounts){
			const e = document.querySelector('.actionItem').cloneNode(true);
			e.className = 'actionItem';
			e.querySelector('.action_background_img').style.backgroundImage = "url(" + discount.imgsrc + ")";
			e.querySelector('.product-title h2').innerHTML = discount.title;
			e.querySelector('.product-description span').innerHTML = discount.description;
			// e.querySelector('.action_background_img').src = discount.imgsrc;
			document.getElementById('product-list').appendChild(e.cloneNode(true));
		}
	} else {
		document.getElementById('product-list').innerHTML += '<h3 style="text-align: center; width: 100%;">Нажаль, зараз ми не проводимо жодної акції😥</h3>';
	}
}

function ordersList(){
	// Clear orders list
	[...document.querySelectorAll('.user_orders')].forEach((orders_box) => {
		orders_box.querySelector('.orders_items').innerHTML = '';
		for (let order of userOrders){
			let e = document.querySelector('.orderItem').cloneNode(true);
			e.className = 'orderItem';
			e.querySelector('.orderTitle').innerHTML = order.title;
			e.querySelector('.ordCost').innerHTML = order.cost * order.count;
			e.querySelector('.ordImg').style.backgroundImage = 'url(' + order.imgsrc + ')';
			e.querySelector('.numbBox').value = order.count;
			e.querySelector('.deleteOrder').setAttribute('ordid', order.id);
			e.querySelector('.numbBox').setAttribute('ordid', order.id);
			e.querySelector('.innerOrderItem').setAttribute('ordid', order.id);
			orders_box.querySelector('.orders_items').appendChild(e.cloneNode(true));
		}
		if (userOrders.length){
			orders_box.querySelector('.totalCost').style.display = 'initial';
			orders_box.querySelector('.ordersEmpty').style.display = 'none';
			orders_box.querySelector('.ordersTotalCost').innerHTML = getTotalCost();
			orders_box.querySelector('.ordersFreeDelivery').innerHTML = 300 - getTotalCost()<=0?'Безкоштовна':'50 грн (До безкоштовної: ' + (300 - getTotalCost()) + 'грн)';
			orders_box.querySelector('.ordersTotalCostWithDelivery').innerHTML = 300 - getTotalCost()<=0?getTotalCost():getTotalCost()+50;
		} else {
			orders_box.querySelector('.ordersEmpty').style.display = 'initial';
			orders_box.querySelector('.totalCost').style.display = 'none';
		}
	})
}

function buyButtonPress(productId){
	const order = getProduct(productId, userOrders);
	if (order) {
		order.count ++;
	} else {
		userOrders.push({count: 1, ...getProduct(productId, products)});
	}
	saveUserOrders();
	ordersList();
	updateOrdersCountIcon();
}

function getProduct(productId, productsArr){
	for (let product of productsArr){
		if (product.id == productId) return product;
	}
	return undefined;
}
function getProductIndex(productId, productsArr){
	for (let i in productsArr){
		if (productsArr[i].id == productId) return i;
	}
}

function getTotalCost(orders = userOrders){
	return orders.reduce((sum, order) => sum + order.cost * order.count, 0);
}
function getTotalCount(orders = userOrders){
	return orders.reduce((sum, order) => sum + order.count, 0);
}

function saveUserOrders(){
	window.sessionStorage.setItem('userOrders', JSON.stringify(userOrders));
}

function updateOrderCosts(el){
	const id = +el.getAttribute('ordid');

	const orderCount = +el.value < 1 ? 1 : +el.value;
	const order = getProduct(id, userOrders);
	order.count = orderCount;
	updateOrdersCountIcon();
	const totalCost = getTotalCost();
	[...document.querySelectorAll('.user_orders')].forEach((elem) => {
		// Update product count and cost
		elem.querySelector('.innerOrderItem[ordId="'+ id +'"] .countAndCost').querySelector('input').value = orderCount;
		elem.querySelector('.innerOrderItem[ordId="'+ id +'"] .ordCost').innerHTML = order.cost * orderCount;

		// Update total costs
		elem.querySelector('.ordersTotalCost').innerHTML = totalCost;
		elem.querySelector('.ordersFreeDelivery').innerHTML = 300 - totalCost <= 0 ? 'Безкоштовна':'50 грн (До безкоштовної: ' + (300 - totalCost) + 'грн)';
		elem.querySelector('.ordersTotalCostWithDelivery').innerHTML = 300 - totalCost <= 0 ? totalCost : totalCost + 50;
	});
	saveOrders();
}
function deleteOrder(productId){
	userOrders.splice(+getProductIndex(productId, userOrders), 1);
	saveOrders();
	updateOrdersCountIcon();
	ordersList();
}

function updateOrdersCountIcon(){
	[...document.querySelectorAll('.order-count-icon')].forEach((elem) => {
		elem.innerHTML = getTotalCount();
	});
}

function saveOrders(){
	sessionStorage['userOrders'] = JSON.stringify(userOrders);
}

//Порівняння массивів
function isEqual(a,b){
	for (let i in b){ if (b[i] != a[i]) {return false;} }
	return true;
}

function openModalOnLoad() {
	// Open modal on load
	switch (sessionStorage['open_modal']){
		case 'auth':
			showModal("login_form");
			delete sessionStorage['open_modal'];
			break;
	}
};

// Select menu button
if (sessionStorage['menuPressButton']){
	menuPressButton(sessionStorage['menuPressButton']);
	sessionStorage.removeItem('menuPressButton');
} else {
	menuPressButton('Роли');
}

window.requestAnimationFrame(frame);
function frame(){
	window.requestAnimationFrame(frame);
	const header = document.querySelector('header');
	if (header){
		if (window.pageYOffset > 20){
			header.style.boxShadow = '0px 0px 10px #0006';
			header.style.padding = '5px  0';
		} else {
			header.style.boxShadow = 'none';
			header.style.padding = '10px  0';
		}
	}
}
