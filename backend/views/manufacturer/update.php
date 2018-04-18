<?php

/* @var $this yii\web\View */
/* @var $manufacturer shop\entities\Site\Manufacturer */
/* @var $model \shop\forms\manage\ManufacturerForm */

$this->title = 'Update Manufacturer: ' . $manufacturer->title;
$this->params['breadcrumbs'][] = ['label' => 'Manufacturers', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $manufacturer->title, 'url' => ['view', 'id' => $manufacturer->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="manufacturer-update">
    <?= $this->render('_form', [
        'model' => $model,
        'manufacturer' => $manufacturer,
    ]) ?>
</div>
