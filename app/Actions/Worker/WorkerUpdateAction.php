<?php

namespace App\Actions\Worker;

use App\Models\Worker;
use Illuminate\Http\Request;

class WorkerUpdateAction
{
    public function __invoke(Request $request, Worker $worker)
    {
        $worker->update($request->only(['name', 'salary']));

        $departments = collect($request->get('departments'))->pluck('id')->all();

        $worker->departments()->sync($departments);
    }
}
