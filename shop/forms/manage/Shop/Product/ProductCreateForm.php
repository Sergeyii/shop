<?php

namespace shop\forms\manage\Shop\Product;

use shop\entities\Shop\Characteristic;
use shop\entities\Shop\Product\Product;
use shop\forms\CompositeForm;
use shop\forms\manage\MetaForm;
use shop\entities\Shop\Brand;
use shop\readModels\Shop\BrandReadRepository;
use yii\helpers\ArrayHelper;

class ProductCreateForm extends CompositeForm
{
    public $brandId;
    public $code;
    public $name;
    public $description;
    public $weight;
    public $quantity;

    public $brandReadRepository;

    public function __construct(BrandReadRepository $brandReadRepository = null, array $config = [])
    {
        $this->brandReadRepository = $brandReadRepository;

        $this->price = new PriceForm();
        $this->meta = new MetaForm();
        $this->categories = new CategoriesForm();
        $this->photos = new PhotosForm();
        $this->tags = new TagsForm();
        $this->quantity = new QuantityForm();
        $this->values = array_map(function(Characteristic $characteristic){
            return new ValueForm($characteristic);
        }, Characteristic::find()->orderBy('sort')->all());

        parent::__construct($config);
    }

    public function rules()
    {
        return [
            [['brandId', 'code', 'name'], 'required'],
            [['code', 'name'], 'string', 'max' => 255],
            [['brandId'], 'integer'],
            ['code', 'unique', 'targetClass' => Product::class],
            ['description', 'string'],
        ];
    }

    protected function internalForms(): array
    {
        return ['price', 'meta', 'photos', 'categories', 'tags', 'values'];
    }

    public function brandsList(): array
    {
        return $this->brandReadRepository->brandsList('id');
    }
}