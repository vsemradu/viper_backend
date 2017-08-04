<div class="container">
    <div class="row">

        <div class="col-lg-12">

            <?php if (!empty($dateLastPost)) { ?>
                <h4>Дата последней публикации <span class="label label-success"><?= $dateLastPost ?></span></h4>
            <?php } ?>
            <h4>Всего опубликованно <span class="label label-info"><?= $countPublisher ?></span></h4>
            <h4>Ожидает публикации <span class="label label-warning"><?= $countNotPublisher ?></span></h4>
            <?php
            $this->widget('application.extensions.bootstrap.BootstrapCGridView', array(
                'id' => 'content-grid',
                'scrollToOnPagination' => true,
                'dataProvider' => $modelContent->searchIndex(),
//                'filter' => $modelContent,
                'columns' => [
                    [
                        'name' => 'tag',
                        'sortable' => false,
                    ],
                    [
                        'name' => 'statusText',
                        'sortable' => false,
                        'type' => 'html',
                    ],
                    [
                        'name' => 'dateCreate',
                    ],
                    [
                        'name' => 'datePosted',
                        'type' => 'html',
                    ],
                    [
                        'name' => 'imagesByCode',
                        'type' => 'html',
                        'htmlOptions' => array('width' => '20%'),
                    ],
                    [
                        'class' => 'BootstrapCButtonColumn',
                        'template' => '{post}{update}{delete}',
                        'buttons' => [
                            'post' => [

                                'label' => '<span class="glyphicon glyphicon-envelope"></span>',
                                'url' => 'Yii::app()->createUrl("post/deleteGroup", array("group"=>$data->group))',
                                'options' => [
                                    'title' => '',
                                    'data-placement' => "top",
                                    'data-toggle' => "tooltip",
                                    'data-original-title' => "Опубликовать",
                                    'class' => 'btn btn-default',
                                    'type' => 'button'
                                ],
                            ],
                            'delete' => [
                                'url' => 'Yii::app()->createUrl("post/deleteGroup", array("group"=>$data->group))',
                            ],
                            'update' => [
                                'url' => 'Yii::app()->createUrl("post/update", array("id"=>$data->group))',
                            ]
                        ],
                        'htmlOptions' => ['width' => '13%'],
                    ],
                ],
            ));
            ?>
        </div>
    </div>
    <div class="row">



        <div class="col-lg-6">



            <?php
            $form = $this->beginWidget('CActiveForm', array(
                'id' => 'post-form',
                'enableAjaxValidation' => false,
            ));
            ?>
            <div class="form-group">
                <?php echo $form->labelEx($model, 'text'); ?>
                <?php echo $form->textArea($model, 'text', array('rows' => 6, 'class' => 'form-control', 'cols' => 50)); ?>
                <?php echo $form->error($model, 'text'); ?>

            </div>

            <div class="form-group">
                <div class="row">
                    <div class='col-sm-6'>
                        <?php echo $form->labelEx($model, 'datetime'); ?>
                        <?php echo $form->textField($model, 'datetime', array('id' => 'datetime', 'class' => 'form-control')); ?>
                        <?php echo $form->error($model, 'datetime'); ?>
                    </div>
                </div>
            </div>

            <?php $this->endWidget(); ?>

        </div>
    </div>
</div>


<script type="text/javascript">
    $(function () {
        $('#datetime').datetimepicker({
            locale: 'ru'
        });
    });
</script>