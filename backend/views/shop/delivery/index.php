<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\grid\ActionColumn;
use \shop\entities\Shop\DeliveryMethod;

/* @var $this yii\web\View */
/* @var $searchModel backend\forms\Shop\DeliveryMethodSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Delivery Methods';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="delivery-method-index">
    <p>
        <?= Html::a('Create Method', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <div class="box">
        <div class="box-body">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    'id',
                    [
                        'attribute' => 'name',
                        'format' => 'raw',
                        'value' => function (DeliveryMethod $model){
                            return Html::a(Html::encode($model->name), ['view', 'id' => $model->id]);
                        },
                    ],
                    'cost',
                    'min_weight',
                    'max_weight',
                    ['class' => ActionColumn::class],
                ],
            ]); ?>
        </div>
    </div>
</div>