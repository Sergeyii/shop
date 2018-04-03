<?php

namespace shop\services\newsletter;

use \DrewM\MailChimp\MailChimp as DrewMailChimp;

class MailChimp implements Newsletter
{
    const STATUS_SUBSCRIBED = 'subscribed';

    public $listId;

    private $mailChimp;

    public function __construct(DrewMailChimp $mailChimp, string $listId)
    {
        $this->mailChimp = $mailChimp;
        $this->listId = $listId;
    }

    public function subscribe($email): void
    {
        $this->mailChimp->post('lists/'.$this->listId.'/members', [
            'email_address' => $email,
            'status' => self::STATUS_SUBSCRIBED,
        ]);

        if( $error = $this->mailChimp->getLastError() ){
            throw new \RuntimeException($error);
        }
    }

    public function unsubscribe($email): void
    {
        $subscriber_hash = $this->mailChimp->subscriberHash($email);
        $this->mailChimp->delete('lists/'.$this->listId.'/members/'.$subscriber_hash);

        if( $error = $this->mailChimp->getLastError() ){
            throw new \RuntimeException($error);
        }
    }
}