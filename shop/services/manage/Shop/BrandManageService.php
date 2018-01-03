<?php

namespace shop\services\manage\Shop;

use shop\entities\Meta;
use shop\entities\Shop\Brand;
use shop\forms\manage\Shop\BrandForm;
use shop\repositories\Shop\BrandRepository;
use shop\repositories\Shop\ProductRepository;

class BrandManageService
{
    public $repository;
    public $productRepository;

    public function __construct(BrandRepository $repository, ProductRepository $productRepository)
    {
        $this->repository = $repository;
        $this->productRepository = $productRepository;
    }

    public function create(BrandForm $form): Brand
    {
        $brand = Brand::create($form->name, $form->slug, new Meta($form->meta->title, $form->meta->keywords, $form->meta->description));
        $this->repository->save($brand);

        return $brand;
    }

    public function edit($id, BrandForm $form): void
    {
        $brand = $this->repository->get($id);
        $brand->edit($form->name, $form->slug, new Meta($form->meta->title, $form->meta->keywords, $form->meta->description));

        $this->repository->save($brand);
    }

    public function remove($id): void
    {
        $brand = $this->repository->get($id);

        //Если к бренду привязаны товары => запретить удаление брэнда
        if($this->productRepository->existsByBrand($brand->id)){
            throw new \DomainException('Brand can\'t be removed because some products are chained to this brand!');
        }

        $this->repository->remove($brand);
    }
}