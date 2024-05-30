<?php

namespace Sf7kmmr\SmsService\Jobs;


use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Sf7kmmr\SmsService\Actions\SmsSendAction;

class SendSmsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    /**
     * Create a new job instance.
     */
    public function __construct(
        private string $phone,
        private string $message
    )
    {        //
    }

    /**
     * Execute the job.
     */
    public function handle(SmsSendAction $smsSendAction): void
    {
        $res = $smsSendAction->run($this->phone, $this->message);
        //асинхронное логирование чтобы не тормозить отправку смс
        LogToDatabase::dispatch(
            $this->phone,
            $this->message,
            $res['SMS_STATUS'],
            $res['SMS_ID'],
            json_encode($res, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
        );
    }
}
