<?php

namespace App\Interfaces;

interface DBInterface
{
    public function getAll(array $keywords, array $order);

    public function getAllCount();

    public function create(array $data);

    public function getById(int $id);

    public function update(int $id, array $data);

    public function softDelete();

    public function delete();
}
