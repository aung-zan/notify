<?php

namespace App\Repositories;

use App\Interfaces\DBInterface;
use App\Models\Push;
use App\Traits\QueryBuilder;
use Illuminate\Database\Eloquent\Collection;

class PushRepository implements DBInterface
{
    use QueryBuilder;

    private $push;

    public function __construct(Push $push)
    {
        $this->push = $push;
    }

    public function getAll(array $keywords): Collection
    {
        $userId = 1;

        $query = $this->push->query();

        $query = $this->queryBuilder($query, $userId, $keywords);

        return $query->get();
    }

    public function getAllCount(): int
    {
        $userId = 1;

        $query = $this->push->query();

        $query = $this->queryBuilder($query, $userId, []);

        return $query->count();
    }

    public function create(array $data): Push
    {
        return $this->push->create($data);
    }

    public function getById(int $id)
    {
        //
    }

    public function update(array $data)
    {
        //
    }

    public function softDelete()
    {
        //
    }

    public function delete()
    {
        //
    }
}
