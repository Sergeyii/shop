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
        $model = $this->repository->create($form->title, $form->slug, $form->description);
        $this->repository->save($model);
        return $model;
    }

    public function edit($id, ManufacturerForm $form): void
    {
        $model = $this->repository->get($id);
        $model->edit($form->title, $form->slug, $form->description);
        $this->repository->save($model);
    }

    public function remove($id)
    {
        $model = $this->repository->get($id);
        $this->repository->remove($model);
    }
}