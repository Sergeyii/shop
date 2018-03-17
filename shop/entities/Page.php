<?php

namespace shop\entities;

use paulzi\nestedsets\NestedSetsBehavior;
use shop\entities\behaviors\MetaBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "pages".
 *
 * @property integer $id
 * @property string $title
 * @property string $slug
 * @property string $content
 * @property string $meta_json
 * @property integer $lft
 * @property integer $rgt
 * @property integer $depth
 * @property Meta $meta
 */
class Page extends ActiveRecord
{
    public $meta;

    public static function create($title, $slug, $content, Meta $meta): self
    {
        $page = new static();
        $page->title = $title;
        $page->slug = $slug;
        $page->content = $content;
        $page->meta = $meta;
        return $page;
    }

    public function edit($title, $slug, $content, Meta $meta): void
    {
        $this->title = $title;
        $this->slug = $slug;
        $this->content = $content;
        $this->meta = $meta;
    }

    public function behaviors(): array
    {
        return [
            MetaBehavior::class,
        ];
    }

    public function transactions(): array
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }

    public static function tableName(): string
    {
        return 'pages';
    }
}
