<?php

namespace shop\services\manage\Shop;

use shop\entities\Shop\Characteristic;
use shop\forms\manage\Shop\CharacteristicForm;
use shop\repositories\Shop\CharacteristicRepository;

class CharacteristicManageService
{
    private $repository;

    public function __construct(CharacteristicRepository $repository)
    {
        $this->repository = $repository;
    }

    public function create(CharacteristicForm $form): Characteristic
    {
        $characteristic = Characteristic::create(
            $form->name,
            $form->type,
            $form->required,
            $form->default,
            $form->variants,
            $form->sort
        );

        $this->repository->save($characteristic);

        return $characteristic;
    }

    public function edit($id, CharacteristicForm $form): void
    {
        $characteristic = $this->repository->get($id);
        $characteristic->edit($form->name,
            $form->type,
            $form->required,
            $form->default,
            $form->variants,
            $form->sort);

        $this->repository->save($characteristic);
    }

    public function remove($id): void
    {
        $characteristic = $this->repository->get($id);
        $this->repository->remove($characteristic);
    }
}