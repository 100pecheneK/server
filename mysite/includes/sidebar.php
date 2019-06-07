<?php
$categories_q = mysqli_query($connection, "SELECT * FROM `categories`");
$categories = array();
while ($cat = mysqli_fetch_assoc($categories_q)) {
    $categories[] = $cat;
}
?>
<aside class="col-md-4 blog-sidebar">
    <div class="row">
        <div class="col">
            <a class="btn btn-primary" href="/pages/articles.php" role="button">Все статьи</a>            
            <a class="btn btn-primary" href="/pages/create_article.php" role="button">Создать статью</a> 
        </div>
    </div>
    <div class="row">
        <div class="p-3">
            <h4 class="font-italic">Категории</h4>
            <ol class="list-unstyled mb-0">
                <?php
                foreach ($categories as $cat) {
                    ?>
                    <li><a href="../pages/categorie.php?id=<?php echo $cat['id'] ?>"><?php echo $cat['categorie'] ?></a></li>
                    <hr>
                <?php
            }
            ?>
            </ol>
        </div>
    </div>
</aside>