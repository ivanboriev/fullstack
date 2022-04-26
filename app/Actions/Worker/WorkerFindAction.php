<?php

namespace App\Actions\Worker;

use App\Models\Worker;
use Illuminate\Http\Request;

class WorkerFindAction
{

    public function __invoke(Request $request)
    {
        $query = Worker::query();

        $query->when($request->has('search'), function ($q) use ($request) {
            return $q->where('name', 'like', "%" . $request->get('search') . "%");
        });

        return $query->take(10)->get(['id','name']);
    }

}
