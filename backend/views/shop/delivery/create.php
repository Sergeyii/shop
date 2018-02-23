<?php

/* @var $this yii\web\View */
/* @var $model shop\entities\Shop\DeliveryMethod */

$this->title = 'Create Delivery Method';
$this->params['breadcrumbs'][] = ['label' => 'Delivery Methods', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="delivery-method-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
