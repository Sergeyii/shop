<?php

/* @var $this yii\web\View */
/* @var $model shop\entities\Shop\DeliveryMethod */
/* @var $method \shop\entities\Shop\DeliveryMethod */

$this->title = 'Update Delivery Method: ' . $method->name;
$this->params['breadcrumbs'][] = ['label' => 'Delivery Methods', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $method->name, 'url' => ['view', 'id' => $method->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="delivery-method-update">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
