<?php

namespace frontend\widgets;

use shop\readModels\Shop\ProductReadRepository;
use shop\repositories\Shop\ProductRepository;
use yii\base\Widget;

class FeaturedProductsWidget extends Widget
{
    public $limit;

    private $repository;

    public function __construct(ProductReadRepository $repository, array $config = [])
    {
        parent::__construct($config);
        $this->repository = $repository;
    }

    public function run()
    {
        return $this->render('featured', [
            'products' => $this->repository->getFeatured($this->limit),
        ]);
    }
}