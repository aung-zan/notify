<?php

namespace App\Repositories;

use App\Interfaces\DBInterface;
use App\Models\EmailChannel;
use App\Traits\QueryBuilder;
use Illuminate\Database\Eloquent\Collection;

class EmailChannelRepository implements DBInterface
{
    use QueryBuilder;

    private $emailChannel;

    public function __construct(EmailChannel $emailChannel)
    {
        $this->emailChannel = $emailChannel;
    }

    public function getAll(array $filter): Collection
    {
        list($userId, $keywords, $order) = $filter;

        $query = $this->emailChannel->query();

        $query = $this->queryBuilder($query, $userId, $keywords, $order);

        return $query->get();
    }

    public function getAllCount(int $userId): int
    {
        $query = $this->emailChannel->query();

        $query = $this->queryBuilder($query, $userId);

        return $query->count();
    }

    public function create(array $data): EmailChannel
    {
        return $this->emailChannel->create($data);
    }

    public function getById(int $id, int $userId): EmailChannel
    {
        return $this->emailChannel->where('user_id', $userId)
            ->findOrFail($id);
    }

    public function update(int $id, array $data): void
    {
        $emailChannel = $this->emailChannel->where('id', $id)
            ->first();

        $emailChannel->fill($data);

        $emailChannel->save();
    }

    public function softDelete(int $id)
    {
        // this function will implemented in another branch.
    }

    public function delete(int $id)
    {
        // this function will implemented in another branch.
    }

    public function checkById(int $id, int $userId): EmailChannel|null
    {
        return $this->emailChannel->where('user_id', $userId)
            ->where('id', $id)
            ->first();
    }
}
