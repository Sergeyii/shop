<?php

namespace shop\helpers;

use shop\entities\User\User;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class UserHelper
{

    public static function statusList():array
    {
        return [
            User::STATUS_WAIT => 'Ожидающий',
            User::STATUS_ACTIVE => 'Активный',
        ];
    }

    public static function statusName($status){
        return ArrayHelper::getValue(self::statusList(), $status);
    }

    public static function statusLabel($status):string
    {
        switch($status){
            case User::STATUS_ACTIVE:
                $class = 'label label-success';
                break;
            case User::STATUS_WAIT:
                $class = 'label label-default';
                break;
            default:
                $class = 'label label-default';
                break;
        }

        return Html::tag('span', self::statusName($status), [
            'class' => $class,
        ]);
    }
}