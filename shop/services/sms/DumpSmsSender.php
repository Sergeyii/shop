<?php

namespace shop\services\sms;

class DumpSmsSender implements SmsSender
{
    public function send($number, $text): void
    {
        \Yii::info('Message to '.$number.': '.$text);
    }
}