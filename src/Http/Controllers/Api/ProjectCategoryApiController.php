<?php

declare(strict_types=1);

namespace VmEngine\Pm\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use VmEngine\Pm\Http\Resources\ProjectCategoryResource;
use VmEngine\Pm\Http\Traits\ApiResponse;
use VmEngine\Pm\Models\ProjectCategory;

class ProjectCategoryApiController extends Controller
{
    use ApiResponse;

    /**
     * Unpaginated project category list.
     */
    public function list(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'q' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return $this->apiError(422, self::SERVICE_PROJECT_CATEGORY_LIST, self::RESPONSE_VALIDATION_ERROR, 'Validation failed.', $validator->errors()->toArray());
        }

        $q = trim((string) $request->input('q', ''));

        $categories = ProjectCategory::query()
            ->when($q !== '', fn ($query) => $query->where(fn ($sub) => $sub->where('name', 'like', "%{$q}%")->orWhere('code', 'like', "%{$q}%")))
            ->orderBy('name')
            ->get();

        return $this->apiSuccess(ProjectCategoryResource::collection($categories), self::SERVICE_PROJECT_CATEGORY_LIST);
    }
}
