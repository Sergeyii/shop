<?php

use yii\helpers\Html;
use \shop\helpers\ManufacturerHelper;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model \shop\forms\manage\ManufacturerForm */
/* @var $manufacturer \shop\entities\Site\Manufacturer */
/* @var $form yii\widgets\ActiveForm */

?>
<div class="manufacturer-form">
    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'sort')->textInput() ?>
    <?= $form->field($model, 'slug')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'file')->label(false)->widget(\kartik\file\FileInput::class, [
        'options' => [
            'accept' => 'image/*',
            'multiple' => false,
        ],
        'pluginOptions' => [
            'initialPreviewAsData'=>true,
            'initialCaption' => $model->file,
            'overwriteInitial'=>true,
            'initialPreview' => ManufacturerHelper::getModelInitialImagesFile($manufacturer ?? null, 'file'),
            'initialPreviewConfig' => [
                ['caption' => $model->file],
            ],
            'layoutTemplates' => [
                'actions' => '',
            ],
            'showUpload' => false,
            'showRemove' => false,
        ]
    ]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>