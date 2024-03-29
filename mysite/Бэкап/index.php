<?php
require "includes/config.php";
?>
<!doctype html>
<html lang="ru">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="style/css/bootstrap.min.css">
    <link rel="stylesheet" href="style/css/main.css">
    <title><?php echo $config['title'] ?></title>
</head>

<body>
    <!-- ---------------------------Header--------------------------- -->
    <?php include "includes/header.php" ?>
    <!-- -------------------------End header------------------------- -->
    <!-- ------------------------Топ читаемых------------------------ -->
    <div class="container">
        <div class="jumbotron myJumbotron">
            <h1 class="font-italic">Топ читаемых</h1>
            <div class="row">
                <!-- Запрос на 3 статьи с самым большим количеством просмотров -->
                <?php
                $articles = mysqli_query($connection, "SELECT * FROM `articles` ORDER BY `views` DESC LIMIT 3");
                while ($art = mysqli_fetch_assoc($articles)) {
                    ?>
                    <div class="col-md-4">
                        <!-- Заголовок статьи -->
                        <h2><?php echo $art['title'] ?></h2>
                        <!-- Текст статьи обрезанный до 300 символов -->
                        <p><?php echo mb_substr($art['text'], 0, 300, 'utf-8') . '...' ?></p>
                        <!-- Кнопка перенаправляет на страницу с данной статьёй -->
                        <p><a class="btn btn-secondary" href="/pages/article.php?id=<?php echo $art['id'] ?>" role="button">Читать дальше »</a></p>
                    </div>
                <?php
            }
            ?>
            </div>
        </div>
    </div>
    <!-- ---------------------Конец топ читаемых--------------------- -->
    <!-- ------------------------------------ -->
    <!-- ------------------------Новые статьи------------------------ -->
    <main role="main" class="container">
        <div class="row">
            <!-- Подключаю сайдбар -->
            <!-- Сайдбар на 4 колонки -->
            <?php include "includes/sidebar.php" ?>
            <!-- Статьи на 8 колонок -->
            <div class="col-md-8 blog-main">
                <div class="col-4">
                    <h3 class="font-italic">Новые статьи</h3>
                </div>
                <!-- Контейнер с новыми статьями -->
                <div class="container">
                    <div class="row">
                        <?php
                        // Запрос на 20 последних статей
                        $articles = mysqli_query($connection, "SELECT * FROM `articles` ORDER BY `id` DESC LIMIT 20");

                        while ($art = mysqli_fetch_assoc($articles)) {
                            // Это переменная считает какая сейчас статья, что бы исполнить перенос статей на следующюю строчку
                            $art_count = 1;
                            ?>
                            <!-- Карточки со статьями -->
                            <div class="col-md" style="margin-bottom: 15px;">
                                <div class="card mb shadow-sm">
                                    <?php
                                    if ($art['image'] == '') {
                                        ?>
                                        <!-- Это был код Bootstrap 4 и я решил его оставить -->
                                        <!-- Это просто серый фон, отображаемый если картинки нет -->
                                        <svg class="bd-placeholder-img card-img-top" width="100%" height="225" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid slice" focusable="false" role="img" aria-label="Placeholder: Thumbnail"></svg>
                                    <?php
                                } else {
                                    ?>
                                        <!-- Если картинка есть, то она есть -->
                                        <img src="images/articles/<?php echo $art['image'] ?>" alt="image" class="img-thumbnail" width="100%" height="225">
                                    <?php
                                }
                                ?>
                                    <div class="card-body">
                                        <!-- Заголовок статьи -->
                                        <h4 class="font-italic"><?php echo $art['title'] ?></h4>
                                        <?php
                                        // Поиск категории статьи
                                        // Сравнивает с категорией из сайдбара(пока не решил как сделать иначе)
                                        $art_cat = false;
                                        foreach ($categories as $cat) {
                                            if ($cat['id'] == $art['categorie_id']) {
                                                $art_cat = $cat;
                                                break;
                                            }
                                        }
                                        ?>
                                        <!-- ?id= - передаёт параметр для $_GET -->
                                        <!-- Жмяк по категории открывает страницу со всеми статьями из данной категории -->
                                        <!-- В будующем сделаю пегинацию -->
                                        <h6>Категория: <a class="" href="/pages/categorie.php?id=<?php echo $art_cat['id'] ?>" class="font-italic"><?php echo $art_cat['categorie'] ?></a></h6>
                                        <!-- Обрезка текста до 100 символов -->
                                        <p class="card-text"><?php echo mb_substr($art['text'], 0, 100, 'utf-8') . '...'  ?></p>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="btn-group">
                                                <!-- Кнопка читать -->
                                                <a href="/pages/article.php?id=<?php echo $art['id'] ?>" class="btn btn-outline-primary">Читать</a>
                                                <!-- Кнопка лайк -->
                                                <a class="btn btn-outline-secondary">Лайк</a>
                                            </div>
                                            <!-- Количество просмотров -->
                                            <small class="text-muted"><?php echo $art['views'] ?> просмотр(ов)</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Возможно будет работать без этого -->
                            <!-- Если это вторая статья, то спустить следующюю вниз. Примерно так работает w-100 в Bootstrap 4.  -->
                            <?php if ($art_count % 2 == 0) {
                                ?>
                                <div class="w-100"></div>
                            <?php
                        }
                        $art_count += 1;
                    }
                    ?>
                    </div>
                </div>
            </div>
            
            <!-- ---------------------Конец новых статей--------------------- -->
        </div>
        
    </main>
    <nav aria-label="Page navigation example">
  <ul class="pagination">
    <li class="page-item"><a class="page-link" href="#">Previous</a></li>
    <li class="page-item"><a class="page-link" href="#">1</a></li>
    <li class="page-item"><a class="page-link" href="#">2</a></li>
    <li class="page-item"><a class="page-link" href="#">3</a></li>
    <li class="page-item"><a class="page-link" href="#">Next</a></li>
  </ul>
</nav>
   
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="style/js/bootstrap.min.js"></script>
</body>

</html>