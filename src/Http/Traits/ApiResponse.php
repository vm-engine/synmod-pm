<?php

declare(strict_types=1);

namespace VmEngine\Pm\Http\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponse
{
    /** Service codes per endpoint */
    protected const SERVICE_STATISTICS = '01';

    protected const SERVICE_BY_CREATOR = '02';

    protected const SERVICE_BY_PROJECT_CATEGORY = '03';

    protected const SERVICE_BY_MONTH = '04';

    protected const SERVICE_INVOICE_LIST = '05';

    protected const SERVICE_CLIENT_LIST = '06';

    protected const SERVICE_CLIENT_DETAIL = '07';

    protected const SERVICE_CLIENT_CATEGORY_LIST = '08';

    protected const SERVICE_SERVICE_LIST = '09';

    protected const SERVICE_PROJECT_CATEGORY_LIST = '10';

    /** Response codes */
    protected const RESPONSE_SUCCESS = '00';

    protected const RESPONSE_INVALID_YEAR = '01';

    protected const RESPONSE_NOT_FOUND = '02';

    protected const RESPONSE_VALIDATION_ERROR = '03';

    /**
     * Build a custom status code string: "<http_code><service_code><response_code>"
     */
    protected function buildStatusCode(int $httpCode, string $serviceCode, string $responseCode): string
    {
        return $httpCode.$serviceCode.$responseCode;
    }

    /**
     * Return a successful API response.
     */
    protected function apiSuccess(mixed $data, string $serviceCode, string $message = 'Success'): JsonResponse
    {
        return response()->json([
            'status' => $this->buildStatusCode(200, $serviceCode, self::RESPONSE_SUCCESS),
            'message' => $message,
            'data' => $data,
        ]);
    }

    /**
     * Return an error API response.
     */
    protected function apiError(int $httpCode, string $serviceCode, string $responseCode, string $message, ?array $errors = null): JsonResponse
    {
        $payload = [
            'status' => $this->buildStatusCode($httpCode, $serviceCode, $responseCode),
            'message' => $message,
        ];

        if ($errors !== null) {
            $payload['errors'] = $errors;
        }

        return response()->json($payload, $httpCode);
    }
}
