<?php

namespace frontend\widgets\Site;

use shop\entities\Site\Manufacturer;
use shop\readModels\Site\ManufacturerReadRepository;
use yii\base\Widget;

class ManufacturersWidget extends Widget
{
    public $limit;

    private $repository;

    public function __construct(ManufacturerReadRepository $repository,array $config = [])
    {
        $this->repository = $repository;
        parent::__construct($config);
    }

    public function run(): string
    {
        $manufacturers = $this->repository->getAll($this->limit);

        if(empty($manufacturers)){
            return '';
        }

        return $this->render('manufacturers', [
            'manufacturers' => $manufacturers,
        ]);
    }
}