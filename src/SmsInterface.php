<?php

namespace Sf7kmmr\SmsService;



use App\Exceptions\SmsNotSendExeption;
use App\Exceptions\SmsServiceExecption;

interface SmsInterface
{
    /**
     * Send sms and return sms id
     * @param string $phone
     * @param string $message
     * @param string $sender
     * @return int|false
     * @throws SmsServiceExecption
     * @throws SmsNotSendExeption
     */
    public function sendSms(string $phone, string $message, string $sender = ''): false|int;
}
