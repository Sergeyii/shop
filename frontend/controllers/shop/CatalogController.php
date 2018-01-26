<?php

namespace frontend\controllers\shop;

use shop\forms\Shop\AddToCartForm;
use shop\forms\Shop\ReviewForm;
use shop\readModels\Shop\BrandReadRepository;
use shop\readModels\Shop\CategoryReadRepository;
use shop\readModels\Shop\ProductReadRepository;
use shop\readModels\Shop\TagReadRepository;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

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

    private function throwPageNotFound(){
        throw new NotFoundHttpException('The requested page does not exists!');
    }

    public function actionCategory($id)
    {
        if( !($category = $this->categories->find($id)) ){
            $this->throwPageNotFound();
        }

        $dataProvider = $this->products->getAllByCategory($category);

        return $this->render('category', [
            'category' => $category,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionBrand($id)
    {
        if( !($brand = $this->brands->find($id)) ){
            $this->throwPageNotFound();
        }

        $dataProvider = $this->products->getAllByBrand($brand);

        return $this->render('brand', [
            'brand' => $brand,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionTag($id)
    {
        if( !($tag = $this->tags->find($id)) ){
            $this->throwPageNotFound();
        }

        $dataProvider = $this->products->getAllByTag($tag);

        return $this->render('tag', [
            'tag' => $tag,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionProduct($id)
    {
        $product = $this->products->find($id);
        $reviewForm = new ReviewForm();
        $cartForm = new AddToCartForm($product);

        return $this->render('product', [
            'product' => $product,
            'reviewForm' => $reviewForm,
            'cartForm' => $cartForm,
        ]);
    }
}