<?php

declare(strict_types=1);

namespace VmEngine\Pm\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use VmEngine\Pm\Http\Resources\ClientResource;
use VmEngine\Pm\Http\Traits\ApiResponse;
use VmEngine\Pm\Models\Client;
use VmEngine\Pm\Services\ClientService;

class ClientApiController extends Controller
{
    use ApiResponse;

    public function __construct(protected ClientService $clientService) {}

    /**
     * Paginated client list.
     */
    public function list(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'client_category_id' => 'nullable|integer|exists:client_categories,id',
            'status' => 'nullable|integer|in:0,1',
            'active' => 'nullable|boolean',
            'services' => 'nullable|array',
            'services.*' => 'integer',
            'project_category' => 'nullable|array',
            'project_category.*' => 'integer|exists:project_categories,id',
            'sort_by' => 'nullable|string|in:company,status,active,created_at',
            'sort_desc' => 'nullable|boolean',
            'page' => 'nullable|integer|min:1',
            'limit' => 'nullable|integer|min:1|max:100',
            'q' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return $this->apiError(422, self::SERVICE_CLIENT_LIST, self::RESPONSE_VALIDATION_ERROR, 'Validation failed.', $validator->errors()->toArray());
        }

        $filters = $request->only([
            'client_category_id', 'status', 'active', 'services', 'project_category',
            'sort_by', 'sort_desc', 'limit', 'q',
        ]);

        $paginated = $this->clientService->getClientList($filters);

        return response()->json([
            'status' => $this->buildStatusCode(200, self::SERVICE_CLIENT_LIST, self::RESPONSE_SUCCESS),
            'message' => 'Success',
            'data' => ClientResource::collection($paginated->items()),
            'meta' => [
                'current_page' => $paginated->currentPage(),
                'last_page' => $paginated->lastPage(),
                'per_page' => $paginated->perPage(),
                'total' => $paginated->total(),
            ],
        ]);
    }

    /**
     * Client detail.
     */
    public function show(string $id): JsonResponse
    {
        $client = ctype_digit($id)
            ? Client::with('category:id,name')->find((int) $id)
            : null;

        if (! $client) {
            return $this->apiError(404, self::SERVICE_CLIENT_DETAIL, self::RESPONSE_NOT_FOUND, 'Client not found.');
        }

        return $this->apiSuccess(new ClientResource($client), self::SERVICE_CLIENT_DETAIL);
    }
}
