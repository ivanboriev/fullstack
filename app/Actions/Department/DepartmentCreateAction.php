<?php

namespace App\Actions\Department;

use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentCreateAction
{

    public function __invoke(Request $request)
    {
        $department = Department::create($request->only(['name', 'started_at']));

        $workers = collect($request->get('workers'))->pluck('id')->all();

        $department->workers()->attach($workers);
    }

}
