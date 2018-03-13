<?php

use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $dataProvider \yii\data\DataProviderInterface */
/* @var $category \shop\entities\Blog\Category */

$this->title = $category->getSeoTitle();

$this->registerMetaTag(['name' => 'keywords', 'content' => $category->meta->keywords]);
$this->registerMetaTag(['name' => 'description', 'content' => $category->meta->description]);

$this->params['breadcrumbs'][] = ['label' => 'Blog', 'url' => ['index']];
$this->params['breadcrumbs'][] = $category->name;

$this->params['active_category'] = $category;
?>

<h1><?=Html::encode($category->getHeadingTitle())?></h1>

<?php if(trim($category->description)):?>
    <div class="panel panel-default">
        <div class="panel-body">
            <?=Yii::$app->formatter->asNtext($category->description)?>
        </div>
    </div>
<?php endif;?>

<?=$this->render('_list', [
    'dataProvider' => $dataProvider
])?>
