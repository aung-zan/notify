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

    public function getById(int $id, bool $checkOnly = false)
    {
        // Implement getById method
    }

    public function update(int $id, array $data)
    {
        // Implement update method
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
