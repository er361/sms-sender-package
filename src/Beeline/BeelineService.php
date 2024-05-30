<?php

namespace Sf7kmmr\SmsService\Beeline;

use App\Exceptions\SmsNotSendExeption;
use App\Exceptions\SmsServiceExecption;
use Illuminate\Support\Facades\Log;
use Sf7kmmr\SmsService\SmsInterface;
use SimpleXMLElement;


class BeelineService implements SmsInterface
{
    private QTSMS $api;

    public function __construct(string $login, string $password, string $host)
    {
        $this->api = new QTSMS($login, $password, $host);
    }

    /**
     * Send sms and return sms id
     * @param string $phone
     * @param string $message
     * @param string|null $sender
     * @return int|false
     * @throws SmsServiceExecption
     * @throws SmsNotSendExeption
     */
    public function sendSms(string $phone, string $message, ?string $sender = ''): int|false
    {

        $res = $this->api->post_message($message, $phone, sender: $sender, period: 600);
        if (!$res) {
            throw new SmsNotSendExeption('Empty response from API');
        }

        try {
            $xml = new SimpleXMLElement($res);

            // Проверяем наличие элемента <errors>
            $this->checkForErrors($xml);

            return (int)$xml->result?->sms['id'];
        } catch (\Throwable $e) {
            Log::error('SmsServiceExecption: response - ' . $res);
            throw new SmsServiceExecption($e->getMessage());
        }

    }


    /**
     * @throws SmsServiceExecption
     */
    public function getSmsStatus(int $smsId, bool $fullInfo = false): array|string
    {
        $res = $this->api->status_sms_id($smsId);
        if (!$res) {
            throw new SmsServiceExecption('Empty response from API');
        }

        $xml = new SimpleXMLElement($res);
        $this->checkForErrors($xml);
        $messageData = $xml->MESSAGES->MESSAGE[0];

        if ($fullInfo) {
            // Возвращаем полный массив информации о сообщении
            $messageArray = [
                'SMS_ID' => (string)$messageData['SMS_ID'],
                'SMS_GROUP_ID' => (string)$messageData['SMS_GROUP_ID'],
                'SMSTYPE' => (string)$messageData['SMSTYPE'],
                'CREATED' => (string)$messageData->CREATED,
                'AUL_USERNAME' => (string)$messageData->AUL_USERNAME,
                'AUL_CLIENT_ADR' => (string)$messageData->AUL_CLIENT_ADR,
                'SMS_SENDER' => (string)$messageData->SMS_SENDER,
                'SMS_TARGET' => (string)$messageData->SMS_TARGET,
                'SMS_TEXT' => (string)$messageData->SMS_TEXT,
                'SMSSTC_CODE' => (string)$messageData->SMSSTC_CODE,
                'SMS_STATUS' => (string)$messageData->SMS_STATUS,
                'SMS_CLOSED' => (int)$messageData->SMS_CLOSED,
                'SMS_SENT' => (int)$messageData->SMS_SENT
            ];
            return $messageArray;
        } else {
            // Возвращаем только статус сообщения
            return (string)$messageData->SMS_STATUS;
        }
    }

    /**
     * @param SimpleXMLElement $xml
     * @return void
     * @throws SmsServiceExecption
     */
    public function checkForErrors(SimpleXMLElement $xml): void
    {
        if ($xml->errors->error) {
            $errorCode = (string)$xml->errors->error['code'];
            $errorMessage = (string)$xml->errors->error;
            throw new SmsServiceExecption("Error sending SMS: $errorCode - $errorMessage");
        }
    }

}
