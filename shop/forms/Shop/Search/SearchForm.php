<?php

namespace shop\forms\Shop\Search;

use shop\entities\Shop\Characteristic;
use shop\readModels\Shop\BrandReadRepository;
use shop\readModels\Shop\CategoryReadRepository;
use shop\forms\CompositeForm;

/* @property ValueForm $values */

class SearchForm extends CompositeForm
{
    public $text;
    public $category;
    public $brand;

    public $categories;
    public $brandReadRepository;

    public function __construct(CategoryReadRepository $categories, BrandReadRepository $brandReadRepository, array $config = [])
    {
        $this->categories = $categories;
        $this->brandReadRepository = $brandReadRepository;

        $this->values = array_map(function(Characteristic $characteristic){
            return new ValueForm($characteristic);
        }, Characteristic::find()->orderBy('sort')->all());

        parent::__construct($config);
    }

    public function rules(): array
    {
        return [
            ['text', 'string'],
            [['category','brand'], 'integer'],
        ];
    }

    public function categoriesList(): array
    {
        return $this->categories->categoriesList();
    }

    public function brandsList(): array
    {
        return $this->brandReadRepository->brandsList('name');
    }

    public function formName(): string
    {
        return '';
    }

    protected function internalForms(): array
    {
        return ['values'];
    }
}