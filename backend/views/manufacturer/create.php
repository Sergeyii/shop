<?php

/* @var $this yii\web\View */
/* @var $model shop\entities\Site\Manufacturer */

$this->title = 'Create Manufacturer';
$this->params['breadcrumbs'][] = ['label' => 'Manufacturers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="manufacturer-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>