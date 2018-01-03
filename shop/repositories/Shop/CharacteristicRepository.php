<?php

namespace shop\repositories\Shop;

use shop\entities\Shop\Characteristic;
use shop\repositories\NotFoundException;

class CharacteristicRepository
{
    public function get($id): Characteristic
    {
        if( !$characteristic = Characteristic::findOne($id) ){
            throw new NotFoundException('Characteristic is not found!');
        }

        return $characteristic;
    }

    public function save(Characteristic $characteristic): void
    {
        if( !$characteristic->save() ){
            throw new \RuntimeException('Characteristic saving error!');
        }
    }

    public function remove(Characteristic $characteristic)
    {
        if( !$characteristic->delete() ){
            throw new \RuntimeException('Characteristic removing error!');
        }
    }

}