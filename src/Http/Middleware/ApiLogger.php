<?php

declare(strict_types=1);

namespace VmEngine\Pm\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use VmEngine\Pm\Models\ApiLog;

class ApiLogger
{
    /** Maximum response body size to store (64KB). */
    protected const MAX_RESPONSE_BODY_SIZE = 65536;

    public function handle(Request $request, Closure $next): Response
    {
        $request->attributes->set('api_log_start_time', microtime(true));

        return $next($request);
    }

    public function terminate(Request $request, Response $response): void
    {
        $startTime = $request->attributes->get('api_log_start_time');
        $durationMs = $startTime ? (int) round((microtime(true) - $startTime) * 1000) : 0;

        $responseBody = $response->getContent();
        // Streamed/binary responses have no buffered content (getContent() returns false).
        if ($responseBody === false || strlen($responseBody) > self::MAX_RESPONSE_BODY_SIZE) {
            $responseBody = null;
        }

        ApiLog::create([
            'user_id' => $request->user()?->id,
            'method' => $request->method(),
            'url' => $request->fullUrl(),
            'route' => $request->route()?->getName(),
            'headers' => $this->sanitizeHeaders($request->headers->all()),
            'input' => $request->all() ?: null,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'status_code' => $response->getStatusCode(),
            'response_body' => $responseBody ? json_decode($responseBody, true) : null,
            'duration_ms' => $durationMs,
        ]);
    }

    /**
     * Sanitize headers by masking the Authorization token value.
     *
     * @param  array<string, list<string|null>>  $headers
     * @return array<string, list<string|null>>
     */
    protected function sanitizeHeaders(array $headers): array
    {
        if (isset($headers['authorization'])) {
            $headers['authorization'] = ['Bearer ***'];
        }

        // Remove noisy headers
        unset($headers['cookie']);

        return $headers;
    }
}
