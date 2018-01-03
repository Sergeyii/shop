<?php

namespace shop\forms\manage\Shop\Product;

use shop\entities\Shop\Characteristic;
use shop\entities\Shop\Product\Product;
use shop\forms\CompositeForm;
use shop\forms\manage\MetaForm;

class ProductEditForm extends CompositeForm
{
    public $brandId;
    public $code;
    public $name;

    private $_product;

    public function __construct(Product $product, array $config = [])
    {
        $this->_product = $product;
        $this->meta = new MetaForm($product->meta);
        $this->tags = new TagsForm($product);
        $this->values = array_map(function(Characteristic $characteristic){
            return new ValueForm($characteristic, $this->_product->getValue($characteristic->id));
        }, Characteristic::find()->orderBy('sort')->all());

        parent::__construct($config);
    }

    public function rules()
    {
        return [
            [['brandId', 'code', 'name'], 'required'],
            [['code', 'name'], 'string', 'max' => 255],
            [['brandId'], 'integer'],
            ['code', 'unique', 'targetClass' => Product::class, 'filter' => $this->_product ? ['<>', 'id', $this->_product->id] : null],
        ];
    }

    protected function internalForms(): array
    {
        return ['meta', 'tags', 'values'];
    }
}