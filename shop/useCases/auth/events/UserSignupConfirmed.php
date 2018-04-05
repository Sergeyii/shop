<?php

namespace shop\useCases\auth\events;

use shop\entities\User\User;

class UserSignupConfirmed
{
    public $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }
}