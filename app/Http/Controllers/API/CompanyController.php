<?php

namespace App\Http\Controllers\API;

use App\Domains\Services\Company\CompanyService;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCompanyRequest;
use App\Http\Requests\UpdateCompanyRequest;
use App\Http\Resources\CompanyResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class CompanyController extends Controller
{
    public function __construct(public CompanyService $companyService)
    {
    }

    public function index(): JsonResponse
    {
        $companies = $this->companyService->getUserCompanies(Auth::id());
        return response()->success(CompanyResource::collection($companies));
    }

    public function store(StoreCompanyRequest $request): JsonResponse
    {
        $company = $this->companyService->createCompany($request->validated() + ['owner_user_id' => Auth::id()]);
        return response()->success(CompanyResource::make($company), 'Company created successfully.', 201);
    }

    public function show(string $uuid): JsonResponse
    {
        $company = $this->companyService->findCompanyByUuid($uuid);

        if (!$company) {
            return response()->error([], 'Company not found.', 404);
        }

        return response()->success(CompanyResource::make($company));
    }

    public function update(UpdateCompanyRequest $request, string $uuid): JsonResponse
    {
        $company = $this->companyService->updateCompany($uuid, $request->validated());

        if (!$company) {
            return response()->error([], 'Company not found or you do not have permission to update it.', 404);
        }

        return response()->success(CompanyResource::make($company), 'Company updated successfully.');
    }
}
