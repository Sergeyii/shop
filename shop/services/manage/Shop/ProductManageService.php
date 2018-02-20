<?php

namespace shop\services\manage\Shop;

use forms\manage\Shop\Product\ProductImportForm;
use services\manage\Shop\ProductReader;
use shop\entities\Meta;
use shop\entities\Shop\Product\Product;
use shop\entities\Shop\Tag;
use shop\forms\manage\Shop\Product\CategoriesForm;
use shop\forms\manage\Shop\Product\PhotosForm;
use shop\forms\manage\Shop\Product\ProductCreateForm;
use shop\forms\manage\Shop\Product\ProductEditForm;
use shop\repositories\Shop\BrandRepository;
use shop\repositories\Shop\CategoryRepository;
use shop\repositories\Shop\ProductRepository;
use shop\repositories\Shop\TagRepository;
use shop\services\TransactionManager;
use yii\web\UploadedFile;

class ProductManageService
{
    public $products;
    public $brands;
    public $categories;
    public $tags;
    public $transaction;

    public function __construct(
        ProductRepository $products,
        BrandRepository $brands,
        CategoryRepository $categories,
        TagRepository $tags,
        TransactionManager $transaction
    )
    {
        $this->products = $products;
        $this->brands = $brands;
        $this->categories = $categories;
        $this->tags = $tags;
        $this->transaction = $transaction;
    }

    public function create(ProductCreateForm $form): Product
    {
        $brand = $this->brands->get($form->brandId);
        $category = $this->categories->get($form->categories->main);

        $product = Product::create(
            $brand->id,
            $category->id,
            $form->code,
            $form->name,
            $form->description,
            $form->weight,
            $form->quantity->quantity,
            new Meta($form->meta->title,$form->meta->keywords,$form->meta->description)
        );

        $product->setPrice($form->price->new, $form->price->old);

        foreach($form->categories->others as $otherId){
            $category = $this->categories->get($otherId);
            $product->assignCategory($category->id);
        }

        foreach($form->values as $value){
            $product->setValue($value->id, $value->value);
        }

        if(!empty($form->photos->files)){
            foreach($form->photos->files as $file){
                $product->addPhoto($file);
            }
        }

        //--Tags
        foreach($form->tags->existing as $tagId){
            $tag = $this->tags->get($tagId);
            $product->assignTag($tag->id);
        }

        $this->transaction->wrap(function() use($form, $product){
            foreach($form->tags->newNames as $tagName){
                if( !($tag = $this->tags->findByName($tagName)) ){
                    $tag = Tag::create($tagName, $tagName);
                    $this->tags->save($tag);
                }

                $product->assignTag($tag->id);
            }

            $this->products->save($product);
        });

        return $product;
    }

    public function edit($id, ProductEditForm $form): void
    {
        $product = $this->products->get($id);
        $brand = $this->brands->get($form->brandId);
        $category = $this->categories->get($form->categories->main);

        $product->edit(
            $brand->id,
            $form->code,
            $form->name,
            $form->description,
            $form->weight,
            new Meta(
                $form->meta->title,
                $form->meta->keywords,
                $form->meta->description
            )
        );

        $product->changeMainCategory($category->id);

        $this->transaction->wrap(function () use ($product, $form) {

            $product->revokeCategories();
            $product->revokeTags();
            $this->products->save($product);

            foreach ($form->categories->others as $otherId) {
                $category = $this->categories->get($otherId);
                $product->assignCategory($category->id);
            }

            foreach ($form->values as $value) {
                $product->setValue($value->id, $value->value);
            }

            foreach ($form->tags->existing as $tagId) {
                $tag = $this->tags->get($tagId);
                $product->assignTag($tag->id);
            }

            if( !empty($form->tags->newNames) ){
                foreach ($form->tags->newNames as $tagName) {
                    if (!$tag = $this->tags->findByName($tagName)) {
                        $tag = Tag::create($tagName, $tagName);
                        $this->tags->save($tag);
                    }
                    $product->assignTag($tag->id);
                }
            }

            $this->products->save($product);
        });
    }

    public function changeCategories($id, CategoriesForm $form)
    {
        $product = $this->products->get($id);
        $category = $this->products->get($form->main);

        $product->changeMainCategory($category->id);
        $product->revokeCategories();

        foreach($form->others as $otherId){
            $category = $this->categories->get($otherId);
            $product->assignCategory($category->id);
        }

        $this->products->save($product);
    }

    public function addPhotos($id, PhotosForm $form): void
    {
        $product = $this->products->get($id);
        foreach($form->files as $file){
            $product->addPhoto($file);
        }

        $this->products->save($product);
    }

    public function movePhotoUp($id, $photoId): void
    {
        $product = $this->products->get($id);
        $product->movePhotoUp($photoId);
        $this->products->save($product);
    }

    public function movePhotoDown($id, $photoId): void
    {
        $product = $this->products->get($id);
        $product->movePhotoDown($photoId);
        $this->products->save($product);
    }

    public function removePhoto($id, $photoId): void
    {
        $product = $this->products->get($id);
        $product->removePhoto($photoId);
        $this->products->save($product);
    }

    public function remove($id)
    {
        $product = $this->products->get($id);
        $this->products->remove($product);
    }

    public function activate($id)
    {
        $product = $this->products->get($id);
        $product->activate();
        $this->products->save($product);
    }

    public function draft($id)
    {
        $product = $this->products->get($id);
        $product->draft();
        $this->products->save($product);
    }

    public function import(ProductImportForm $form): void
    {
        $reader = new ProductReader();
        $result = $reader->readCSV($form->file->tempName);

        foreach($result as $row){
            //Если такой товар есть => обновить
            $product = $this->products->getByCode($row->code);
            $product->setPrice($row->price_old, $row->price_new);
            $product->setModificationPriceByCode($row->modification, $row->modification_price);

            $this->products->save($product);
        }
    }
}