<?php

declare(strict_types=1);

namespace App\Domains\Services\Company;

use App\Domains\Repositories\Company\CompanyRepository;
use App\Models\Company;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CompanyService
{
    public function __construct(public CompanyRepository $companyRepository)
    {
    }

    public function createCompany(array $data): Company
    {
        $data['owner_user_id'] = Auth::id();
        $data['uuid'] = Str::uuid()->toString();
        $data['slug'] = $this->generateUniqueSlug($data['name']);

        return $this->companyRepository->create($data);
    }

    public function updateCompany(string $uuid, array $data): ?Company
    {
        $company = $this->companyRepository->findByUuidAndUser($uuid, Auth::id());

        if (!$company) {
            return null;
        }

        if (isset($data['name']) && $data['name'] !== $company->name) {
            $data['slug'] = $this->generateUniqueSlug($data['name'], $uuid);
        }

        $this->companyRepository->updateByUuid($uuid, $data);

        return $this->companyRepository->findByUuid($uuid);
    }

    public function findCompanyByUuid(string $uuid): ?Company
    {
        return $this->companyRepository->findByUuid($uuid);
    }

    public function getUserCompanies(int $userId): LengthAwarePaginator
    {
        return $this->companyRepository->listForUser($userId);
    }

    private function generateUniqueSlug(string $name, ?string $ignoreUuid = null): string
    {
        $slug = Str::slug($name);
        $originalSlug = $slug;
        $counter = 1;

        $query = Company::where('slug', $slug);

        if ($ignoreUuid) {
            $query->where('uuid', '!=', $ignoreUuid);
        }

        while ($query->exists()) {
            $slug = $originalSlug . '-' . $counter++;
            $query = Company::where('slug', $slug);
            if ($ignoreUuid) {
                $query->where('uuid', '!=', $ignoreUuid);
            }
        }

        return $slug;
    }
} 