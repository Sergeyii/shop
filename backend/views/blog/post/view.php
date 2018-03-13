<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\DetailView;
use shop\helpers\PostHelper;
use shop\entities\Blog\Post\Post;

/* @var $this yii\web\View */
/* @var $post shop\entities\Blog\Post\Post */

$this->title = $post->title;
$this->params['breadcrumbs'][] = ['label' => 'Posts', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="post-view">
    <p>
        <?php if($post->isActive()):?>
            <?=Html::a('Draft', ['draft', 'id' => $post->id], [
                'class' => 'btn btn-primary',
                'data-method' => 'post',
            ])?>
        <?php else:?>
            <?=Html::a('Activate', ['activate', 'id' => $post->id], [
                'class' => 'btn btn-success',
                'data-method' => 'post',
            ])?>
        <?php endif;?>
        <?= Html::a('Update', ['update', 'id' => $post->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $post->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <div class="box">
        <div class="box-header with-border">Common</div>
        <div class="box-body">
            <?= DetailView::widget([
                'model' => $post,
                'attributes' => [
                    'id',
                    [
                        'attribute' => 'status',
                        'value' => PostHelper::statusLabel($post->status),
                        'format' => 'raw',
                    ],
                    'title',
                    [
                        'attribute' => 'category_id',
                        'value' => ArrayHelper::getValue($post, 'category.name'),
                    ],
                    [
                        'label' => 'Tags',
                        'value' => implode(',', ArrayHelper::getColumn($post->tags, 'name')),
                    ],
                ],
            ]) ?>
        </div>
    </div>

    <div class="box">
        <div class="box-header with-border">Photo</div>
        <div class="box-body">
            <?php if($post->photo):?>
                <?=Html::a(Html::img($post->getThumbFileUrl('photo', 'thumb')),
                    $post->getUploadedFileUrl('photo'), [
                        'class' => 'thumbnail',
                        'target' => '_blank',
                ])?>
            <?php endif;?>
        </div>
    </div>

    <div class="box">
        <div class="box-header with-border">Description</div>
        <div class="box-body">
            <?=Yii::$app->formatter->asNtext($post->description)?>
        </div>
    </div>

    <div class="box">
        <div class="box-header with-border">Content</div>
        <div class="box-body">
            <?=Yii::$app->formatter->asNtext($post->content)?>
        </div>
    </div>

    <div class="box">
        <div class="box-header with-border">SEO</div>
        <div class="box-body">
            <?=DetailView::widget([
                'model' => $post->meta,
                'attributes' => [
                    'title',
                    'keywords',
                    'description',
                ],
            ])?>
        </div>
    </div>
</div>