<?php

/* @var $this yii\web\View */
/* @var $dataProvider \yii\data\DataProviderInterface */
/* @var $category \shop\entities\Shop\Category */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;

$this->title = 'Catalog';
$this->params['breadcrumbs'][] = $this->title;
?>

<h1><?= Html::encode($this->title) ?></h1>

<div class="panel panel-default">
    <div class="panel-body">
        <?php foreach($category->children as $child){?>
            <a href="<?=Html::encode(Url::to(['shop/catalog/category', 'id' => $child->id]))?>"><?=Html::encode($child->name)?></a>&nbsp;

       <?php }?>
    </div>
</div>

<div class="row">
    <div class="col-md-2 col-sm-6 hidden-xs">
        <div class="btn-group btn-group-sm">
            <button type="button" id="list-view" class="btn btn-default" data-toggle="tooltip" title="List"><i class="fa fa-th-list"></i></button>
            <button type="button" id="grid-view" class="btn btn-default" data-toggle="tooltip" title="Grid"><i class="fa fa-th"></i></button>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="form-group">
            <a href="/index.php?route=product/compare" id="compare-total" class="btn btn-link">Product Compare (0)</a>
        </div>
    </div>
    <div class="col-md-4 col-xs-6">
        <div class="form-group input-group input-group-sm">
            <label class="input-group-addon" for="input-sort">Sort By:</label>
            <select id="input-sort" class="form-control" onchange="location = this.value;">
                <?php
                $sortValues = [
                    '' => 'Default',
                    'name' => 'Name (A - Z)',
                    '-name' => 'Name (Z - A)',
                    'price' => 'Price (Low &gt; High)',
                    '-price' => 'Price (High &gt; Low)',
                    'rating' => 'Rating (Highest)',
                    '-rating' => 'Rating (Lowest)',
                ];

                $currentSortValue = Yii::$app->request->get('sort');
                ?>
                <?php foreach($sortValues as $k => $v) {?>
                    <option value="<?=Url::current(['sort' => $k ?: null])?>" <?=($currentSortValue == $k ? ' selected="selected"' : '')?>><?=$v?></option>
                <?php }?>
            </select>
        </div>
    </div>
    <div class="col-md-3 col-xs-6">
        <div class="form-group input-group input-group-sm">
            <label class="input-group-addon" for="input-limit">Show:</label>
            <select id="input-limit" class="form-control" onchange="location = this.value;">
                <?php
                $paginationValues = [15, 25, 50, 75, 100];

                $currentPageValue = $dataProvider->getPagination()->getPageSize();
                ?>
                <?php foreach($paginationValues as $v) {?>
                    <option value="<?=Url::current(['per-page' => $v])?>" <?=($currentPageValue == $v ? ' selected="selected"' : '')?>><?=$v?></option>
                <?php }?>
            </select>
        </div>
    </div>
</div>
<div class="row">
    <?php foreach ($dataProvider->getModels() as $product) {?>
        <?=$this->render('_product', [
            'product' => $product,
        ])?>
    <?php
    }?>
</div>
<div class="row">
    <div class="col-sm-6 text-left">
        <?=LinkPager::widget([
            'pagination' => $dataProvider->getPagination()
        ])?>
    </div>
    <div class="col-sm-6 text-right">Showing <?= $dataProvider->getCount() ?> of <?= $dataProvider->getTotalCount() ?></div>
</div>
