<?php

include('includes/db.php');

// ---------------------------Запросы---------------------------
$articles = mysqli_query($connection, "SELECT * FROM `articles` ORDER BY `id` DESC");
$categories = mysqli_query($connection, "SELECT * FROM `categories`");
$comments = mysqli_query($connection, "SELECT * FROM `comments`");
// -----------------------Конец запросов-----------------------
?>
<!-- Вывести текущие время и дату  -->
<?php 
echo 'Сегодня: ' .  date('H:i d.m.Y') . '<hr>' 
?>
<!-- Создание новой статьи -->
<h3>Создать новую статью </h3>
<form method="POST">
	<input type="text" placeholder="Введите заголовок статьи" name="title">
	<input type="text" placeholder="Введите текст статьи" name="text">
	<input type="submit" value="Отправить">
</form>	

<?php 
if (isset($_POST['title']) and isset($_POST['text'])){

	$title = $_POST['title'];
	$text = $_POST['text'];
	if ($title != '' or $text != ''){

		$create = mysqli_query($connection, "INSERT INTO `articles` (`title`, `text`) VALUES ('{$_POST['title']}', '{$_POST['text']}')");

		if ($create){
			echo 'Нажмите кнопку Отправить ещё раз' . '<br>';
		}else{
			echo 'Информация не занесена в базу данных' . '<br>';
		}
	}
}
?>
<!-- Список категорий -->
<?php
if (mysqli_num_rows($categories) == 0) {
	echo 'Катеогрий не найдено.';
} else {
	echo 'Категории: <br>';
	?>

	<ul>
		<?php 
		while ($cat = mysqli_fetch_assoc($categories)){
			// Запрос к БД
			$art_count = mysqli_query($connection, "SELECT COUNT(id) AS `total_count` FROM `articles` WHERE `categorie_id` = " . $cat['id']);
			// Вывод категории ($cat['categorie']) и количество статей в данной категории (mysqli_fetch_assoc($art_count)['total_count'])
			echo '<li>' . $cat['categorie'] . ' (Статей: ' . mysqli_fetch_assoc($art_count)['total_count'] . ')</li>'; 
		}
		?>
	</ul>
	<hr>
	<?php
}
?>


<!-- Статьи -->
<?php
if (mysqli_num_rows($articles) == 0) {
	echo 'Статей не найдено.';
} else {
	echo 'Статьи: <br>';

	?>
	<ul>
		<?php
		while ($art = mysqli_fetch_assoc($articles)) {
			if ($art['categorie_id'] != 0) {
				// Запрос к БД
				$cat = mysqli_query($connection, "SELECT * FROM `categories` WHERE `id` = " . $art['categorie_id']);
				echo '<li>' . 'Категория: ' . mysqli_fetch_assoc($cat)['categorie'] . '</li>';
			}
			echo $art['title'] . '<br>';
			echo $art['text'] . '<br>';
			echo 'Просмотров: ' . $art['views'] . '<br>';
			// Дата публикации в timeStamp
			$start_date_timeStamp = strtotime($art['pubdate']);
			echo 'Дней с момента публикации: ';
			if (floor((time() - $start_date_timeStamp)/86400) < 1) {
				echo '0';
			} else {
				echo floor((time() - $start_date_timeStamp)/86400);
			}
			echo '<hr>';
		}
		?>
	</ul>
	<?php 
}
?>


<?php 
// Close connetcion
mysqli_close($connection)
?>