<?php

namespace tests\unit\entities\User;

use Codeception\Test\Unit;
use shop\entities\User\User;

class ConfirmSignupTest extends Unit
{
    public function testSuccess(){
        $user = new User([
            'status' => User::STATUS_WAIT,
            'email_confirm_token' => 'token',
        ]);

        $user->confirmSignup();
        $this->assertTrue($user->isActive());
        $this->assertFalse($user->isWait());
        $this->assertEmpty($user->email_confirm_token);
    }

    public function testAlreadyActive(){
        $user = new User([
            'status' => User::STATUS_WAIT,
            'email_confirm_token' => null,
        ]);

        $this->getExpectedException();
        $user->confirmSignup();
    }

}