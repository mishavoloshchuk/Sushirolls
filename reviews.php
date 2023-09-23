<?php require_once($_SERVER['DOCUMENT_ROOT'].'/db/config.php'); 
	$is_review_exists = false;
	if ($loggedUser){
		$is_review_exists = $cnct->query("SELECT * FROM reviews WHERE authorId = $loggedUserId")->fetch_assoc() == true;
	}
?>
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

	<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
	<title>Sushi Rolls - Відгуки користувачів</title>

	<!-- Dark mode -->
	<script type="text/javascript" src="js/dark_mode_switcher.js"></script>
	<!-- Components including -->
	<script type="text/javascript" src="js/component_import.js"></script>

	<script type="text/javascript">
window.onload = function(){
	const stars = document.getElementById('stars');
	const starsCount = 5;
	if (stars){
		stars.addEventListener('click', function(e){
			let starId = +e.target.getAttribute('star_number');
			if (typeof starId == 'number'){
				for (let i = starsCount; i--;){
					if (i <= starId){
						document.getElementById('stars').children[i].src = '../ico/starActiv.png';
					} else {
						document.getElementById('stars').children[i].src = '../ico/star.png';
					}			
				}
				sendComment.value = starId+1;
			}
		});
	}	
}
	</script>
</head>
<body>
	<include-component href="components\header.php" onload="updateOrdersCountIcon(); ordersList();"></include-component>
	<div class="margin-zone"></div>
	<br>
	<section class="container about-us select-color">
		<? if (!$is_review_exists): ?>
			<button class="orderButton" onclick="<? echo $loggedUser ? 'add_review_modal.show()' : 'showModal(\'login_form\');'; ?>"><a>Залиште відгук</a></button>
		<? endif; ?>
		<div style="display: flex; justify-content: center">
			<h1>Відгуки користувачів</h1>
		</div>
		<div class="reviews">
			<? 
			$userId = $loggedUser ? $loggedUserId : -1;
			$sql = $cnct->query("SELECT * FROM reviews ORDER BY FIELD(authorId, $userId, authorId), `date`");
				$rating_emojy = [
					'1' => '😠 Погано!',
					'2' => '🙁 Так собі',
					'3' => '😐 Нормально',
					'4' => '🙂 Добре',
					'5' => '😊 Відмінно!',
				];
				while($review = $sql->fetch_assoc()): ?>
					<div class="review_wrapper" style="<? echo $loggedUserId == $review['authorId'] ? 'border: 2px solid orange' : ''; ?>">
						<div class="review">
							<?php if ($loggedUserId == $review['authorId']): ?>
								<div class="review_options">
									<button class="admin_control_button admin_control_open_btn material-symbols-outlined" onclick="const sib = this.nextElementSibling; sib.show(); sib.hideTmt = setTimeout(() => sib.hide(), 5000);" style="border: 1px solid var(--main-oposite-low-opacity);">more_horiz</button>
									<div class="admin_control" animstate='hidden' animationduration='200' onmouseleave="this.hide(); clearTimeout(this.hideTmt)">
										<button class="admin_control_button material-symbols-outlined" style="background-color: #F66; color: #222;" title="Видалити" onclick="deleteReview()">delete</button>
										<button class="admin_control_button material-symbols-outlined" title="Редагувати" onclick="add_review_modal.show()">edit</button>
									</div>
								</div>
							<? endif; ?>
							<div class="rev_name_and_date">
								<h2><? echo $review['author_name']; ?></h2>
								<span style="display: flex; align-items: center;">
									<span style="font-weight: 800; font-size: 22px;">Оцінка: </span>
									<span class="review_emojy_rating" style="padding: 5px"><? echo $rating_emojy[$review['rating']]; ?></span>
								</span>
								<span class="rev_date"><? echo $review['date']; ?></span>
							</div>
							<? if (trim($review['description'])): ?>
								<p class="review_text">
									<? echo $review['description']; ?>
								</p>
							<? endif; ?>
						</div>
					</div>
			<? 	endwhile; ?>
		</div>
	</section>
	<? if ($loggedUser): ?>
		<div class="modal" id="add_review_modal" animstate="hidden" animationDuration="300">
			<div class="modal_wrapper">
				<div class="login_form_wrapper">
					<? $usersReview = $cnct->query("SELECT * FROM reviews WHERE authorId = $loggedUserId")->fetch_assoc(); ?>
					<h1><? echo $is_review_exists ? 'Редагувати відгук' : 'Створити відгук'; ?></h1>
					<form action="" onsubmit="add_review(event);" modalMode="<? echo $is_review_exists ? 'edit' : 'create'; ?>">
						<div class="loader" showed="false"></div>
						<span class="error" showed="false"></span>
						<input type="text" name="author_name" class="textInput" placeholder="Ім'я автора" value="<? echo $is_review_exists ? $usersReview['author_name'] : $loggedUser['login']; ?>" required>
						<label>Оцінка:</label><br>
						<span class="rating" onclick="setRating(event);">
							<span class="emojy_rating" selected="false">😠</span>
							<span class="emojy_rating" selected="false">🙁</span>
							<span class="emojy_rating" selected="false">😐</span>
							<span class="emojy_rating" selected="false">🙂</span>
							<span class="emojy_rating" selected="false">😊</span>
						</span>
						<textarea class="text_area" name="review_text" rows="5" placeholder="Розкажіть більше (не обов'язково)"><? echo $is_review_exists ? $usersReview['description'] : ''; ?></textarea>
						<button type="submit" class="orderButton" name="review" value=""><? echo $is_review_exists ? 'Готово!' : 'Створити відгук'; ?></button>
					</form>
				</div>
			</div>
			<div class="tint" onclick="hideModal('add_review_modal');"></div>
		</div>
	<? endif; ?>
	<!-- Footer -->
	<include-component href="components\footer.html"></include-component>
</body>
<script type="text/javascript" src="js/script.js"></script>
<script type="text/javascript">
	<?  
	if ($loggedUser && $is_review_exists){
			$rating = $usersReview['rating'] - 1;
			echo "document.querySelector('.rating').children[$rating].setAttribute('selected', true);";
			echo "document.querySelector('.rating').setAttribute('value', $rating);";
	}
	?>
	function setRating(event){
		const rating = event.target.closest('.rating');
		let count = 1;
		for (let emojy of rating.children){
			let value = false;
			if (emojy == event.target) {
				value = true;
				rating.setAttribute('value', count);
			}
			emojy.setAttribute('selected', value);
			count ++;
		}
		if (event.target == rating){
			rating.setAttribute('value', '');
		}
	}
</script>
</html>