<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait QueryBuilder
{
    private function queryBuilder(Builder $query, int $id, array $keywords = [], array $order = []): Builder
    {
        $query = $query->where('user_id', $id);

        if (! empty($keywords)) {
            $count = 1;

            $query = $query->where(function ($query) use ($keywords, $count) {
                foreach ($keywords as $column => $value) {
                    if ($count == 1) {
                        $query = $query->where($column, 'LIKE', '%' . $value . '%');
                    } else {
                        $query = $query->orWhere($column, 'LIKE', '%' . $value . '%');
                    }

                    $count++;
                }
            });
        }

        if (! empty($order)) {
            return $query->orderBy($order['column'], $order['dir']);
        }

        return $query->orderBy('id');
    }
}
