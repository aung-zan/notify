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
     * @return Push
     */
    public function create(array $data): Push
    {
        return $this->push->create($data);
    }

    /**
     * return a push notification channel by channel_id.
     *
     * @param int $id
     * @return Push
     */
    public function getById(int $id): Push
    {
        $userId = 1;

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
        $this->getById($id);

        $this->push->where('id', $id)
            ->update($data);
    }

    public function softDelete()
    {
        //
    }

    public function delete()
    {
        //
    }

    /**
     * update the push notification channel by channel_id.
     * (use this method if there is the mutator.)
     *
     * @param int $id
     * @param array $data
     * @return void
     */
    public function save(int $id, array $data): void
    {
        $channel = $this->getById($id);

        $channel->fill($data);
        $channel->save();
    }
}
