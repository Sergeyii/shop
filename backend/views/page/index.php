<?php

use yii\helpers\Html;
use yii\grid\GridView;
use shop\entities\Page;

/* @var $this yii\web\View */
/* @var $searchModel backend\forms\PageSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Pages';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="page-index">
    <p>
        <?= Html::a('Create Page', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <div class="box">
        <div class="box-body">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    'id',
                    [
                        'attribute' => 'title',
                        'format' => 'raw',
                        'value' => function(Page $model){
                            return Html::a(Html::encode($model->title), ['view', 'id' => $model->id]);
                        },
                    ],
                    'slug',
                    ['class' => \yii\grid\ActionColumn::class],
                ],
            ]); ?>
        </div>
    </div>
</div>