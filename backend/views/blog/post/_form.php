<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\file\FileInput;

/* @var $this yii\web\View */
/* @var $model \shop\forms\manage\Blog\Post\PostForm */
/* @var $form yii\widgets\ActiveForm */
/* @var $post \shop\entities\Blog\Post\Post */
?>

<div class="post-form">
    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-md-6">
            <div class="box box-default">
                <div class="box-header with-border">Common</div>
                <div class="box-body">
                    <?= $form->field($model, 'categoryId')->dropDownList($model->categoriesList(), ['prompt' => '']) ?>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="box box-default">
                <div class="box-header with-border">Tags</div>
                <div class="box-body">
                    <?= $form->field($model->tags, 'existing')->checkboxList($model->tags->tagsList())?>
                    <?= $form->field($model->tags, 'textNew')->textInput()?>
                </div>
            </div>
        </div>
    </div>

    <div class="box box-default">
        <div class="box-body">
            <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'description')->textarea(['rows' => 5]) ?>
            <?= $form->field($model, 'content')->textarea(['rows' => 20]) ?>
        </div>
    </div>

    <div class="box box-default">
        <div class="box-header with-border">Photo</div>
        <div class="box-body">
            <?php
            $initialPreview = [];
            if(isset($post)){
                $initialPreview[] = $post->getThumbFileUrl('photo', 'admin');
                ?>
                <?= Html::a('<span class="glyphicon glyphicon-remove"></span>', ['delete-photo', 'id' => $post->id], [
                    'class' => 'btn btn-default',
                    'data-method' => 'post',
                    'data-confirm' => 'Remove photo?',
                ]); ?>
                <?php
            }
            ?>
            <?= $form->field($model, 'photo')->label(false)->widget(FileInput::class, [
                'options' => [
                    'accept' => 'image/*',
                ],
                'pluginOptions' => [
                    'initialPreview'=>$initialPreview,
                    'initialPreviewConfig' => [
                        ['caption' => $model->photo],
                    ],
                    'initialPreviewAsData'=>true,
//                    'showPreview' => true,
//                    'showCaption' => true,
//                    'showRemove' => true,
//                    'showUpload' => false,
                ]
            ])?>
        </div>
    </div>

    <div class="box box-default">
        <div class="box-header with-border">SEO</div>
        <div class="box-body">
            <?= $form->field($model->meta, 'title')->textInput(['maxLength' => true]) ?>
            <?= $form->field($model->meta, 'keywords')->textInput(['maxLength' => true]) ?>
            <?= $form->field($model->meta, 'description')->textarea(['rows' => 2]) ?>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>