<?php

namespace App\Filters;

use App\Models\Department;
use Illuminate\Database\Eloquent\Builder;

class WorkersFilter extends QueryFilter
{

    public function salaryFrom($value)
    {
        $this->builder->where('salary', '>=', $value);
    }

    public function salaryTo($value)
    {
        $this->builder->where('salary', '<=', $value);
    }

    public function name($value)
    {
        $this->builder->orderBy('name', $value);
    }

    public function byDepartment($values)
    {
        // Такое решение работает намного быстрее

        $departments = Department::with('workers')->find(explode(',', $values));

        $workersIds = $departments->map(function ($department) {
            return $department->workers->pluck('id');
        });


        $this->builder->whereIn('id', $workersIds->flatten()->unique()->toArray());
    }

}
