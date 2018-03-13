<?php

use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $dataProvider \yii\data\DataProviderInterface */

$this->title = 'Blog';
$this->params['breadcrumbs'][] = $this->title;
?>
<h1><?=Html::encode($this->title)?></h1>

<?=$this->render('_list', [
    'dataProvider' => $dataProvider,
])?>