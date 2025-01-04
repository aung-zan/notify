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

    /**
     * return all apps by user_id.
     *
     * @param array $keywords
     * @param array $order
     * @return Collection
     */
    public function getAll(array $keywords, array $order): Collection
    {
        $userId = 1;

        $query = $this->app->query();

        $query = $this->queryBuilder($query, $userId, $keywords, $order);

        return $query->get();
    }

    /**
     * return the apps' count by user_id.
     *
     * @return int
     */
    public function getAllCount(): int
    {
        $userId = 1;

        $query = $this->app->query();

        $query = $this->queryBuilder($query, $userId);

        return $query->count();
    }

    /**
     * create an app with request's data.
     *
     * @param array $data
     * @return App
     */
    public function create(array $data): App
    {
        return $this->app->create($data);
    }

    /**
     * return an app by app_id.
     *
     * @param int $id
     * @param bool $checkOnly
     * @return App
     */
    public function getById(int $id, bool $checkOnly = false): App
    {
        $userId = 1;

        return $this->app->where('user_id', $userId)
            ->findOrFail($id);
    }

    /**
     * update an app by app_id.
     *
     * @param int $id
     * @param array $data
     * @return void
     */
    public function update(int $id, array $data): void
    {
        $app = $this->getById($id);

        if ($app->notifications()->exists()) {
            // TODO: use a custom exception.
            throw new \Exception('App is used.');
        }

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
