<?php

namespace App\Http\Controllers\API;

use App\Domains\Services\ServiceCategory\ServiceCategoryService;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreServiceCategoryRequest;
use App\Http\Requests\UpdateServiceCategoryRequest;
use App\Http\Resources\ServiceCategoryResource;
use App\Models\ServiceCategory;
use Illuminate\Http\JsonResponse;

class ServiceCategoryController extends Controller
{
    public function __construct(public ServiceCategoryService $serviceCategoryService)
    {
    }

    public function index(): JsonResponse
    {
        $categories = $this->serviceCategoryService->listCategories();

        return response()->success(ServiceCategoryResource::collection($categories));
    }

    public function store(StoreServiceCategoryRequest $request): JsonResponse
    {
        $category = $this->serviceCategoryService->createCategory($request->validated());

        return response()->success(
            ServiceCategoryResource::make($category),
            'Service category created successfully.',
            201
        );
    }

    public function show(ServiceCategory $serviceCategory): JsonResponse
    {
        return response()->success(ServiceCategoryResource::make($serviceCategory));
    }

    public function update(UpdateServiceCategoryRequest $request, ServiceCategory $serviceCategory): JsonResponse
    {
        $this->serviceCategoryService->updateCategory($serviceCategory, $request->validated());

        return response()->success(
            ServiceCategoryResource::make($serviceCategory->refresh()),
            'Service category updated successfully.'
        );
    }

    public function destroy(ServiceCategory $serviceCategory): JsonResponse
    {
        $this->serviceCategoryService->deleteCategory($serviceCategory);

        return response()->success([], 'Service category deleted successfully.');
    }
} 