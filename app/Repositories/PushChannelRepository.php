<?php

namespace App\Repositories;

use App\Interfaces\DBInterface;
use App\Models\PushChannel;
use App\Traits\QueryBuilder;
use Illuminate\Database\Eloquent\Collection;

class PushChannelRepository implements DBInterface
{
    use QueryBuilder;

    private $pushChannel;

    public function __construct(PushChannel $pushChannel)
    {
        $this->pushChannel = $pushChannel;
    }

    public function getAll(array $filter): Collection
    {
        list($userId, $keywords, $order) = $filter;

        $query = $this->pushChannel->query();

        $query = $this->queryBuilder($query, $userId, $keywords, $order);

        return $query->get();
    }

    public function getAllCount(int $userId): int
    {
        $query = $this->pushChannel->query();

        $query = $this->queryBuilder($query, $userId);

        return $query->count();
    }

    public function create(array $data): PushChannel
    {
        return $this->pushChannel->create($data);
    }

    public function getById(int $id, int $userId): PushChannel
    {
        return $this->pushChannel->where('user_id', $userId)
            ->findOrFail($id);
    }

    public function update(int $id, array $data): void
    {
        $pushChannel = $this->pushChannel->where('id', $id)
            ->first();

        $pushChannel->fill($data);
        $pushChannel->save();
    }

    public function softDelete(int $id)
    {
        // this function will implement later.
    }

    public function delete(int $id)
    {
        // this function will implement later.
    }

    public function checkById(int $id, int $userId): PushChannel|null
    {
        return $this->pushChannel->where('user_id', $userId)
            ->where('id', $id)
            ->first();
    }
}
