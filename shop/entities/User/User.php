<?php
namespace shop\entities\User;

use lhs\Yii2SaveRelationsBehavior\SaveRelationsBehavior;
use shop\entities\AggregateRoot;
use shop\entities\EventTrait;
use shop\entities\User\events\UserSignUpConfirmed;
use shop\entities\User\events\UserSignUpRequested;
use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $auth_key
 * @property string $email_confirm_token
 * @property string $phone
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 * @property array|mixed networks
 * @property WishlistItem[] wishlistItems
 */
class User extends ActiveRecord implements AggregateRoot
{
    use EventTrait;

    const STATUS_WAIT = 0;
    const STATUS_ACTIVE = 10;

    public static function create(string $username, string $email, string $phone, string $password): self
    {
        $user = new static();
        $user->username = $username;
        $user->email = $email;
        $user->phone = $phone;
        $user->setPassword(!empty($password) ? $password : Yii::$app->security->generateRandomString());
        $user->created_at = time();
        $user->status = static::STATUS_ACTIVE;
        $user->generateAuthKey();
        $user->recordEvent(new UserSignUpRequested($user));
        return $user;
    }

    public function edit(string $username, string $email, string $phone): void
    {
        $this->username = $username;
        $this->email = $email;
        $this->phone = $phone;
        $this->updated_at = time();
    }

    public function editProfile(string $email, string $phone): void
    {
        $this->email = $email;
        $this->phone = $phone;
        $this->updated_at = time();
    }

    public static function requestSignup(string $username, string $email, string $phone, string $password): self
    {
        $user = new static();
        $user->username = $username;
        $user->email = $email;
        $user->phone = $phone;
        $user->setPassword($password);
        $user->created_at = time();
        $user->status = static::STATUS_WAIT;
        $user->generateConfirmToken();
        $user->generateAuthKey();

        return $user;
    }

    public function confirmSignup(): void
    {
        if (!$this->isWait()) {
            throw new \DomainException('User is already active');
        }

        $this->status = static::STATUS_ACTIVE;
        $this->removeEmailConfirmToken();
        $this->recordEvent(new UserSignUpConfirmed($this));
    }

    public static function signupByNetwork($network, $identity): self
    {
        //Создать нового пользователя
        $user = new User();
        $user->created_at = time();
        $user->status = static::STATUS_ACTIVE;
        $user->generateAuthKey();

        //Привязать к нему переданную соц. сеть
        //Здесь поведение save-relation-behavior само выполнит работу
        //TODO::здесь не сохраняет два поля в таблице user_networks
        $user->networks = [Network::create($network, $identity)];

        return $user;
    }

    public function attachNetwork($network, $identity): void
    {
        $networks = $this->networks;
        foreach ($networks as $network) {
            if ($network->isFor($network, $identity)) {
                throw new \DomainException('Network is already attached!');
            }
        }

        $networks[] = Network::create($network, $identity);
        $this->networks = $networks;
    }

    public function isWait()
    {
        return $this->status == static::STATUS_WAIT;
    }

    public function removeEmailConfirmToken()
    {
        $this->email_confirm_token = null;
    }

    public function generateConfirmToken()
    {
        $this->email_confirm_token = Yii::$app->security->generateRandomString();
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%users}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
            'saveRelations' => [
                'class' => SaveRelationsBehavior::className(),
                'relations' => ['networks', 'wishlistItems'],
            ],
        ];
    }

    public function transactions()
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL
        ];
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return bool
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int)substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password): bool
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    public function resetPassword($password)
    {
        if (empty($this->password_reset_token)) {
            throw new \DomainException('Password resetting is not requested.');
        }

        $this->setPassword($password);
        $this->password_reset_token = null;

        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    /*public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }*/

    /**
     * Removes password reset token
     */
    /*public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }*/

    public function isActive(): bool
    {
        return $this->status == static::STATUS_ACTIVE;
    }

    public function requestPasswordReset(): void
    {
        if (!empty($this->password_reset_token) && static::isPasswordResetTokenValid($this->password_reset_token)) {
            throw new \DomainException('Password resetting is already requested.');
        }

        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    public function getNetworks(): ActiveQuery
    {
        return $this->hasMany(Network::className(), ['user_id' => 'id']);
    }

    public function getWishlistItems(): ActiveQuery
    {
        return $this->hasMany(WishlistItem::class, ['user_id' => 'id']);
    }


    public function addToWishlist($productId): void
    {
        $items = $this->wishlistItems;

        foreach($items as $item){
            if($item->isForProduct($productId)){
                throw new \DomainException('Item is already added!');
            }
        }

        $items[] = WishlistItem::create($this->id, $productId);
        $this->wishlistItems = $items;
    }

    public function removeFromWishlist($productId): void
    {
        $items = $this->wishlistItems;

        foreach($items as $i => $item){
            if($item->isForProduct($productId)) {
                unset($items[$i]);
                $this->wishlistItems = $items;

                return;
            }
        }

        throw new \DomainException('Item not exists!');
    }
}