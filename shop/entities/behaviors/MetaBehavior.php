<?php

namespace shop\entities\behaviors;

use shop\entities\Meta;
use yii\base\Behavior;
use yii\base\Event;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;

class MetaBehavior extends Behavior
{
    public $attribute = 'meta';
    public $jsonAttribute = 'meta_json';

    public function events()
    {
        return [
            ActiveRecord::EVENT_AFTER_FIND => 'handleAfterFind',
            ActiveRecord::EVENT_BEFORE_INSERT => 'handleBeforeSave',
            ActiveRecord::EVENT_BEFORE_UPDATE => 'handleBeforeSave',
        ];
    }

    public function handleAfterFind(Event $event){
        $meta = Json::decode($event->sender->getAttribute($this->jsonAttribute));
        $event->sender->{$this->attribute} = new Meta(
            ArrayHelper::getValue($meta, 'title'),
            ArrayHelper::getValue($meta, 'keywords'),
            ArrayHelper::getValue($meta, 'description')
        );
    }

    public function handleBeforeSave(Event $event){
        $event->sender->setAttribute($this->jsonAttribute, Json::encode([
                'title' => $event->sender->{$this->attribute}->title,
                'keywords' => $event->sender->{$this->attribute}->keywords,
                'description' => $event->sender->{$this->attribute}->description
            ])
        );
    }
}