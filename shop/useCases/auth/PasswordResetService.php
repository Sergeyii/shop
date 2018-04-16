<?php

namespace shop\useCases\auth;

use shop\entities\User\User;
use shop\repositories\NotFoundException;
use shop\repositories\UserRepository;
use shop\forms\auth\PasswordResetRequestForm;
use shop\forms\auth\ResetPasswordForm;
use yii\mail\MailerInterface;
use Yii;

class PasswordResetService
{
    private $mailer;
    private $users;

    public function __construct(MailerInterface $mailer, UserRepository $users)
    {
        $this->mailer = $mailer;
        $this->users = $users;
    }

    public function checkUserIsActive(User $user):void
    {
        if ( !$user->isActive() ) {
            throw new NotFoundException('User is not active.');
        }
    }

    /**
     * Sends an email with a link, for resetting the password.
     *
     * @return bool whether the email was send
     */
    public function request(PasswordResetRequestForm $form): void
    {
        $user = $this->users->getByEmail($form->email);
        $this->checkUserIsActive($user);

       $user->requestPasswordReset();
        $this->users->save($user);

        $sent = $this
            ->mailer
            ->compose(
                ['html' => 'auth/reset/passwordResetToken-html', 'text' => 'auth/reset/passwordResetToken-text'],
                ['user' => $user]
            )
            ->setTo($form->email)
            ->setSubject('Password reset for ' . Yii::$app->name)
            ->send();

        $this->checkEmailSended($sent);
    }

    public function checkEmailSended($sent):void
    {
        if(!$sent){
            throw new \RuntimeException('Sending mail error.');
        }
    }

    public function validateToken($token): void
    {
        if (empty($token) || !is_string($token)) {
            throw new \DomainException('Password reset token cannot be blank.');
        }
        if (!$this->users->existsByPasswordResetToken($token)) {
            throw new \DomainException('Wrong password reset token.');
        }
    }

    /**
     * Resets password.
     *
     * @return bool if password was reset.
     */
    public function reset(string $token, ResetPasswordForm $form): bool
    {
        $user = $this->users->getByPasswordResetToken($token);
        $user->resetPassword($form->password);
        $this->users->save($user);
    }
}