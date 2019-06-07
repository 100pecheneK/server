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
<?php
    $categories_q = mysqli_query($connection, "SELECT * FROM `categories`");
    $categories = array();
    while ($cat = mysqli_fetch_assoc($categories_q)) {
        $categories[] = $cat;
    }
    ?>



    <?php
    $data = $_POST;
    $title = $data['title'];
    $text = $data['text'];
    $categorie_id = 1;
    foreach($categories as $cat) {
        if ($data['categorie_id'] == $cat['categorie']) {
            $categorie_id = $cat['id'];
            break;
        };
    } 
        
    ?>
    <!-- header -->
    <?php include "../includes/header.php"; ?>
    
    <div class="container">
        <form action="/pages/create_article.php" method="POST">
            <div class="form-group">
                <label for="exampleFormControlInput1">Заголовок</label>
                <input type="text" class="form-control" id="exampleFormControlInput1" name="title" placeholder="Без заголовка" value="<?php echo @$data['title'] ?>">
            </div>
            <div class="form-group">
                <label for="exampleFormControlTextarea1">Текс</label>
                <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" name="text" placeholder="Без текста" value="<?php echo @$data['text'] ?>"></textarea>
            </div>
            <div class="form-group">
                <label for="exampleFormControlSelect1">Категория</label>
                <select class="form-control" name="categorie_id" id="exampleFormControlSelect1" value="<?php echo @$data['categorie_id'] ?>">
                    <?php
                    foreach ($categories as $cat) {
                        echo '<option>' . $cat['categorie'] . '</option>';
                    }
                    ?>
                </select>
            </div>

            <button type="submit" class="btn btn-primary" name="do_create">Создать</button>
            <?php if (isset($data['do_create'])) {
                mysqli_query($connection, "INSERT INTO `articles` (`title`, `text`, `categorie_id`) VALUES ('$title', '$text', '$categorie_id')");
                echo '<h5 class="font-italic text-success">Статья создана</h5>';
            }
            ?>

        </form>
    </div>
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="../style/js/bootstrap.min.js"></script>
</body>


</html>