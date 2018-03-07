<?php

use yii\helpers\Html;
use shop\helpers\PostHelper;
use yii\grid\GridView;
use shop\entities\Blog\Post\Post;
use yii\grid\ActionColumn;

/* @var $this yii\web\View */
/* @var $searchModel backend\forms\Blog\PostSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Posts';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="post-index">
    <p>
        <?= Html::a('Create Post', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <div class="box">
        <div class="box-body">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    'id',
                    [
                        'attribute' =>  'photo',
                        'value' =>  function(Post $post){
                            return $post->photo ? Html::img($post->getThumbFileUrl('photo', 'thumb')) : null;
                        },
                        'format' =>  'raw',
                        'contentOptions' =>  ['style' => 'max-width: 120px;'],
                    ],
                    [
                        'attribute' => 'title',
                        'value' => function(Post $post){
                            return Html::a(Html::encode($post->title), ['view', 'id' => $post->id]);
                        },
                        'format' => 'raw',
                    ],
                    [
                        'attribute' => 'category_id',
                        'filter' => $searchModel->categoriesList(),
                        'value' => 'category.name',
                    ],
                    [
                        'attribute' => 'status',
                        'filter' => $searchModel->statusList(),
                        'value' => function(Post $post){
                            return PostHelper::statusLabel($post->status);
                        },
                        'format' => 'raw',
                    ],
                    'created_at:datetime',
                    ['class' => ActionColumn::class],
                ],
            ]); ?>
        </div>
    </div>
</div>