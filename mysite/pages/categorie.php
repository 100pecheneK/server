<?php
require "../includes/config.php"
?>
<!doctype html>
<html lang="ru">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="../style/css/bootstrap.min.css">
    <link rel="stylesheet" href="../style/css/main.css">
    <title><?php echo $config['title'] ?></title>
</head>

<body>
    <!-- ---------------------------Header--------------------------- -->
    <?php include "../includes/header.php" ?>
    <!-- -------------------------End header------------------------- -->
    <!-- ------------------------Новые статьи------------------------ -->
    <main role="main" class="container">
        <div class="row">
            <?php include "../includes/sidebar.php" ?>
            <div class="col-md-8 blog-main">
                <div class="col">
                    <h3 class="font-italic">Все статьи из выбранной категории</h3>
                </div>
                <div class="container">
                    <div class="row">
                        <?php
                        $per_page = 4;
                        $page = 1;

                        if (isset($_GET['page'])) {
                            $page = (int)$_GET['page'];
                        }

                        $total_count_q = mysqli_query($connection, "SELECT COUNT(id) AS `total_count` FROM `articles` WHERE `categorie_id` = ". (int)$_GET['id']);
                        $total_count = mysqli_fetch_assoc($total_count_q);
                        $total_count = $total_count['total_count'];

                        $total_pages = ceil($total_count / $per_page);

                        if ($page < 1 || $page > $total_pages) {
                            $page = 1;
                        }

                        $offset = ($per_page * $page) - $per_page;

                        $articles = mysqli_query($connection, "SELECT * FROM `articles` WHERE `categorie_id` = ". (int)$_GET['id'] . " ORDER BY `id` DESC LIMIT $offset, $per_page");
                        $articles_exist = true;

                        // <!-- Контейнер с новыми статьями -->
                        if (mysqli_num_rows($articles) <= 0) {
                            ?>
                            <h4 class="featurette-heading">Нет статей из выбранной категории</h4>
                        <?php
                    }
                    while ($art = mysqli_fetch_assoc($articles)) {
                        // Это переменная считает какая сейчас статья, что бы исполнить перенос статей на следующюю строчку
                        
                        ?>
                            <!-- Карточки со статьями -->
                            <div class="col-md-12" style="margin-bottom: 15px;">
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
                                        <img src="../images/articles/<?php echo $art['image'] ?>" alt="image" class="img-thumbnail" width="100%" height="225">
                                    <?php
                                }
                                ?>
                                    <div class="card-body">
                                        <h4 class="font-italic"><?php echo $art['title'] ?></h4>
                                        <?php
                                        $art_cat = false;
                                        // Поиск категории статьи
                                        // Сравнивает с категорией из сайдбара(пока не решил как сделать иначе)
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
                                                <div class="col">
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
                <!-- Пегинация -->
                <div class="col-12">
                    <?php
                    if ($total_pages <= 4) {
                        $page_left = 1;
                        $page_right = $total_pages;
                    } else {
                        $page_show = 2;
                        $page_left = $page - $page_show;
                        $page_right = $page + $page_show;
                        if ($page_left < 1) {
                            $page_left = 1;
                        }
                        if ($page_right > $total_pages) {
                            $page_right = $total_pages;
                        };
                        if ($page == 2) {
                            $page_right = 5;
                        }
                        if ($page == 1) {
                            $page_right = 5;
                        }
                        if ($page == $total_pages - 1) {
                            $page_left = $total_pages - 4;
                        }
                        if ($page == $total_pages) {
                            $page_left = $total_pages - 4;
                        }
                    }
                    if ($articles_exist) {
                        ?>
                        <nav aria-label="Page navigation example">
                            <ul class="pagination justify-content-center">
                                <li class="page-item <?php if ($page <= 1) echo 'disabled'; ?> "><a class="page-link" href="categorie.php?id=<?php echo $_GET['id'] ?>&page=<?php echo 1; ?>">Первая</a></li>
                                <li class="page-item <?php if ($page <= 1) echo 'disabled'; ?> "><a class="page-link" href="categorie.php?id=<?php echo $_GET['id'] ?>&page=<?php echo ($page - 1); ?>">Назад</a></li>
                                <li class="page-item <?php if ($page >= $total_pages) echo 'disabled'; ?> "><a class="page-link" href="categorie.php?id=<?php echo $_GET['id'] ?>&page=<?php echo ($page + 1); ?>">Вперёд</a></li>
                                <li class="page-item <?php if ($page >= $total_pages) echo 'disabled'; ?> "><a class="page-link" href="categorie.php?id=<?php echo $_GET['id'] ?>&page=<?php echo $total_pages; ?>">Последняя</a></li>
                            </ul>
                            <ul class="pagination justify-content-center">
                                <li class="page-item <?php if ($page - 3 < 1) echo 'disabled'; ?>"><a class="page-link" href="categorie.php?id=<?php echo $_GET['id'] ?>&page=<?php echo ($page - 3); ?>">...</a></li>
                                <?php
                                for ($i = $page_left; $i <= $page_right; $i++) {
                                    ?>
                                    <li class="page-item <?php if ($i == $page) echo 'disabled'; ?>"><a class="page-link" href="categorie.php?id=<?php echo $_GET['id'] ?>&page=<?php echo $i; ?>"><?php echo $i ?></a></li>
                                <?php
                            }
                            ?>
                                <li class="page-item <?php if ($page + 3 > $total_pages) echo 'disabled'; ?>"><a class="page-link" href="categorie.php?id=<?php echo $_GET['id'] ?>&page=<?php echo ($page + 3); ?>">...</a></li>
                            </ul>

                        </nav>
                    <?php
                }
                ?>
                </div>
                <!-- Конец пегинации -->
            </div>
            <!-- ---------------------Конец новых статей--------------------- -->
        </div>
    </main>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="../style/js/bootstrap.min.js"></script>
</body>

</html>