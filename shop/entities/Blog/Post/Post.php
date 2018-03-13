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
use yii\web\UploadedFile;
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
 * @property Meta $meta
 * @property integer $comments_count
 * @property Comment $comments
 *
 * @property Category $category
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
        $post->comments_count = 0;
        return $post;
    }

    public function setPhoto(UploadedFile $photo): void
    {
        $this->photo = $photo;
    }

    public function edit($categoryId, $title, $description, $content, Meta $meta): void
    {
        $this->category_id = $categoryId;
        $this->title = $title;
        $this->description = $description;
        $this->content = $content;
        $this->meta = $meta;
    }

    public function activate(): void
    {
        $this->status = self::STATUS_ACTIVE;
    }

    public function draft(): void
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

    public function getSeoTitle(): string
    {
        return $this->meta ? $this->meta->title : $this->title;
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

    //Comments

    public function addComment($userId, $parentId, $text): Comment
    {
        $parent = $parentId ? $this->getComment($parentId) : null;
        if($parent && !$parent->isActive()){
            throw new \DomainException('Cannot add comment to inactive parent.');
        }

        $comments = $this->comments;
        $comments[] = $comment = Comment::create($this->id, $userId, $parentId ? $parent->id : null, $text);
        $this->updateComments($comments);
        return $comment;
    }

    public function editComment($id, $parentId, $text): void
    {
        $parent = $parentId ? $this->getComment($parentId) : null;
        $comments = $this->comments;
        foreach($comments as $comment){
            if($comment->isIdEqualTo($id)){
                $comment->edit($parent ? $parent->id : null, $text);
                $this->updateComments($comments);
                return;
            }
        }

        throw new \DomainException('Comment not found.');
    }

    public function activateComment($id): void
    {
        $comments = $this->comments;
        foreach($comments as $comment){
            if($comment->isIdEqualTo($id)){
                $comment->activate();
                $this->updateComments($comments);
                return;
            }
        }

        throw new \DomainException('Comment not found.');
    }

    public function removeComment($id): void
    {
        $comments = $this->comments;
        foreach($comments as $i => $comment){
            if($comment->isIdEqualTo($id)){
                if($this->hasChildren($comment->id)){
                    $comment->draft();
                }else{
                    unset($comments[$i]);
                }
                $this->updateComments($comments);
                return;
            }
        }

        throw new \DomainException('');
    }

    public function getComment($id): Comment
    {
        foreach($this->comments as $comment){
            if($comment->isIdEqualTo($id)){
                return $comment;
            }
        }

        throw new \DomainException('Comment not found.');
    }

    private function hasChildren($id): bool
    {
        foreach($this->comments as $comment){
            if($comment->isChildOf($id)){
                return true;
            }
        }
        return false;
    }

    private function updateComments(array $comments): void
    {
        $this->comments = $comments;
        $this->comments_count = count(array_filter($comments, function(Comment $comment){
            return $comment->isActive();
        }));
    }

    ##########################

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

    public function getComments(): ActiveQuery
    {
        return $this->hasMany(Comment::class, ['post_id' => 'id']);
    }

    ##########################

    public static function tableName(): string
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
                    'blog_list' => ['width' => 1000,  'height' => 150],
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