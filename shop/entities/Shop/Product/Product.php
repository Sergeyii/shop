<?php

namespace shop\entities\Shop\Product;

use lhs\Yii2SaveRelationsBehavior\SaveRelationsBehavior;
use shop\entities\behaviors\MetaBehavior;
use shop\entities\Meta;
use shop\entities\Shop\Brand;
use shop\entities\Shop\Category;
use shop\entities\Shop\Product\queries\ProductQuery;
use shop\entities\Shop\Tag;
use shop\entities\User\WishlistItem;
use shop\entities\Shop\Product\Modification;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\web\UploadedFile;
use yiidreamteam\upload\ImageUploadBehavior;

/**
 * @property integer $status
 * @property integer $price_new
 * @property integer $price_old
 * @property string $name
 * @property string $description
 * @property Value[] values
 * @property Photo[] photos
 * @property Modification[] modifications
 * @property Review[] reviews
 * @mixin ImageUploadBehavior mainPhoto
 * @mixin MetaBehavior $meta
 * */

class Product extends ActiveRecord
{
    public $meta;

    const STATUS_DRAFT = 0;
    const STATUS_ACTIVE = 1;

    public static function tableName()
    {
        return '{{%shop_products}}';
    }

    public static function create($brandId, $categoryId, $code, $name, $description, Meta $meta): self
    {
        $product = new static();
        $product->brand_id = $brandId;
        $product->category_id = $categoryId;
        $product->code = $code;
        $product->name = $name;
        $product->description = $description;
        $product->meta = $meta;

        $product->status = self::STATUS_DRAFT;
        $product->created_at = time();

        return $product;
    }

    public function setPrice($new, $old): void
    {
        $this->price_new = $new;
        $this->price_old = $old;
    }

    public function edit($brandId, $code, $name, $description, Meta $meta): void
    {
        $this->brand_id = $brandId;
        $this->code = $code;
        $this->name = $name;
        $this->description = $description;
        $this->meta = $meta;
    }

    public function updatePhotos(array $photos)
    {
        foreach($photos as $i => $photo){
            $photo->setSort($i);
        }
        $this->photos = $photos;
        $this->populateRelation('mainPhoto', reset($photos));
    }

    //--Related products
    public function assignRelatedProducts($id): void
    {
        $assignments = $this->relatedAssignments;
        foreach($assignments as $assignment){
            if($assignment->isForProduct($id)){
                return;
            }
        }

        $assignments[] = CategoryAssignment::create($id);
        $this->relatedAssignments = $assignments;
    }
    //-

    public function getBrand(): ActiveQuery
    {
        return $this->hasOne(Brand::class, ['id' => 'brand_id']);
    }

    public function getCategory(): ActiveQuery
    {
        return $this->hasOne(Category::class, ['id' => 'category_id']);
    }

    public function getCategoryAssignments(): ActiveQuery
    {
        return $this->hasMany(CategoryAssignment::class, ['product_id' => 'id']);
    }

    public function getCategories(): ActiveQuery
    {
        return $this->hasMany(Category::class, ['id' => 'category_id'])->via('categoryAssignments');
    }

    public function getTagAssignments(): ActiveQuery
    {
        return $this->hasMany(TagAssignment::class, ['product_id' => 'id']);
    }

    public function getTags(): ActiveQuery
    {
        return $this->hasMany(Tag::class, ['id' => 'tag_id'])->via('tagAssignments');
    }

    public function getModifications(): ActiveQuery
    {
        return $this->hasMany(Modification::class, ['product_id' => 'id']);
    }

    public function getValues(): ActiveQuery
    {
        return $this->hasMany(Value::class, ['product_id' => 'id']);
    }

    public function getPhotos(): ActiveQuery
    {
        return $this->hasMany(Photo::class, ['product_id' => 'id'])->orderBy('sort');
    }

    public function getMainPhoto(): ActiveQuery
    {
        return $this->hasOne(Photo::class, ['id' => 'main_photo_id']);
    }

