<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use \shop\helpers\ManufacturerHelper;

/* @var $this yii\web\View */
/* @var $model shop\entities\Site\Manufacturer */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Manufacturers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="manufacturer-view">
    <p>
        <?php if ($model->isActive()): ?>
            <?= Html::a('Draft', ['draft', 'id' => $model->id], ['class' => 'btn btn-primary', 'data-method' => 'post']) ?>
        <?php else: ?>
            <?= Html::a('Activate', ['activate', 'id' => $model->id], ['class' => 'btn btn-success', 'data-method' => 'post']) ?>
        <?php endif; ?>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'title',
            'slug',
            [
                'attribute' => 'status',
                'value' => ManufacturerHelper::statusLabel($model->status),
                'format' => 'raw',
            ],
            'description:ntext',
        ],
    ]) ?>

    <div class="box">
        <div class="box-header with-border">Photo</div>
        <div class="box-body">
            <div class="row">
                <div class="col-md-2 col-xs-3" style="text-align: center">
                    <?php if($model->file):?>
                        <div class="btn-group">
                            <?= Html::a('<span class="glyphicon glyphicon-remove"></span>', ['delete-photo', 'id' => $model->id], [
                                'class' => 'btn btn-default',
                                'data-method' => 'post',
                                'data-confirm' => 'Remove photo?',
                            ]); ?>
                        </div>
                    <?php endif;?>
                    <div>
                        <?= Html::a(Html::img($model->getThumbFileUrl('file', 'index_page')), $model->getUploadedFileUrl('file'), [
                            'target' => '_blank',
                        ]) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>