
<?php if (empty($instagram_access_token)) { ?>
    <div class="container">
        <div class="row">
            <a class="btn btn-primary btn-lg btn-block" href='<?= $instagram->getLoginUrl() ?>'>Войти Instagram</a>
        </div>
    </div>
<?php } ?>




<?php if (!empty($instagram_access_token)) { ?>
    <div class="container">
        <div class="row">
            <div class="jumbotron">

                <form>
                    <div class="form-group">
                        <label>Поиск</label>
                        <input type="text" name="tag" class="form-control" value="<?= $tag?>" placeholder="Tag">
                    </div>

                    <button type="submit" class="btn btn-primary">Найти</button>

                </form>

                <p>Кликайте на фото</p>
            </div>
            <div class="js-instagram-content">
                <?php echo $this->renderPartial('_view_instagram', ['result' => $result]); ?>
            </div>
            <div class="col-lg-2">
                <br><br><br>
                <button type="button" data-tag="<?php echo $tag; ?>" data-max_tag_id="<?php echo $result['max_tag_id']; ?>" id="js-instagram-content-loader" data-loading-text="Загрузка..." class="btn btn-primary" autocomplete="off">
                    Загрузить еще
                </button>
            </div>
        </div>
    </div>
<?php } ?>