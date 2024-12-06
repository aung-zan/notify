<?php

namespace App\Interfaces;

interface DBInterface
{
    public function getAll(array $keywords);

    public function getAllCount();

    public function create(array $data);

    public function getById(int $id);

    public function update(array $data);

    public function softDelete();

    public function delete();
}
