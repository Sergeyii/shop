<?php

namespace shop\helpers;

use shop\entities\Site\Manufacturer;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class ManufacturerHelper
{
    public static function getManufacturerMaxSort(): int
    {
        return Manufacturer::find()->max('sort')+1;
    }

    public static function statusList()
    {
        return [
            Manufacturer::STATUS_DRAFT => 'Draft',
            Manufacturer::STATUS_ACTIVE => 'Active',
        ];
    }

    public static function statusLabel($status): string
    {
        switch ($status) {
            case Manufacturer::STATUS_DRAFT:
                $class = 'label label-default';
                break;
            case Manufacturer::STATUS_ACTIVE:
                $class = 'label label-success';
                break;
            default:
                $class = 'label label-default';
        }
        return Html::tag('span', ArrayHelper::getValue(self::statusList(), $status), [
            'class' => $class,
        ]);
    }

    public static function getModelInitialImagesFile($model, $attribute): array
    {
        $initialImages = [];
        if($model){
            $fileUrl = $model->getUploadedFileUrl($attribute);
            if(!empty($fileUrl)){
                $initialImages[] = $fileUrl;
            }
        }
        return $initialImages;
    }
}