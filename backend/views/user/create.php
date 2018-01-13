<?php

use yii\helpers\Html;
use \yii\widgets\ActiveForm;


/* @var $this yii\web\View */
/* @var $model shop\entities\user\User */

$this->title = 'Create User';
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-create">

    <?php $form = ActiveForm::begin(); ?>

    <?=$form->field($model, 'username')->textInput(['maxLength' => true])?>
    <?=$form->field($model, 'email')->textInput(['maxLength' => true])?>
    <?=$form->field($model, 'password')->textInput(['maxLength' => true])?>

    <div class="form-group">
        <?= Html::submitButton('Create', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>