<?php

namespace shop\forms\manage;

use shop\entities\Site\Manufacturer;
use shop\helpers\ManufacturerHelper;
use yii\base\Model;
use yii\helpers\Inflector;
use yii\web\UploadedFile;

class ManufacturerForm extends Model
{
    public $title;
    public $sort;
    public $slug;
    public $description;
    public $file;

    public $_manufacturer;

    public function __construct(Manufacturer $manufacturer=null, array $config = [])
    {
        if($manufacturer){
            $this->_manufacturer = $manufacturer;

            $this->title = $manufacturer->title;
            $this->sort = $manufacturer->sort;
            $this->slug = $manufacturer->slug;
            $this->description = $manufacturer->description;
            $this->file = $manufacturer->file;
        }else{
            $this->sort = ManufacturerHelper::getManufacturerMaxSort();
        }
        parent::__construct($config);
    }

    public function rules(): array
    {
        return [
            [['title', 'slug'], 'required'],
            [['title', 'slug'], 'string', 'max' => 255],
            [['sort'], 'integer'],
            [['description'], 'string'],
            [['file'], 'image'],
            [['slug'], 'unique', 'targetClass' => Manufacturer::class, 'filter' => $this->_manufacturer ? ['<>', 'id', $this->_manufacturer->id] : null],
        ];
    }

    public function beforeValidate(): bool
    {
        if(parent::beforeValidate()){
            $this->slug = Inflector::slug($this->slug);
            $this->file = UploadedFile::getInstance($this, 'file');
            return true;
        }
        return false;
    }
}