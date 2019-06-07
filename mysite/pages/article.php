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
    <!-- header -->
    <?php include "../includes/header.php" ?>

    <?php
    $article_q = mysqli_query($connection, "SELECT * FROM `articles` WHERE `id` = " . (int)$_GET['id']);
    $article = mysqli_fetch_assoc($article_q);
    mysqli_query($connection, "UPDATE `articles` SET `views` = `views` + 1 WHERE `id` =  " . (int)$article['id']);
    if (mysqli_num_rows($article_q) <= 0) {
        ?>
        <div class="container">
            <div class="row featurette">
                <div class="col-md-5">
                    <div class="text-center">
                        <img src="../images/notfound.jpg" alt="image" class="img-thumbnail rounded">
                    </div>
                </div>
                <div class="col-md-7">
                    <h2 class="featurette-heading">Статья не найдена</h2>
                </div>
            </div>
        </div>
    <?php
} else {
    ?>
        <div class="container">
            <div class="row featurette">
                <div class="col-md-5">
                    <div class="text-center">
                        <img src="../images/articles/<?php echo $article['image'] ?>" alt="image" class="img-thumbnail rounded">
                    </div>
                </div>
                <div class="col-md-7">
                    <h2 class="featurette-heading"><?php echo $article['title'] ?></h2>
                    <p class="lead"><?php echo $article['text'] ?></p>
                    <small class="text-muted"><?php echo $article['views'] ?> просмотр(ов)</small>
                </div>

            </div>
            <a name="top"></a>
            <hr>
            
        </div>
        <main role="main" class="container">

            <div class="row">
                <?php include "../includes/sidebar.php" ?>
                <div class="col-md-8 blog-main">
                    <div class="col-4">
                        
                        <h4 class="font-italic">Похожие статьи</h4>
                    </div>
                    <!-- Контейнер с новыми статьями -->
                    <div class="container">
                        <div class="row">
                            <?php
                                $per_page = 4;
                                $page = 1;

                                if (isset($_GET['page'])) {
                                    $page = (int)$_GET['page'];
                                }

                                $total_count_q = mysqli_query($connection, "SELECT COUNT(id) AS `total_count` FROM `articles` WHERE `categorie_id` = " . $article['categorie_id']);
                                $total_count = mysqli_fetch_assoc($total_count_q);
                                $total_count = $total_count['total_count'];

                                $total_pages = ceil($total_count / $per_page);

                                if ($page < 1 || $page > $total_pages) {
                                    $page = 1;
                                }

                                $offset = ($per_page * $page) - $per_page;

                                $articles = mysqli_query($connection, "SELECT * FROM `articles` WHERE `categorie_id` = " . $article['categorie_id'] . " ORDER BY `id` DESC LIMIT $offset, $per_page");
                                $articles_exist = true;

                                if (mysqli_num_rows($articles) <= 0) {
                                    ?>
                                     <h4 class="featurette-heading">Нет статей</h4>
                                <?php }

                            while ($art = mysqli_fetch_assoc($articles)) {

                                ?>
                                    <div class="col-md-12" style="margin-bottom: 15px;">
                                        <div class="card mb shadow-sm">
                                            <?php
                                            if ($art['image'] == '') {
                                                ?>
                                                <svg class="bd-placeholder-img card-img-top" width="100%" height="225" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid slice" focusable="false" role="img" aria-label="Placeholder: Thumbnail">
                                                </svg>
                                            <?php
                                        } else {
                                            ?>
                                                <!-- <img src="images/background_image.jpg" alt="image" class="img-thumbnail" width="100%" height="225"> -->
                                                <img src="../images/articles/<?php echo $art['image'] ?>" alt="image" class="img-thumbnail" width="100%" height="225">

                                            <?php
                                        }
                                        ?>
                                            <div class="card-body">
                                                <h4 class="font-italic"><?php echo $art['title'] ?></h4>
                                                <?php
                                                $art_cat = false;
                                                foreach ($categories as $cat) {
                                                    if ($cat['id'] == $art['categorie_id']) {
                                                        $art_cat = $cat;
                                                        break;
                                                    }
                                                }
                                                ?>
                                                <h6>Категория: <a class="" href="/pages/categorie.php?id=<?php echo $art_cat['id'] ?>" class="font-italic"><?php echo $art_cat['categorie'] ?></a></h6>
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

                                <?php


                            }
                            ?>
                                <!-- Конец статей для вида -->
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
                                        <li class="page-item <?php if ($page <= 1) echo 'disabled'; ?> "><a class="page-link" href="article.php?id=<?php echo $_GET['id'] ?>&page=<?php echo 1; ?>#top">Первая</a></li>
                                        <li class="page-item <?php if ($page <= 1) echo 'disabled'; ?> "><a class="page-link" href="article.php?id=<?php echo $_GET['id'] ?>&page=<?php echo ($page - 1); ?>">Назад</a></li>
                                        <li class="page-item <?php if ($page >= $total_pages) echo 'disabled'; ?> "><a class="page-link" href="article.php?id=<?php echo $_GET['id'] ?>&page=<?php echo ($page + 1); ?>#top">Вперёд</a></li>
                                        <li class="page-item <?php if ($page >= $total_pages) echo 'disabled'; ?> "><a class="page-link" href="article.php?id=<?php echo $_GET['id'] ?>&page=<?php echo $total_pages; ?>#top">Последняя</a></li>
                                    </ul>
                                    <ul class="pagination justify-content-center">
                                        <li class="page-item <?php if ($page - 3 < 1) echo 'disabled'; ?>"><a class="page-link" href="article.php?id=<?php echo $_GET['id'] ?>&page=<?php echo ($page - 3); ?>#top">...</a></li>
                                        <?php
                                        for ($i = $page_left; $i <= $page_right; $i++) {
                                            ?>
                                            <li class="page-item <?php if ($i == $page) echo 'disabled'; ?>"><a class="page-link" href="article.php?id=<?php echo $_GET['id'] ?>&page=<?php echo $i; ?>#top"><?php echo $i ?></a></li>
                                        <?php
                                    }
                                    ?>
                                        <li class="page-item <?php if ($page + 3 > $total_pages) echo 'disabled'; ?>"><a class="page-link" href="article.php?id=<?php echo $_GET['id'] ?>&page=<?php echo ($page + 3); ?>#top">...</a></li>
                                    </ul>

                                </nav>
                            <?php
                        }
                        ?>
                        </div>
                        <!-- Конец пегинации -->
                    </div>


                </div>
        </main>
    <?php

}
?>





    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="../style/js/bootstrap.min.js"></script>
</body>

</html>