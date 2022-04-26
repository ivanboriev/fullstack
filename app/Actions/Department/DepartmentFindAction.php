<?php

namespace App\Actions\Department;

use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentFindAction
{

    public function __invoke(Request $request)
    {
        $query = Department::query();

        $query->when($request->has('search'), function ($q) use ($request) {
            return $q->where('name', 'like', "%" . $request->get('search') . "%");
        });

        return $query->take(10)->get(['id','name']);
    }

}
