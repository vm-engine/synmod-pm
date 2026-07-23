<?php

declare(strict_types=1);

namespace VmEngine\Pm\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use VmEngine\Pm\Http\Resources\ClientCategoryResource;
use VmEngine\Pm\Http\Traits\ApiResponse;
use VmEngine\Pm\Models\ClientCategory;

class ClientCategoryApiController extends Controller
{
    use ApiResponse;

    /**
     * Paginated client category list.
     */
    public function list(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'q' => 'nullable|string|max:255',
            'page' => 'nullable|integer|min:1',
            'limit' => 'nullable|integer|min:1|max:100',
        ]);

        if ($validator->fails()) {
            return $this->apiError(422, self::SERVICE_CLIENT_CATEGORY_LIST, self::RESPONSE_VALIDATION_ERROR, 'Validation failed.', $validator->errors()->toArray());
        }

        $limit = min((int) $request->input('limit', 20), 100);
        $q = trim((string) $request->input('q', ''));

        $query = ClientCategory::query();

        if ($q !== '') {
            $query->where('name', 'like', "%{$q}%");
        }

        $paginated = $query->orderBy('name')->paginate($limit);

        return response()->json([
            'status' => $this->buildStatusCode(200, self::SERVICE_CLIENT_CATEGORY_LIST, self::RESPONSE_SUCCESS),
            'message' => 'Success',
            'data' => ClientCategoryResource::collection($paginated->items()),
            'meta' => [
                'current_page' => $paginated->currentPage(),
                'last_page' => $paginated->lastPage(),
                'per_page' => $paginated->perPage(),
                'total' => $paginated->total(),
            ],
        ]);
    }
}
