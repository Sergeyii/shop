<?php

namespace shop\forms\Shop\Order;

use yii\base\Model;

class CustomerForm extends Model
{
    public $phone;
    public $name;

    public function rules()
    {
        return [
            [['phone', 'name'], 'required'],
            [['phone', 'name'], 'string', 'max' => 255],
        ];
    }
}