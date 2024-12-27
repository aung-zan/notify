<?php

namespace App\Interfaces;

interface DBInterface
{
    public function getAll(array $keywords, array $order);

    public function getAllCount();

    public function create(array $data);

    public function getById(int $id, bool $checkOnly = false);

    public function update(int $id, array $data);

    public function softDelete(int $id);

    public function delete(int $id);
}
