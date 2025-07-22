<?php

namespace App\Http\Controllers\API;

use App\Domains\Services\Company\CompanyService;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCompanyRequest;
use App\Http\Requests\UpdateCompanyRequest;
use App\Http\Resources\CompanyResource;
use App\Models\Company;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class CompanyController extends Controller
{
    public function __construct(public CompanyService $companyService)
    {
        $this->authorizeResource(Company::class, 'company');
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

    public function show(Company $company): JsonResponse
    {
        return response()->success(CompanyResource::make($company));
    }

    public function update(UpdateCompanyRequest $request, Company $company): JsonResponse
    {
        $updatedCompany = $this->companyService->updateCompany($company, $request->validated());

        return response()->success(CompanyResource::make($updatedCompany), 'Company updated successfully.');
    }
}