    public function getRelatedAssignments(): ActiveQuery
    {
        return $this->hasMany(RelatedAssignment::class, ['product_id' => 'id']);
    }

    public function getReviews(): ActiveQuery
    {
        return $this->hasMany(Review::class, ['product_id' => 'id']);
    }

    public function getWishlistItems(): ActiveQuery
    {
        return $this->hasMany(WishlistItem::class, ['product_id' => 'id']);
    }

    public function changeMainCategory($categoryId): void
    {
        $this->category_id = $categoryId;
    }

    public function activate(): void
    {
        if ($this->isActive()) {
            throw new \DomainException('Product is already active.');
        }
        $this->status = self::STATUS_ACTIVE;
    }

    public function draft(): void
    {
        if ($this->isDraft()) {
            throw new \DomainException('Product is already draft.');
        }
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

    public function isAvailable(): bool
    {
        return $this->quantity > 0;
    }

    public function canChangeQuantity(): bool
    {
        return !$this->modifications;
    }

    public function getSeoTitle(): string
    {
        return $this->meta->title ?: $this->name;
    }

    public function setValue($id, $value): void
    {
        $values = $this->values;
        foreach($values as $val){
            if($val->isForCharacteristic($id)){
                return;
            }
        }

        $values[] = Value::create($id, $value);
        $this->values = $values;
    }

    public function getValue($id)
    {
        $values = $this->values;
        foreach($values as $val){
            if($val->isForCharacteristic($id)){
                return $val;
            }
        }

        return Value::blank($id);
    }

    //--Modifications
    public function getModification($id): Modification
    {
        foreach($this->modifications as $modification){
            if($modification->isIdEqualTo($id)){
                return $modification;
            }
        }

        throw new \DomainException('Modification not found!');
    }

    public function getModificationPrice($id): float
    {
        foreach($this->modifications as $modification){
            if($modification->isIdEqualTo($id)){
                return $modification->price ? $modification->price : $this->price_new;
            }
        }

        throw new \DomainException('Modification not found!');
    }

    public function addModification($code, $name, $price): void
    {
        foreach($this->modifications as $modification){
            if($modification->isCodeEqualTo($code)){
                throw new \DomainException('Modification is already exists!');
            }
        }

        $modification = Modification::create($code, $name, $price);
        $this->modifications[] = $modification;
    }

    public function editModification($id, $code, $name, $price): void
    {
        foreach($this->modifications as $modification){
            if($modification->isIdEqualTo($id)){
                $modification->edit($code, $name, $price);
                return;
            }
        }

        throw new \DomainException('Modification not found!');
    }
    //-

    public function assignCategory($id): void
    {
        $assignments = $this->getCategoryAssignments()->all();
        foreach($assignments as $assignment){
            if(!empty($assignment)){
                if($assignment->isForCategory($id)) {
                    return;
                }
            }
        }

        $assignments[] = CategoryAssignment::create($id);
        $this->categoryAssignments = $assignments;
    }

    public function revokeCategory($id): void
    {
        $assignments = $this->getCategoryAssignments()->all();

        foreach($assignments as $i => $assignment){
            if($assignment->isForCategory($id)){
                unset($assignments[$i]);
                $this->categoryAssignments = $assignments;
                return;
            }
        }

        throw new \DomainException('Assignment not found!');
    }

    //--Reviews
    public function addReview($userId, int $vote, string $text)
    {
        $reviews = $this->reviews;
        $reviews[] = Review::create($userId, $vote, $text);
        $this->updateReviews($reviews);
    }

    public function doWithReview($id, callable $callback)
    {
        $reviews = $this->reviews;

        foreach($reviews as $review){
            if($review->isIdEqualTo($id)){
                $callback($review);

                //$review->edit($vote, $text);
                $this->updateReviews($reviews);
                return;
            }
        }

        throw new \DomainException('Review not found!');
    }

    public function editReview($id, $vote, $text): void
    {
        $this->doWithReview($id, function(Review $review) use($vote, $text){
            $review->edit($vote, $text);
        });
    }

    public function activateReview($id): void
    {
        $this->doWithReview($id, function(Review $review){
            $review->activate();
        });
    }

    public function draftReview($id): void
    {
        $this->doWithReview($id, function(Review $review){
            $review->draft();
        });
    }

    public function removeReview($id): void
    {
        $reviews = $this->reviews;

        foreach($reviews as $i => $review){
            if($review->isIdEqualTo($id)){
                unset($reviews[$i]);
                $this->updateReviews($reviews);

                return;
            }
        }

        throw new \DomainException('Review not found!');
    }

    private function updateReviews(array $reviews): void
    {
        $amount = 0;
        $total = 0;

        foreach($reviews as $review){
            if($review->isActive()){
                $amount++;

                $total += $review->getRating();
            }
        }

        $this->reviews = $reviews;
        $this->rating = $amount ? $total/$amount : null;
    }
    //-

    public function revokeCategories(): void
    {
        $this->categoryAssignments = [];
    }

    public function addPhoto(UploadedFile $file): void
    {
        $photos = $this->photos;
        $photos[] = Photo::create($file);
        $this->updatePhotos($photos);
    }

    public function removePhoto($id): void
    {
        $photos = $this->photos;
        foreach($photos as $i => $photo){
            if($photo->isEqualTo($id)){
                unset($photos[$i]);

                $this->photos = $photos;
                return;
            }
        }

        throw new \DomainException('Photo not found!');
    }

    public function removePhotos(): void
    {
        $this->updatePhotos([]);
    }

    public function movePhotoUp($id): void
    {
        $photos = $this->photos;
        foreach($photos as $i => $photo){
            if($photo->isEqualTo($id)){
                if($prev = $photos[$i - 1] ?? null){
                    $photos[$i - 1] = $photo;
                    $photos[$i] = $prev;
                    $this->updatePhotos($photos);
                }

                return;
            }
        }

        throw new \DomainException('Photo not found!');
    }

    public function movePhotoDown($id): void
    {
        $photos = $this->photos;
        foreach($photos as $i => $photo){
            if($photo->isEqualTo($id)){
                if($next = $photos[$i + 1] ?? null) {
                    $photos[$i] = $next;
                    $photos[$i + 1] = $photo;
                    $this->updatePhotos($photos);
                }

                return;
            }
        }

        throw new \DomainException('Photo not found!');
    }

    //--Tags
    public function assignTag($id): void
    {
        $assignments = $this->tagAssignments;
        foreach($assignments as $assignment){
            if($assignment->isForTag($id)){
                return;
            }
        }

        $assignments[] = TagAssignment::create($id);
        $this->tagAssignments = $assignments;
    }

    public function revokeTag($id): void
    {
        $assignments = $this->tagAssignments;
        foreach($assignments as $i => $assignment){
            if($assignment->isForTag($id)){
                unset($assignments[$i]);
                return;
            }
        }

        throw new \DomainException('Tag not found!');
    }

    public function revokeTags(): void
    {
        $this->tagAssignments = [];
    }
    //--

    public function behaviors()
    {
        return [
            MetaBehavior::class,
            [
                'class' => SaveRelationsBehavior::class,
                'relations' => ['categoryAssignments', 'tagAssignments', 'relatedAssignments', 'values', 'photos'],
            ],
        ];
    }

    public function afterSave($insert, $changedAttributes)
    {
        //Просто взять привязанные товары по mainPhoto
        $related = $this->getRelatedRecords();
        parent::afterSave($insert, $changedAttributes);

        //Взять первую фотографию и сюда прописать её id
        if(array_key_exists('mainPhoto', $related)){
            $this->updateAttributes(['main_photo_id' => $related['mainPhoto'] ? $related['mainPhoto']->id : null]);
        }
    }

    public static function find()
    {
        return new ProductQuery(static::class);
    }
}