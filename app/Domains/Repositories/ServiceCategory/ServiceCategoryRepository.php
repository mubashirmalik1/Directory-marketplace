<?php

declare(strict_types=1);

namespace App\Domains\Repositories\ServiceCategory;

use App\Models\ServiceCategory;
use Illuminate\Pagination\LengthAwarePaginator;

class ServiceCategoryRepository
{
    public function __construct(public ServiceCategory $model)
    {
    }

    public function list(): LengthAwarePaginator
    {
        return $this->model->with(['parent', 'children'])->paginate();
    }

    public function findByUuid(string $uuid): ?ServiceCategory
    {
        return $this->model->with(['parent', 'children'])->where('uuid', $uuid)->first();
    }

    public function create(array $data): ServiceCategory
    {
        return $this->model->create($data);
    }

    public function update(ServiceCategory $category, array $data): bool
    {
        return $category->update($data);
    }

    public function delete(ServiceCategory $category): bool
    {
        return (bool) $category->delete();
    }
} 