<?php

namespace App\Actions\Worker;

use App\Models\Worker;
use Illuminate\Http\Request;

class WorkersCreateAction
{

    public function __invoke(Request $request)
    {
        $worker = Worker::create($request->only(['name', 'salary']));

        $departments = collect($request->get('departments'))->pluck('id')->all();

        $worker->departments()->attach($departments);
    }

}
