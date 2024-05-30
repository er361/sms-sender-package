<?php

namespace Sf7kmmr\SmsService\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SendSmsLog extends Model
{
    use HasFactory;
    protected $fillable = [
        'phone',
        'message',
        'status',
        'sms_id',
        'full_info'
    ];
}
