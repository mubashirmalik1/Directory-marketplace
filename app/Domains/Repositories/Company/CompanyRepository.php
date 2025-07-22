<?php

declare(strict_types=1);

namespace App\Domains\Repositories\Company;

use App\Models\Company;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

class CompanyRepository
{
    public function __construct(public Company $model)
    {
    }

    public function create(array $data): Company
    {
        return $this->model->create($data);
    }

    public function findByUuid(string $uuid): ?Company
    {
        return $this->model->where('uuid', $uuid)->first();
    }

    public function findByUuidAndUser(string $uuid, int $userId): ?Company
    {
        return $this->model->where('uuid', $uuid)->where('user_id', $userId)->first();
    }

    public function updateByUuid(string $uuid, array $data): bool
    {
        $company = $this->findByUuid($uuid);
        if (!$company) {
            return false;
        }
        return $company->update($data);
    }

    public function listForUser(int $userId, int $perPage = 15): LengthAwarePaginator
    {
        return $this->model->where('user_id', $userId)->latest()->paginate($perPage);
    }
} 