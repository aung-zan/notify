<?php

namespace App\Repositories;

use App\Interfaces\DBInterface;
use App\Models\Push;

class PushRepository implements DBInterface
{
    private $push;

    public function __construct(Push $push)
    {
        $this->push = $push;
    }

    public function getAll()
    {
        //
    }

    public function create(array $data): Push
    {
        return $this->push->create($data);
    }

    public function getById(int $id)
    {
        //
    }

    public function update(array $data)
    {
        //
    }

    public function softDelete()
    {
        //
    }

    public function delete()
    {
        //
    }
}
