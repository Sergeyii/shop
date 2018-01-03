<?php

namespace shop\forms\manage\Shop;

use shop\entities\Meta;
use shop\entities\Shop\Brand;
use shop\forms\CompositeForm;
use shop\forms\manage\MetaForm;
use shop\validators\SlugValidator;


/**
 * @property Meta $meta
 * @property Brand $_brand
*/
class BrandForm extends CompositeForm
{
    public $name;
    public $slug;

    public $_brand;

    protected function internalForms(): array
    {
        return ['meta'];
    }

    public function __construct(Brand $brand = null, array $config = [])
    {
        if($brand){
            $this->_brand = $brand;
            $this->meta = new MetaForm($this->_brand->meta);

            $this->name = $brand->name;
            $this->slug = $brand->slug;
        }else{
            $this->meta = new MetaForm();
        }

        parent::__construct($config);
    }

    public function rules()
    {
        return [
            [['name', 'slug'], 'required'],
            [['name', 'slug'], 'string', 'max' => 255],
            //[['slug'], SlugValidator::class],
            [['name', 'slug'], 'unique', 'targetClass' => Brand::class, 'filter' => $this->_brand ? ['<>', 'id', $this->_brand->id] : null],
        ];
    }
}