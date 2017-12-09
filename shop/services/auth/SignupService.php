<?php

namespace shop\services\auth;

use shop\entities\User\User;
use shop\repositories\UserRepository;
use shop\forms\auth\SignupForm;
use yii\mail\MailerInterface;

class SignupService
{
    private $mailer;
    private $users;

    public function __construct(Mailerinterface $mailer, UserRepository $users)
    {
         $this->mailer = $mailer;
         $this->users = $users;
    }

    public function signup(SignupForm $form):void
    {
        $user = User::requestSignup($form-username, $form->email, $form->password);
        $this->save($user);

        $send = $this->mailer->compose(
            ['html' => 'auth/signup/emailConfirmToken-html', 'text' => 'auth/signup/emailConfirmToken-text'],
            ['user' => $user]
        )
        ->setTo($form->email)
        ->setSubject('Signup confirm for '.\Yii::$app->name)
        ->send();

        if( !$send ){
            throw new \RuntimeException('Email sending error.');
        }
    }

    public function confirm($token):void
    {
        if( empty($token) ){
            throw new \DomainException('Empty confirm token.');
        }

        $user = $this->users->getByEmailConfirmToken($token);
        $user->confirmSignup();

        $this->save($user);
    }

    public function save(User $user):void
    {
        if(!$user->save()){
            throw new \RuntimException('Saving error.');
        }
    }
}