<?php

namespace shop\repositories\Shop;

use shop\entities\Shop\Product\Product;
use shop\repositories\NotFoundException;

class ProductRepository
{
    public function remove(Product $product)
    {
        if(!$product->delete()){
            throw new \RuntimeException('Product removing error!');
        }
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