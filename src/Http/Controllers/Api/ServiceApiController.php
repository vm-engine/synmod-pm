<?php

declare(strict_types=1);

namespace VmEngine\Pm\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use VmEngine\Pm\Enums\Services;
use VmEngine\Pm\Http\Traits\ApiResponse;

class ServiceApiController extends Controller
{
    use ApiResponse;

    /**
     * Static list of available services (enum-backed).
     */
    public function list(): JsonResponse
    {
        $data = collect(Services::cases())
            ->map(fn (Services $service) => [
                'id' => $service->value,
                'label' => $service->label(),
            ])
            ->values()
            ->all();

        return $this->apiSuccess($data, self::SERVICE_SERVICE_LIST);
    }
}
