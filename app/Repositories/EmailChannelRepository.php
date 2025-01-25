<?php

namespace App\Repositories;

use App\Interfaces\DBInterface;
use App\Models\EmailChannel;
use App\Models\PushChannel;
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

    /**
     * return all email_channels by user_id.
     *
     * @param array $keywords
     * @param array $order
     * @return Collection
     */
    public function getAll(array $keywords, array $order): Collection
    {
        $userId = 1;

        $query = $this->emailChannel->query();

        $query = $this->queryBuilder($query, $userId, $keywords, $order);

        return $query->get();
    }

    /**
     * return all email_channels count by user_id.
     *
     * @return int
     */
    public function getAllCount(): int
    {
        $userId = 1;

        $query = $this->emailChannel->query();

        $query = $this->queryBuilder($query, $userId);

        return $query->count();
    }

    /**
     * create an email notification channel.
     *
     * @param array $data
     * @return EmailChannel
     */
    public function create(array $data): EmailChannel
    {
        return $this->emailChannel->create($data);
    }

    public function getById(int $id, bool $checkOnly = false)
    {
        // this function will implemented in another branch.
    }

    public function update(int $id, array $data)
    {
        // this function will implemented in another branch.
    }

    public function softDelete(int $id)
    {
        // this function will implemented in another branch.
    }

    public function delete(int $id)
    {
        // this function will implemented in another branch.
    }
}
