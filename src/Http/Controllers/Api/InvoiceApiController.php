<?php

declare(strict_types=1);

namespace VmEngine\Pm\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use VmEngine\Pm\Http\Resources\InvoiceResource;
use VmEngine\Pm\Http\Traits\ApiResponse;
use VmEngine\Pm\Services\InvoiceService;

class InvoiceApiController extends Controller
{
    use ApiResponse;

    public function __construct(protected InvoiceService $invoiceService) {}

    /**
     * Invoice statistics.
     */
    public function statistics(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'year' => 'nullable|integer|min:2000|max:2099',
        ]);

        if ($validator->fails()) {
            return $this->apiError(422, self::SERVICE_STATISTICS, self::RESPONSE_INVALID_YEAR, 'Invalid year parameter.', $validator->errors()->toArray());
        }

        $year = $request->integer('year') ?: null;
        $data = $this->invoiceService->apiStatistics($year);

        return $this->apiSuccess($data, self::SERVICE_STATISTICS);
    }

    /**
     * Report by creator.
     */
    public function byCreator(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'year' => 'nullable|integer|min:2000|max:2099',
            'id' => 'nullable|integer|exists:users,id',
        ]);

        if ($validator->fails()) {
            return $this->apiError(422, self::SERVICE_BY_CREATOR, self::RESPONSE_VALIDATION_ERROR, 'Validation failed.', $validator->errors()->toArray());
        }

        $year = $request->integer('year') ?: null;
        $id = $request->integer('id') ?: null;
        $data = $this->invoiceService->getByCreator($year, $id);

        return $this->apiSuccess($data, self::SERVICE_BY_CREATOR);
    }

    /**
     * Report by project category.
     */
    public function byProjectCategory(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'year' => 'nullable|integer|min:2000|max:2099',
        ]);

        if ($validator->fails()) {
            return $this->apiError(422, self::SERVICE_BY_PROJECT_CATEGORY, self::RESPONSE_INVALID_YEAR, 'Invalid year parameter.', $validator->errors()->toArray());
        }

        $year = $request->integer('year') ?: null;
        $data = $this->invoiceService->getByProject($year);

        return $this->apiSuccess($data, self::SERVICE_BY_PROJECT_CATEGORY);
    }

    /**
     * Report by month.
     */
    public function byMonth(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'year' => 'nullable|integer|min:2000|max:2099',
        ]);

        if ($validator->fails()) {
            return $this->apiError(422, self::SERVICE_BY_MONTH, self::RESPONSE_INVALID_YEAR, 'Invalid year parameter.', $validator->errors()->toArray());
        }

        $year = $request->integer('year') ?: null;
        $data = $this->invoiceService->getByMonth($year);

        return $this->apiSuccess($data, self::SERVICE_BY_MONTH);
    }

    /**
     * Paginated invoice list.
     */
    public function list(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'year' => 'nullable|integer|min:2000|max:2099',
            'month' => 'nullable|integer|min:1|max:12',
            'creator' => 'nullable|integer|exists:users,id',
            'project_category' => 'nullable|integer|exists:project_categories,id',
            'is_paid' => 'nullable|boolean',
            'sort_by' => 'nullable|string|in:issue_date,due_date,total_amount,invoice_number,created_at,is_paid',
            'sort_desc' => 'nullable|boolean',
            'page' => 'nullable|integer|min:1',
            'limit' => 'nullable|integer|min:1|max:100',
        ]);

        if ($validator->fails()) {
            return $this->apiError(422, self::SERVICE_INVOICE_LIST, self::RESPONSE_VALIDATION_ERROR, 'Validation failed.', $validator->errors()->toArray());
        }

        $filters = $request->only([
            'year', 'month', 'creator', 'project_category',
            'is_paid', 'sort_by', 'sort_desc', 'limit',
        ]);

        $paginated = $this->invoiceService->getInvoiceList($filters);

        return response()->json([
            'status' => $this->buildStatusCode(200, self::SERVICE_INVOICE_LIST, self::RESPONSE_SUCCESS),
            'message' => 'Success',
            'data' => InvoiceResource::collection($paginated->items()),
            'meta' => [
                'current_page' => $paginated->currentPage(),
                'last_page' => $paginated->lastPage(),
                'per_page' => $paginated->perPage(),
                'total' => $paginated->total(),
            ],
        ]);
    }
}
