<?php

use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $tag \shop\entities\Blog\Tag */
/* @var $dataProvider \yii\data\DataProviderInterface */

$this->title = 'Posts with tag '. $tag->name;
$this->params['breadcrumbs'][] = ['label' => 'Blog', 'url' => ['index']];
$this->params['breadcrumbs'] = $tag->name;
?>
<h1>Posts with tag &laquo;<?=Html::encode($tag->name)?>&raquo;</h1>

<?=$this->render('_list', [
    'dataProvider' => $dataProvider,
])?>