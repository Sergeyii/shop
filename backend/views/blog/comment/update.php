<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $comment shop\entities\Blog\Post\Comment */
/* @var $model \shop\forms\manage\Blog\Post\CommentEditForm */

$this->title = 'Update Comment: ' . $comment->id;
$this->params['breadcrumbs'][] = ['label' => 'Comments', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $comment->id, 'url' => ['view', 'id' => $comment->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="comment-update">
    <?php $form = ActiveForm::begin()?>

    <div class="box box-default">
        <div class="box-header with-border">Common</div>
        <div class="box-body">
            <?=$form->field($model, 'parentId')->textInput()?>
            <?=$form->field($model, 'text')->textarea(['rows' => 20])?>
        </div>
    </div>

    <div class="form-group">
        <?=Html::submitButton('Save', ['class' => 'btn btn-success'])?>
    </div>

    <?php ActiveForm::end()?>
</div>