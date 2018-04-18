<?php

namespace shop\useCases\manage;

use shop\forms\manage\ManufacturerForm;
use shop\repositories\ManufacturerRepository;

class ManufacturerManageService
{
    private $repository;

    public function __construct(ManufacturerRepository $repository)
    {
        $this->repository = $repository;
    }

    public function get($id)
    {
        $model = $this->repository->get($id);
        return $model;
    }

    public function create(ManufacturerForm $form)
    {
        $model = $this->repository->create($form->title, $form->slug, $form->description, $form->sort, $form->file);
        $this->repository->save($model);
        return $model;
    }

    public function edit($id, ManufacturerForm $form): void
    {
        $model = $this->repository->get($id);
        $model->edit($form->title, $form->slug, $form->description, $form->sort, $form->file);
        $this->repository->save($model);
    }

    public function activate($id): void
    {
        $model = $this->repository->get($id);

        if($model->isActive()){
            throw new \DomainException('Manufacturer is already active.');
        }

        $model->activate();
        $this->repository->save($model);
    }

    public function draft($id): void
    {
        $model = $this->repository->get($id);

        if($model->isDrafted()){
            throw new \DomainException('Manufacturer is already active.');
        }

        $model->draft();
        $this->repository->save($model);
    }

    public function remove($id): void
    {
        $model = $this->repository->get($id);
        $this->repository->remove($model);
    }

    public function removePhoto($id): void
    {
        $model = $this->repository->get($id);
        $model->removePhoto();
        $this->repository->save($model);
    }
}