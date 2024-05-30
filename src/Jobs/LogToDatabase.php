<?php

namespace Sf7kmmr\SmsService\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Sf7kmmr\SmsService\Models\SendSmsLog;

class LogToDatabase implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    /**
     * Create a new job instance.
     * @param string $phone
     * @param string $message
     * @param string $status
     * @param string $sms_id
     * @param string $full_info
     */
    public function __construct(
        private readonly string $phone,
        private readonly string $message,
        private readonly string $status,
        private readonly string $sms_id,
        private readonly string $full_info
    )
    {
        //
    }

    /**
     * Execute the job.
     * @throws \Throwable
     */
    public function handle(): void
    {
        //

        (new SendSmsLog([
            'phone' => $this->phone,
            'message' => $this->message,
            'status' => $this->status,
            'sms_id' => $this->sms_id,
            'full_info' => $this->full_info
        ]))->saveOrFail();

    }
}
