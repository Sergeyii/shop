<?php

namespace shop\services\sms;

use yii\base\InvalidConfigException;

class SmsRu implements SmsSender
{
    private $apiId;
    private $baseUrl;

    public function __construct($apiId, $baseUrl)
    {
        if(empty($apiId)){
            throw new InvalidConfigException('Sms apiId must be set.');
        }
        if(empty($baseUrl)){
            throw new InvalidConfigException('Sms baseUrl must be set.');
        }

        $this->apiId = $apiId;
        $this->baseUrl = $baseUrl;
    }

    public function send($number, $text): void
    {
        $ch = curl_init($this->baseUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array(
            "api_id" => $this->apiId,
            "to" => $number,
            "msg" => $text,
            "json" => 1 // Для получения более развернутого ответа от сервера
        )));
        $body = curl_exec($ch);
        curl_close($ch);

        $json = json_decode($body);

        if(!$json){
            throw new \RuntimeException('Не удалось послать смс.');
        }

        if($json->status != "OK"){
            throw new \RuntimeException('Ошибка обработки посылки смс.');
        }
    }
}