<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\forms\Shop\OrderSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="order-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'created_at') ?>

    <?= $form->field($model, 'user_id') ?>

    <?= $form->field($model, 'delivery_method_id') ?>

    <?= $form->field($model, 'delivery_method_name') ?>

    <?php // echo $form->field($model, 'delivery_cost') ?>

    <?php // echo $form->field($model, 'payment_method') ?>

    <?php // echo $form->field($model, 'cost') ?>

    <?php // echo $form->field($model, 'note') ?>

    <?php // echo $form->field($model, 'current_status') ?>

    <?php // echo $form->field($model, 'cancel_reason') ?>

    <?php // echo $form->field($model, 'statuses_json') ?>

    <?php // echo $form->field($model, 'customer_phone') ?>

    <?php // echo $form->field($model, 'customer_name') ?>

    <?php // echo $form->field($model, 'delivery_index') ?>

    <?php // echo $form->field($model, 'delivery_address') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
