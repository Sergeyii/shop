<?php

namespace frontend\controllers\shop;

use shop\readModels\Shop\BrandReadRepository;
use shop\readModels\Shop\CategoryReadRepository;
use shop\readModels\Shop\ProductReadRepository;
use shop\readModels\Shop\TagReadRepository;
use yii\web\Controller;

class CatalogController extends Controller
{
    public $products;
    public $categories;
    public $brands;
    public $tags;

    public $layout = 'catalog';

    public function __construct(string $id, $module,
        ProductReadRepository $products,
        CategoryReadRepository $categories,
        BrandReadRepository $brands,
        TagReadRepository $tags,
        array $config = []
    )
    {
        parent::__construct($id, $module, $config);


        $this->products = $products;
        $this->categories = $categories;
        $this->brands = $brands;
        $this->tags = $tags;
    }

    public function actionIndex()
    {
        $dataProvider = $this->products->getAll();
        $category = $this->categories->getRoot();


        return $this->render('index', [
            'category' => $category,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCategory($id)
    {
        return $this->render('category');
    }

    public function actionBrand($id)
    {
        return $this->render('brand');
    }

    public function actionTag($id)
    {
        return $this->render('tag');
    }

    public function actionProduct($id)
    {
        return $this->render('product');
    }
}