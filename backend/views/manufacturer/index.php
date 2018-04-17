<?php

use yii\helpers\Html;
use yii\grid\GridView;
use \shop\entities\Site\Manufacturer;

/* @var $this yii\web\View */
/* @var $searchModel backend\forms\ManufacturerSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Manufacturers';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="manufacturer-index">
    <p>
        <?= Html::a('Create Manufacturer', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'id',
            [
                'attribute' => 'title',
                'value' => function(Manufacturer $model){
                    return Html::a(Html::encode($model->title), ['view', 'id' => $model->id]);
                },
                'format' => 'raw',
            ],
            'slug',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
