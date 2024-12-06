<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait QueryBuilder
{
    private function queryBuilder(Builder $query, int $id, array $keywords): Builder
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

        $query = $query->orderBy('id');

        return $query;
    }
}
