<?php

namespace shop\forms\manage\Shop\Product;

use shop\entities\Shop\Brand;
use shop\entities\Shop\Characteristic;
use shop\entities\Shop\Product\Product;
use shop\forms\CompositeForm;
use shop\forms\manage\MetaForm;
use yii\helpers\ArrayHelper;

class ProductEditForm extends CompositeForm
{
    public $brandId;
    public $code;
    public $name;
    public $description;

    public $weight;
    public $categories;

    private $_product;
    public $brandReadRepository;

    public function __construct(Product $product, BrandReadRepository $brandReadRepository = null, array $config = [])
    {
        $this->_product = $product;
        $this->brandReadRepository = $brandReadRepository;

        $this->code = $product->code;
        $this->name = $product->name;
        $this->description = $product->description;

        $this->meta = new MetaForm($product->meta);
        $this->categories = new CategoriesForm($product);
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
            [['description'], 'string'],
        ];
    }

    protected function internalForms(): array
    {
        return ['meta', 'tags', 'values'];
    }

    public function brandsList(): array
    {
        return $this->brandReadRepository->brandsList('name');
    }
}