<?php

namespace shop\entities\behaviors;

use yii\base\Behavior;
use yii\base\Event;
use yii\db\ActiveRecord;
use yii\helpers\Json;

class JsonParamBehavior extends Behavior
{
    public $attribute;
    public $dbAttribute;
    
    public function events()
    {
        return [
            ActiveRecord::EVENT_AFTER_FIND => 'handleAfterFind',
            ActiveRecord::EVENT_BEFORE_INSERT => 'handleBeforeSave',
            ActiveRecord::EVENT_BEFORE_UPDATE => 'handleBeforeSave',
        ];
    }

    public function handleAfterFind(Event $event)
    {
        $event->sender->{$this->attribute} = Json::decode($event->sender->getAttribute($this->dbAttribute));
    }

    public function handleBeforeSave(Event $event)
    {
        $event->sender->setAttribute($this->dbAttribute, Json::encode($event->sender->{$this->attribute}));
    }
}