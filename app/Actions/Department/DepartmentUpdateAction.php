<?php

namespace App\Actions\Department;

use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentUpdateAction
{
    public function __invoke(Request $request, Department $department)
    {
        $department->update($request->only(['name', 'started_at']));

        $workers = collect($request->get('workers'))->pluck('id')->all();

        $department->workers()->sync($workers);
    }
}
