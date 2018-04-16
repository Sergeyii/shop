<?php

namespace console\controllers;

use shop\entities\Shop\Product\Product;
use shop\services\search\ProductIndexer;
use yii\console\Controller;

class SearchController extends Controller
{
    public $indexer;

    public function __construct(string $id, $module, ProductIndexer $indexer, array $config = [])
    {
        $this->indexer = $indexer;
        parent::__construct($id, $module, $config);
    }

    public function actionReindex(): void
    {
        $query = Product::find()
            ->active()
            ->with(['category', 'categoryAssignments', 'tagAssignments', 'values'])
            ->orderBy('id');

        $this->stdout('Clearing'.PHP_EOL);
        $this->indexer->clear();


        //Добавляем товары в индекс
        $this->stdout('Indexing of products'.PHP_EOL);
        /* @var Product $product */
        foreach($query->each() as $product){
            $this->stdout('Product #'.$product->id.PHP_EOL);
            $this->indexer->index($product);
        }

        $this->stdout('Done!'.PHP_EOL);
    }
}