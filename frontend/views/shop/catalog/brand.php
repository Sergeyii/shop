<?php

/* @var $brand \shop\entities\Shop\Brand */
/* @var $this \yii\web\View */
/* @var $dataProvider \yii\data\DataProviderInterface */

use yii\helpers\Html;

$this->title = $brand->getSeoTitle();

$this->registerMetaTag(['name' => 'keywords', 'content' => $brand->meta->keywords]);
$this->registerMetaTag(['name' => 'description', 'content' => $brand->meta->description]);

$this->params['breadcrumbs'][] = ['label' => 'Catalog', 'url' => ['index']];
$this->params['breadcrumbs'][] = $brand->name;
?>

<h1><?=Html::encode($brand->name)?></h1>
<hr/>
<?= $this->render('_list', [
    'dataProvider' => $dataProvider,
]);?>
