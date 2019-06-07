<header>
    <div class="bg-dark collapse" id="navbarHeader">
        <div class="container">
            <div class="row">
                <div class="col-sm-8 col-md-7 py-4">
                    <h4 class="text-white">О нас</h4>
                    <p class="text-muted"><?php echo $config['about'] ?></p>
                </div>
                <div class="col-sm-4 offset-md-1 py-4">
                    <h4 class="text-white">Контакты</h4>
                    <ul class="list-unstyled">
                        <li><a href="<?php echo $config['vk'] ?>" class="btn <?php if ($config['vk'] == '') {
                                                                                    echo 'disabled';
                                                                                } ?> text-white">ВКонтакте</a></li>
                        <li><a href="<?php echo $config['instagram'] ?>" class="btn <?php if ($config['instagram'] == '') {
                                                                                        echo 'disabled';
                                                                                    } ?> text-white">Instagram</a></li>
                        <li><a href="<?php echo $config['twitter'] ?>" class="btn <?php if ($config['twitter'] == '') {
                                                                                        echo 'disabled';
                                                                                    } ?> text-white">Twitter</a></li>
                        <li><a href="<?php echo $config['facebook'] ?>" class="btn <?php if ($config['facebook'] == '') {
                                                                                        echo 'disabled';
                                                                                    } ?> text-white" disabled>Facebook</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="navbar navbar-dark bg-dark shadow-sm" style="margin-bottom: 20px;">
        <div class="container d-flex justify-content-between">
            <a href="/" class="navbar-brand d-flex align-items-center">
                <!-- Заголовок сайта -->
                <strong><?php echo $config['title'] ?></strong>
            </a>
            <button class="navbar-toggler collapsed" type="button" data-toggle="collapse" data-target="#navbarHeader" aria-controls="navbarHeader" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>
    </div>
</header>