<?php

namespace shop\services\manage\Shop;

use shop\entities\Shop\DeliveryMethod;
use shop\forms\manage\Shop\DeliveryMethodForm;
use shop\repositories\Shop\DeliveryMethodRepository;

class DeliveryMethodManageService
{
    private $repository;

    public function __construct(DeliveryMethodRepository $repository)
    {
        $this->repository = $repository;
    }

    public function get($id): DeliveryMethod
    {
        return $this->repository->get($id);
    }

    public function create(DeliveryMethodForm $form): DeliveryMethod
    {
        $method = DeliveryMethod::create($form->name, $form->cost, $form->minWeight, $form->maxWeight, $form->sort);
        $this->repository->save($method);
        return $method;
    }

    public function edit(int $id, DeliveryMethodForm $form)
    {
        $method = $this->get($id);
        $method->edit($form->name, $form->cost, $form->minWeight, $form->maxWeight, $form->sort);
        $this->repository->save($method);
    }

    public function remove($id)
    {
        $method = $this->repository->get($id);
        $this->repository->remove($method);
    }
}