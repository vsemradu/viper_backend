<!DOCTYPE html>
<html lang="ru">
    <head>
        <script src="<?php echo Yii::app()->request->baseUrl; ?>/bower_components/jquery/dist/jquery.min.js"></script>
        <script src="<?php echo Yii::app()->request->baseUrl; ?>/bower_components/jquery-ui/jquery-ui.min.js"></script>
        <script src="<?php echo Yii::app()->request->baseUrl; ?>/bower_components/jquery.scrollTo/jquery.scrollTo.min.js"></script>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">
        <title><?php echo CHtml::encode($this->pageTitle); ?></title>
        <link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/bower_components/bootstrap/dist/css/bootstrap.min.css" />
        <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/bower_components/moment/min/moment.min.js"></script>
        <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/bower_components/moment/min/moment-with-locales.min.js"></script>
        <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/bower_components/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js"></script>
        <link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/bower_components/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css" />
        <link href="<?php echo Yii::app()->request->baseUrl; ?>/css/justified-nav.css" rel="stylesheet">
        <link href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.css" rel="stylesheet">
        <script src="<?php echo Yii::app()->request->baseUrl; ?>/js/ie-emulation-modes-warning.js"></script>
        <script src="<?php echo Yii::app()->request->baseUrl; ?>/js/main.js"></script>


        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>
    <body>
        <div class="container">
            <div class="masthead">
                <h3 class="text-muted"><?php echo CHtml::encode($this->pageTitle); ?></h3>
                <nav>

                    <?php
                    $this->widget('zii.widgets.CMenu', array(
                        'items' => array(
                            array('label' => 'Главная', 'url' => array('/site/index')),
                            array('label' => 'Instagram', 'url' => array('/site/instagram'), 'visible' => !Yii::app()->user->isGuest),
                            array('label' => 'Посты', 'url' => array('/post/posts'), 'visible' => !Yii::app()->user->isGuest),
                            array('label' => 'Получить код', 'url' => array('/site/getVkCode'), 'visible' => !Yii::app()->user->isGuest),
                            array('label' => 'Выйти (' . Yii::app()->user->name . ')', 'url' => array('/site/logout'), 'visible' => !Yii::app()->user->isGuest)
                        ),
                        'htmlOptions' => ['class' => 'nav nav-justified']
                    ));
                    ?>

                </nav>
            </div>

            <div class="container-fluid">
                <div class="row">


                    <?php foreach (Yii::app()->user->getFlashes() as $key => $message) { ?>
                        <div class="my-alert alert alert-<?= $key ?> alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Закрыть"><span aria-hidden="true">&times;</span></button>
                            <?= $message ?>
                        </div>
                    <?php } ?>
                    <?php echo $content; ?>
                </div>
            </div>

            <!-- Site footer -->
            <footer class="footer">
                <p>&copy; Company <?php echo date('Y'); ?></p>
            </footer>

        </div> <!-- /container -->



    </body>
</html>
