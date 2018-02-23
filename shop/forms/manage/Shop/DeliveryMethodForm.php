<?php

namespace shop\forms\manage\Shop;

use shop\entities\Shop\DeliveryMethod;
use yii\base\Model;

class DeliveryMethodForm extends Model
{
    public $name;
    public $cost;
    public $minWeight;
    public $maxWeight;
    public $sort;

    public function __construct(DeliveryMethod $method=null, array $config = [])
    {
        if($method){
            $this->name = $method->name;
            $this->cost = $method->cost;
            $this->minWeight = $method->min_weight;
            $this->maxWeight = $method->max_weight;
            $this->sort = $method->sort;
        }else{
            $this->sort = DeliveryMethod::find()->max('sort')+1;
        }

        parent::__construct($config);
    }

    public function rules()
    {
        return [
            [['name', 'cost', 'minWeight', 'maxWeight'], 'required'],
            [['name'], 'string'],
            [['cost'], 'number'],
            [['minWeight', 'maxWeight'], 'number'],
            [['sort'], 'integer'],
        ];
    }
}