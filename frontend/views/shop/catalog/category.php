<?php

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\DataProviderInterface */
/* @var $category shop\entities\Shop\Category */

use yii\helpers\Html;

$this->title = $category->getSeoTitle();

$this->registerMetaTag(['name' => 'keywords', 'content' => $category->meta->keywords]);
$this->registerMetaTag(['name' => 'description', 'content' => $category->meta->description]);

$this->params['breadcrumbs'][] = ['label' => 'Catalog', 'url' => ['index']];
$this->params['breadcrumbs'][] = $category->name;
?>

<h1><?= Html::encode($category->getHeadingTitle()) ?></h1>

<?=$this->render('_subcategories', [
    'category' => $category
])?>

<?php if (trim($category->description)): ?>
    <div class="panel panel-default">
        <div class="panel-body">
            <?= Yii::$app->formatter->asHtml($category->description) ?>
        </div>
    </div>
<?php endif; ?>

<?=$this->render('_list', [
    'dataProvider' => $dataProvider
])?>