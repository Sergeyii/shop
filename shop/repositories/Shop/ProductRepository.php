<?php

namespace shop\repositories\Shop;

use shop\dispatchers\EventDispatcher;
use shop\entities\Shop\Product\Product;
use shop\repositories\NotFoundException;

class ProductRepository
{
    private $dispatcher;

    public function __construct(EventDispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    public function remove(Product $product)
    {
        if(!$product->delete()){
            throw new \RuntimeException('Product removing error!');
        }
        $this->dispatcher->dispatchAll($product->releaseEvents());
    }

    public function get($id): Product
    {
        if(!$product = Product::findOne($id)){
            throw new NotFoundException('Product not found!');
        }

        return $product;
    }

    public function save(Product $product): void
    {
        if(!$product->save()){
            throw new \RuntimeException('Product saving error!');
        }
        $this->dispatcher->dispatchAll($product->releaseEvents());
    }

    /* @param Product[] $products */
    public function saveAll(array $products): void
    {
        foreach($products as $product){
            $this->save($product);
        }
    }

    public function existsByBrand($id): bool
    {
        Product::find()->andWhere(['brand_id' => $id])->exists();
    }

    public function existsByMainCategory($id): bool
    {
        return Product::find()->andWhere(['category_id' => $id])->exists();
    }
}