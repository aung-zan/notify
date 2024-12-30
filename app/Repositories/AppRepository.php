<?php

namespace App\Repositories;

use App\Interfaces\DBInterface;
use App\Models\App;

class AppRepository implements DBInterface
{
    private $app;

    public function __construct(App $app)
    {
        $this->app = $app;
    }

    public function getAll(array $keywords, array $order)
    {
        // Implement getAll method
    }

    public function getAllCount()
    {
        // Implement getAllCount method
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
        $this->getById($id);

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
