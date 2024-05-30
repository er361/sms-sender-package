<?php

namespace Sf7kmmr\SmsService\Actions;

use App\Exceptions\SmsServiceExecption;
use Sf7kmmr\SmsService\SmsInterface;

class SmsSendAction
{
    public function __construct(
        private SmsInterface $sms
    )
    {
    }

    /**
     * @throws SmsServiceExecption
     * @throws SmsNotSendExeption
     */
    public function run(string $phone, string $message): string|array|false
    {
        $smsId = $this->sms->sendSms($phone, $message, 'ctrzaim24ru');
        return $this->sms->getSmsStatus($smsId, fullInfo: true);
    }
}
