<?php

use yii\helpers\Html;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use shop\entities\Blog\Category;

/* @var $this yii\web\View */
/* @var $searchModel backend\forms\Blog\CategorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Categories';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="category-index">
    <p>
        <?= Html::a('Create Category', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <div class="box">
        <div class="box-body">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    'sort',
                    [
                        'attribute' => 'name',
                        'format' => 'raw',
                        'value' => function(Category $category){
                            return Html::a(Html::encode($category->name), ['view', 'id' => $category->id]);
                        },
                    ],
                    'slug',
                    'title',
                    ['class' => ActionColumn::class],
                ],
            ]); ?>
        </div>
    </div>
</div>
