<?php

namespace App\Repositories;

use App\Interfaces\DBInterface;
use App\Models\App;
use App\Traits\QueryBuilder;
use Illuminate\Database\Eloquent\Collection;

class AppRepository implements DBInterface
{
    use QueryBuilder;

    private $app;

    public function __construct(App $app)
    {
        $this->app = $app;
    }

    public function getAll(array $filter): Collection
    {
        list($userId, $keywords, $order) = $filter;

        $query = $this->app->query();

        $query = $this->queryBuilder($query, $userId, $keywords, $order);

        return $query->get();
    }

    public function getAllCount(int $userId): int
    {
        $query = $this->app->query();

        $query = $this->queryBuilder($query, $userId);

        return $query->count();
    }

    public function create(array $data): App
    {
        return $this->app->create($data);
    }

    public function getById(int $id, int $userId): App
    {
        return $this->app->where('user_id', $userId)
            ->findOrFail($id);
    }

    public function update(int $id, array $data): void
    {
        $this->app->where('id', $id)
            ->update($data);
    }

    public function softDelete(int $id)
    {
        // Implement softDelete method
    }

    public function delete(int $id)
    {
        // Implement delete method
    }
}
