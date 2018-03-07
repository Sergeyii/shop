<?php

/* @var $this yii\web\View */
/* @var $model \shop\forms\manage\Blog\Post\PostForm */
/* @var $post \shop\entities\Blog\Post\Post */

$this->title = 'Update Post: ' . $post->title;
$this->params['breadcrumbs'][] = ['label' => 'Posts', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $post->title, 'url' => ['view', 'id' => $post->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="post-update">
    <?= $this->render('_form', [
        'model' => $model,
        'post' => $post,
    ]) ?>
</div>
