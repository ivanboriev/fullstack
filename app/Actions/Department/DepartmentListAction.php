<?php

namespace App\Actions\Department;

use App\Models\Department;

class DepartmentListAction
{

    public function __invoke()
    {
        return Department::all(['id','name']);
    }

}
