<?php

namespace App\Repositories;

use App\Interfaces\DBInterface;
use App\Models\PushChannel;
use App\Traits\QueryBuilder;
use Illuminate\Database\Eloquent\Collection;

class PushChannelRepository implements DBInterface
{
    use QueryBuilder;

    private $push;

    public function __construct(PushChannel $push)
    {
        $this->push = $push;
    }

    /**
     * return all push notifications channels by user_id.
     *
     * @param array $keywords
     * @param array $order
     * @return Collection
     */
    public function getAll(array $keywords, array $order): Collection
    {
        $userId = 1;

        $query = $this->push->query();

        $query = $this->queryBuilder($query, $userId, $keywords, $order);

        return $query->get();
    }

    /**
     * return the push notification channels' count by user_id.
     *
     * @return int
     */
    public function getAllCount(): int
    {
        $userId = 1;

        $query = $this->push->query();

        $query = $this->queryBuilder($query, $userId);

        return $query->count();
    }

    /**
     * create a push notification channel.
     *
     * @param array $data
     * @return PushChannel
     */
    public function create(array $data): PushChannel
    {
        return $this->push->create($data);
    }

    /**
     * return a push notification channel by channel_id.
     *
     * @param int $id
     * @param bool $checkOnly
     * @return PushChannel|null
     */
    public function getById(int $id, bool $checkOnly = false): PushChannel|null
    {
        $userId = 1;

        if ($checkOnly) {
            return $this->push->where('user_id', $userId)
                ->where('id', $id)
                ->first();
        }

        return $this->push->where('user_id', $userId)
            ->findOrFail($id);
    }

    /**
     * update the push notification channel by channel_id.
     *
     * @param int $id
     * @param array $data
     * @return void
     */
    public function update(int $id, array $data): void
    {
        $pushChannel = $this->getById($id);

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
}
