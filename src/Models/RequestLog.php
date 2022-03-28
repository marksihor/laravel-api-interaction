<?php

namespace MarksIhor\ApiInteraction\Models;

use Illuminate\Database\Eloquent\Model;

class RequestLog extends Model
{
    protected $table = 'requests_logs';

    protected $fillable = ['endpoint', 'from_endpoint', 'method', 'code', 'request_data', 'request_headers', 'response_data'];

    protected $casts = [
        'request_data' => 'array',
        'request_headers' => 'array',
        'response_data' => 'array',
    ];
}
