<?php

declare(strict_types=1);

namespace VmEngine\Pm\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use VmEngine\Pm\Models\Concerns\ResolvesUserModel;
use VmEngine\SynAuth\Models\User;

/**
 * @property int $id
 * @property int|null $user_id
 * @property string $method
 * @property string $url
 * @property string|null $route
 * @property array<array-key, mixed> $headers
 * @property array<array-key, mixed>|null $input
 * @property string $ip_address
 * @property string|null $user_agent
 * @property int $status_code
 * @property array<array-key, mixed>|null $response_body
 * @property int $duration_ms
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class ApiLog extends Model
{
    use ResolvesUserModel;

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

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(static::userModel());
    }
}
