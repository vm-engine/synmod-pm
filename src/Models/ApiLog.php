<?php

declare(strict_types=1);

namespace VmEngine\Pm\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApiLog extends Model
{
    protected $fillable = [
        'user_id',
        'method',
        'url',
        'route',
        'headers',
        'input',
        'ip_address',
        'user_agent',
        'status_code',
        'response_body',
        'duration_ms',
    ];

    protected function casts(): array
    {
        return [
            'headers' => 'array',
            'input' => 'array',
            'response_body' => 'array',
            'created_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(config('auth.providers.users.model'));
    }
}
