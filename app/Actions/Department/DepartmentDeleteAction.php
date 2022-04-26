<?php

namespace App\Actions\Department;

use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentDeleteAction
{
    public function __invoke(Department $department)
    {
        $department->workers()->detach();
        $department->delete();
    }
}
