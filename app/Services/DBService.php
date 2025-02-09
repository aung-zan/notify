<?php

namespace App\Services;

use App\Interfaces\DBInterface;
use Illuminate\Database\Eloquent\Model;

abstract class DBService
{
    protected $database;

    public function __construct(DBInterface $database)
    {
        $this->database = $database;
    }

    abstract public function list(array $request): array;

    abstract public function store(array $request): void;

    abstract public function show(int $id): array|Model;

    abstract public function edit(int $id): array|Model;

    abstract public function update(int $id, array $request): void;

    /**
     * return the search request.
     *
     * @param array $request
     * @param array $columns
     * @return array
     */
    protected function getSearchRequest(array $request, array $columns): array
    {
        $searchValue = [];

        if (! is_null($request['search']['value'])) {
            foreach ($columns as $column) {
                $searchValue[$column] = $request['search']['value'];
            }
        }

        return $searchValue;
    }

    /**
     * return the order request.
     *
     * @param array $request
     * @return array
     */
    protected function getOrderRequest(array $request): array
    {
        $order = [];

        if (array_key_exists('order', $request)) {
            $column = $request['order'][0]['column'];
            $order = [
                'column' => $request['columns'][$column]['data'],
                'dir' => $request['order'][0]['dir'],
            ];
        }

        return $order;
    }
}
