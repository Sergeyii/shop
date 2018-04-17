<?php

namespace shop\repositories;

use shop\entities\Site\Manufacturer;

class ManufacturerRepository
{
    public function get($id): Manufacturer
    {
        if( !$model = Manufacturer::findOne($id) ){
            throw new NotFoundException('Manufacturer not found.');
        }
        return $model;
    }

    public function create($title, $slug, $description): Manufacturer
    {
        return Manufacturer::create($title, $slug, $description);
    }

    public function save(Manufacturer $model): void
    {
        if( !$model->save() ){
            throw new \RuntimeException('Manufacturer saving error.');
        }
    }

    public function remove(Manufacturer $model): void
    {
        if( !$model->delete() ){
            throw new \RuntimeException('Manufacturer removing error.');
        }
    }
}