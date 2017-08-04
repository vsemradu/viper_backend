<?= CHtml::link("Получить code", $v->get_code_token('token'), ['target' => '_blank']) ?>
<br>
<br>
<div class="form">
    <?php echo CHtml::beginForm(); ?>


    <div class="row">
        <?php echo CHtml::label('Введите код:', 'code'); ?>
        <?php echo CHtml::textField('code'); ?>
    </div>


    <div class="row submit">
        <?php echo CHtml::submitButton('Ввести'); ?>
    </div>

    <?php echo CHtml::endForm(); ?>
</div><!-- form -->