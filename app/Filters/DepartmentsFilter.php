<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;

class DepartmentsFilter extends QueryFilter
{

    public function salaryFrom($value)
    {
        $this->builder->whereHas(
            'workers', function (Builder $query) use ($value) {
            $query->where('salary', '>=', $value);
        }
        );
    }

    public function salaryTo($value)
    {
        $this->builder->whereHas(
            'workers', function (Builder $query) use ($value) {
            $query->where('salary', '<=', $value);
        }
        );
    }

    public function name($value)
    {
        $this->builder->orderBy('name', $value);
    }

}
