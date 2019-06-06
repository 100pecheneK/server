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
            <hr>
        </div>
        <main role="main" class="container">

            <div class="row">
                <?php include "../includes/sidebar.php" ?>
                <div class="col-md-8 blog-main">
                    <div class="col-4">
                        <h4 class="font-italic">Читайте так же</h4>
                    </div>
                    <!-- Контейнер с новыми статьями -->
                    <div class="container">
                        <div class="row">
                            <?php
                            $articles = mysqli_query($connection, "SELECT * FROM `articles` WHERE `categorie_id` = " . $article['categorie_id'] . " ORDER BY `id` DESC LIMIT 20");
                            while ($art = mysqli_fetch_assoc($articles)) {

                                $art_count = 1;
                                ?>
                                <div class="col-md-6" style="margin-bottom: 15px;">
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
                                <?php if ($art_count % 2 == 0) {
                                    ?>
                                    <div class="w-100"></div>
                                <?php
                            }
                            $art_count += 1;
                        }
                        ?>
                            <!-- Конец статей для вида -->
                        </div>
                    </div>
                    <!-- тут сайдбар -->

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