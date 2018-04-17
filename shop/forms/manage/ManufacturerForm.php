<?php

namespace shop\forms\manage;

use shop\entities\Site\Manufacturer;
use yii\base\Model;
use yii\helpers\Inflector;

class ManufacturerForm extends Model
{
    public $title;
    public $slug;
    public $description;

    public $_manufacturer;

    public function __construct(Manufacturer $manufacturer=null, array $config = [])
    {
        if($manufacturer){
            $this->_manufacturer = $manufacturer;

            $this->title = $manufacturer->title;
            $this->slug = $manufacturer->slug;
            $this->description = $manufacturer->description;
        }
        parent::__construct($config);
    }

    public function rules(): array
    {
        return [
            [['title', 'slug'], 'required'],
            [['title', 'slug'], 'string', 'max' => 255],
            [['description'], 'string'],
            [['slug'], 'unique', 'targetClass' => Manufacturer::class, 'filter' => $this->_manufacturer ? ['<>', 'id', $this->_manufacturer->id] : null],
        ];
    }

    public function beforeValidate(): bool
    {
        if(parent::beforeValidate()){
            $this->slug = Inflector::slug($this->slug);
            return true;
        }
        return false;
    }
}