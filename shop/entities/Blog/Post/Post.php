<?php

namespace shop\entities\Blog\Post;
use lhs\Yii2SaveRelationsBehavior\SaveRelationsBehavior;
use shop\entities\behaviors\MetaBehavior;
use shop\entities\Blog\Category;
use shop\entities\Blog\Post\queries\PostQuery;
use shop\entities\Blog\Tag;
use shop\entities\Meta;
use shop\services\WaterMarker;
use yii\db\ActiveQuery;
use yiidreamteam\upload\ImageUploadBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "blog_posts".
 *
 * @property integer $id
 * @property integer $category_id
 * @property integer $created_at
 * @property string $title
 * @property string $description
 * @property string $content
 * @property string $photo
 * @property integer $status
 * @property string $meta_json
 *
 * @property BlogCategories $category
 * @property TagAssignment[] $tagAssignments
 * @mixin ImageUploadBehavior
 */
class Post extends ActiveRecord
{
    const STATUS_DRAFT = 0;
    const STATUS_ACTIVE = 1;

    public $meta;

    public static function create($categoryId, $title, $description, $content, Meta $meta): self
    {
        $post = new static();
        $post->category_id = $categoryId;
        $post->title = $title;
        $post->description = $description;
        $post->content = $content;
        $post->meta = $meta;
        $post->status = self::STATUS_DRAFT;
        $post->created_at = time();
        return $post;
    }

    public function setPhoto($photo): void
    {
        $this->photo = $photo;
    }

    public function removePhoto(): void
    {
        $this->cleanFiles();
        $this->setPhoto('');
    }

    public function draft(): void
    {
        $this->status = self::STATUS_ACTIVE;
    }

    public function activate(): void
    {
        $this->status = self::STATUS_DRAFT;
    }

    public function isActive(): bool
    {
        return $this->status == self::STATUS_ACTIVE;
    }

    public function isDraft(): bool
    {
        return $this->status == self::STATUS_DRAFT;
    }

    public function edit($categoryId, $title, $description, $content, Meta $meta): void
    {
        $this->category_id = $categoryId;
        $this->title = $title;
        $this->description = $description;
        $this->content = $content;
        $this->meta = $meta;
    }

    public function assignTag(Tag $tag): void
    {
        $tagAssignments = $this->tagAssignments;
        foreach($tagAssignments as $tagAssignment){
            if($tagAssignment->isForTag($tag->id)){
                return;
            }
        }
        $tagAssignments[] = TagAssignment::create($tag->id);
        $this->tagAssignments = $tagAssignments;
    }

    public function revokeTags(): void
    {
        $this->tagAssignments = [];
    }

    public function getCategory(): ActiveQuery
    {
        return $this->hasOne(Category::class, ['id' => 'category_id']);
    }

    public function getTagAssignments(): ActiveQuery
    {
        return $this->hasMany(TagAssignment::class, ['post_id' => 'id']);
    }

    public function getTags(): ActiveQuery
    {
        return $this->hasMany(Tag::class, ['id' => 'tag_id'])->via('tagAssignments');
    }

    public static function tableName()
    {
        return 'blog_posts';
    }

    public function behaviors(): array
    {
        return [
            MetaBehavior::class,
            [
                'class' => SaveRelationsBehavior::class,
                'relations' => ['tagAssignments'],
            ],
            [
                'class' => ImageUploadBehavior::class,
                'attribute' => 'photo',
                'createThumbsOnRequest' => true,
                'filePath' => '@staticRoot/origin/posts/[[id]].[[extension]]',
                'fileUrl' => '@static/origin/posts/[[id]].[[extension]]',
                'thumbPath' => '@staticRoot/origin/posts/[[profile]]_[[pk]].[[extension]]',
                'thumbUrl' => '@static/origin/posts/[[profile]]_[[pk]].[[extension]]',
                'thumbs' => [
                    'thumb' => ['width' => 100,  'height' => 70],
                    'admin' => ['width' => 640,  'height' => 480],
                    'origin' => ['processor' => [new WaterMarker(1024, 768, '@static/post/logo.png'), 'process']],
                ],
            ]
        ];
    }

    public function transactions(): array
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }

    public static function find(): PostQuery
    {
        return new PostQuery(static::class);
    }
}