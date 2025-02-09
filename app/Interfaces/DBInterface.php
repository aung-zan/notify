<?php

namespace App\Interfaces;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface DBInterface
{
    /**
     * return the records filtered by $filter
     * [int $userId, array $keywords, array $order].
     *
     * @param array $filter
     * @return Collection
     */
    public function getAll(array $filter): Collection;

    /**
     * return count of the records with user_id.
     *
     * @param int $userId
     * @return int
     */
    public function getAllCount(int $userId): int;

    /**
     * create a record.
     *
     * @param array $data
     * @return Model
     */
    public function create(array $data): Model;

    /**
     * return a record or null by id.
     *
     * @param int $id
     * @param int $userId
     * @return Model|null
     */
    public function getById(int $id, int $userId): Model|null;

    /**
     * update a record.
     *
     * @param int $id
     * @param array $data
     * @return void
     */
    public function update(int $id, array $data): void;

    /**
     * insert data in delete flag column.
     *
     * @param int $id
     */
    public function softDelete(int $id);

    /**
     * permanently delete a record.
     *
     * @param int $id
     */
    public function delete(int $id);
}
