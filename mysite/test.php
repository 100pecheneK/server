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

    <div class="container" style="padding-bottom: 15px;">
        <div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
            <div class="carousel-inner">
                <ol class="carousel-indicators">
                    <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
                    <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
                    <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
                </ol>
                <?php
                $articles = mysqli_query($connection, "SELECT * FROM `articles` ORDER BY `views` DESC LIMIT 3");
                $i = 1;
                while ($art = mysqli_fetch_assoc($articles)) {

                    if ($i > 3) $i = 1;
                    ?>
                    <div class="carousel-item <?php if ($i == 1) echo 'active'; ?>">
                        <img class="d-block w-100" src="images/background_image.jpg" height="400" alt="Первый слайд">
                        <div class="container">
                            <div class="carousel-caption">
                                <div class="row justify-content-center">
                                    <h3><?php echo $art['title'] ?></h3>
                                </div>
                                <div class="row d-none d-md-block">
                                    <p><?php echo mb_substr($art['text'], 0, 500, 'utf-8') . '...' ?></p>
                                </div>
                                <div class="row justify-content-center">
                                    <p><a class="btn btn-secondary" href="/pages/article.php?id=<?php echo $art['id'] ?>" role="button">Читать дальше »</a></p>
                                </div>
                            </div>
                        </div>



                    </div>
                    <?php
                    $i++;
                }
                ?>
            </div>
            <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>
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
                <div class="container">
                    <div class="row">
                        <div class="col-4">
                            <h3 class="font-italic">Новые статьи</h3>
                        </div>
                    </div>
                </div>

                <!-- Контейнер с новыми статьями -->
                <div class="container">
                    <div class="row">
                        <?php
                        // Пегинация
                        $per_page = 4;
                        $page = 1;

                        if (isset($_GET['page'])) {
                            $page = (int)$_GET['page'];
                        }

                        $total_count_q = mysqli_query($connection, "SELECT COUNT(id) AS `total_count` FROM `articles`");
                        $total_count = mysqli_fetch_assoc($total_count_q);
                        $total_count = $total_count['total_count'];

                        $total_pages = ceil($total_count / $per_page);

                        if ($page < 1 || $page > $total_pages) {
                            $page = 1;
                        }

                        $offset = ($per_page * $page) - $per_page;

                        $articles = mysqli_query($connection, "SELECT * FROM `articles` ORDER BY `id` DESC LIMIT $offset, $per_page");
                        $articles_exist = true;
                        if (mysqli_num_rows($articles) <= 0) {
                            $articles_exist = false;
                            ?>

                            <h4 class="featurette-heading">Нет статей</h4>
                        <?php
                    }
                    include "crop.php";

                    while ($art = mysqli_fetch_assoc($articles)) {
                        cropImage("images/articles/" . $art['image'], "images/" . $art['image'], 400, 300);
                        ?>
                        
                            <!-- Карточки со статьями -->
                            <div class="col-md-6" style="margin-bottom: 15px;">
                                <div class="card mb shadow-sm" >
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
                                        <img src="images/<?php echo $art['image'] ?>" alt="image" class="img-thumbnail" width="100%" height="225">
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

                                            <div class="row">
                                                <div class="col-12">
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
                                </div>
                            </div>
                            <!-- Возможно будет работать без этого -->
                            <!-- Если это вторая статья, то спустить следующюю вниз. Примерно так работает w-100 в Bootstrap 4.  -->
                        <?php
                    }
                    ?>
                    </div>
                </div>
            </div>
            <!-- ---------------------Конец новых статей--------------------- -->
        </div>

    </main>
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="style/js/bootstrap.min.js"></script>
</body>

</html>