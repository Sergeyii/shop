<?php

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\DataProviderInterface */
/* @var $category shop\entities\Shop\Category */

use yii\helpers\Html;

$this->title = $category->getSeoTitle();

$this->registerMetaTag(['name' => 'keywords', 'content' => $category->meta->keywords]);
$this->registerMetaTag(['name' => 'description', 'content' => $category->meta->description]);

$this->params['breadcrumbs'][] = ['label' => 'Catalog', 'url' => ['index']];

foreach($category->parents as $parent){
    if(!$parent->isRoot()){
        $this->params['breadcrumbs'][] = ['label' => $parent->name, 'url' => ['shop/catalog/category', 'id' => $parent->id]];
    }
}

$this->params['breadcrumbs'][] = $category->name;
$this->params['active_category'] = $category;
?>

<h1><?= Html::encode($category->getHeadingTitle()) ?></h1>

<?=$this->render('_subcategories', [
    'category' => $category
])?>

<?php if (trim($category->description)): ?>
    <div class="panel panel-default">
        <div class="panel-body">
            <?= Yii::$app->formatter->asHtml($category->description, [
                'Attr.AllowedRel' => array('nofollow'),
                'HTML.SafeObject' => true,
                'Output.FlashCompat' => true,
                'HTML.SafeIframe' => true,
                'URI.SafeIframeRegexp'=>'%^(https?:)?//(www\.youtube(?:-nocookie)?\.com/embed/|player\.vimeo\.com/video/)%',
            ]) ?>
        </div>
    </div>
<?php endif; ?>

<?=$this->render('_list', [
    'dataProvider' => $dataProvider
])?>