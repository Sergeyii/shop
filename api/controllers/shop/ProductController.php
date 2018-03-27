<?php

namespace api\controllers\shop;

use api\formatters\ProductListFormatter;
use api\formatters\ProductViewFormatter;
use api\providers\MapDataProvider;
use shop\readModels\Shop\BrandReadRepository;
use shop\readModels\Shop\CategoryReadRepository;
use shop\readModels\Shop\ProductReadRepository;
use shop\readModels\Shop\TagReadRepository;
use yii\data\DataProviderInterface;
use yii\rest\Controller;
use yii\web\NotFoundHttpException;

class ProductController extends Controller
{
    private $products;
    private $categories;
    private $brands;
    private $tags;

    protected function verbs()
    {
        return [
            'index' => ['GET'],
            'view' => ['GET'],
            'category' => ['GET'],
            'brand' => ['GET'],
            'tag' => ['GET'],
        ];
    }

    public function __construct(
        string $id,
        $module,
        ProductReadRepository $products,
        CategoryReadRepository $categories,
        BrandReadRepository $brands,
        TagReadRepository $tags,
        array $config = []
    )
    {
        $this->products = $products;
        $this->categories = $categories;
        $this->brands = $brands;
        $this->tags = $tags;
        parent::__construct($id, $module, $config);
    }

    public function actionIndex(): DataProviderInterface
    {
        $dataProvider = $this->products->getAll();
        return new MapDataProvider($dataProvider, ProductListFormatter::class);
    }

    public function actionCategory($id): DataProviderInterface
    {
        if( !$category = $this->categories->find($id) ){
            throw new NotFoundHttpException("The requested product doesn't exists");
        }

        $dataProvider = $this->products->getAllByCategory($category);
        return new MapDataProvider($dataProvider, ProductListFormatter::class);
    }

    public function actionBrand($id): DataProviderInterface
    {
        if( !$brand = $this->brands->find($id) ){
            throw new NotFoundHttpException("The requested product doesn't exists");
        }

        $dataProvider = $this->products->getAllByBrand($brand);
        return new MapDataProvider($dataProvider, ProductListFormatter::class);
    }

    public function actionTag($id): DataProviderInterface
    {
        if( !$tag = $this->tags->find($id) ){
            throw new NotFoundHttpException("The requested product doesn't exists");
        }

        $dataProvider = $this->products->getAllByTag($tag);
        return new MapDataProvider($dataProvider, ProductListFormatter::class);
    }

    public function actionView($id): array
    {
        if( !$product = $this->products->find($id) ){
            throw new NotFoundHttpException("The requested product doesn't exists");
        }

        return (new ProductViewFormatter($product))->format();
    }
}