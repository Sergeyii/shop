<?php

namespace shop\listeners\User;

use shop\entities\User\events\UserSignUpRequested;
use yii\mail\MailerInterface;

class UserSignupRequestedListener
{
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function handle(UserSignUpRequested $event): void
    {
        $send = $this->mailer->compose(
            ['html' => 'auth/signup/confirm-html', 'text' => 'auth/signup/confirm-text'],
            ['user' => $event->user]
        )
        ->setTo($event->user->email)
        ->setSubject('Signup confirm ')
        ->send();

        if( !$send ){
            throw new \RuntimeException('Email sending error.');
        }
    }
}