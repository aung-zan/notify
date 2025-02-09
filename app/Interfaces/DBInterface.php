<?php

namespace App\Interfaces;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface DBInterface
{
    public function getAll(array $keywords, array $order): Collection;

    public function getAllCount(): int;

    public function create(array $data): Model;

    public function getById(int $id, bool $checkOnly = false): Model|null;

    public function update(int $id, array $data): void;

    public function softDelete(int $id);

    public function delete(int $id);
}
