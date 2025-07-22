<?php

declare(strict_types=1);

namespace App\Domains\Services\ServiceCategory;

use App\Domains\Repositories\ServiceCategory\ServiceCategoryRepository;
use App\Models\ServiceCategory;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;

class ServiceCategoryService
{
    public function __construct(public ServiceCategoryRepository $repository)
    {
    }

    public function listCategories(): LengthAwarePaginator
    {
        return $this->repository->list();
    }

    public function createCategory(array $data): ServiceCategory
    {
        $data['slug'] = Str::slug($data['name']);

        if (! empty($data['parent_uuid'])) {
            $parent = $this->repository->findByUuid($data['parent_uuid']);
            if ($parent) {
                $data['parent_id'] = $parent->id;
                $data['depth'] = $parent->depth + 1;
            }
        } else {
            $data['parent_id'] = null;
            $data['depth'] = 0;
        }
        unset($data['parent_uuid']);

        return $this->repository->create($data);
    }

    public function getCategoryByUuid(string $uuid): ?ServiceCategory
    {
        return $this->repository->findByUuid($uuid);
    }

    public function updateCategory(ServiceCategory $category, array $data): bool
    {
        if (isset($data['name'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        if (array_key_exists('parent_uuid', $data)) {
            if ($data['parent_uuid'] !== null) {
                $parent = $this->repository->findByUuid($data['parent_uuid']);
                if ($parent) {
                    $data['parent_id'] = $parent->id;
                    $data['depth'] = $parent->depth + 1;
                }
            } else {
                $data['parent_id'] = null;
                $data['depth'] = 0;
            }
            unset($data['parent_uuid']);
        }

        return $this->repository->update($category, $data);
    }

    public function deleteCategory(ServiceCategory $category): bool
    {
        return $this->repository->delete($category);
    }
} 