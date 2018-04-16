<?php

namespace api\providers;

use yii\base\Object;
use yii\data\DataProviderInterface;

class MapDataProvider extends Object implements DataProviderInterface
{
    private $dataProvider;
    private $formatterClass;

    public function __construct(DataProviderInterface $dataProvider, $formatterClass, array $config = [])
    {
        $this->dataProvider = $dataProvider;
        $this->formatterClass = $formatterClass;
        parent::__construct($config);
    }

    public function prepare($forcePrepare = false)
    {
        $this->dataProvider->prepare($forcePrepare);
    }

    public function getTotalCount(): int
    {
        return $this->dataProvider->getTotalCount();
    }

    public function getSort()
    {
        return $this->dataProvider->getSort();
    }

    public function getPagination()
    {
        return $this->dataProvider->getPagination();
    }

    public function getKeys()
    {
        return $this->dataProvider->getKeys();
    }

    public function getModels()
    {
        return array_map(function($model){
            return (new $this->formatterClass($model))->format();
        }, $this->dataProvider->getModels());
    }

    public function getCount()
    {
        return $this->dataProvider->getCount();
    }
}