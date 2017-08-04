<?php foreach ($result['content'] as $content) { ?>

    <div class="col-lg-3 instagram_block <?= ($content['inDb'] == true) ? 'inDb' : '' ?>">
        <?php echo CHtml::image($content['url'], '', ['class' => "img-responsive img-thumbnail", 'id' => 'insta_' . $content['id'], 'data-id' => $content['id']]); ?>
    </div>

<?php } ?>
