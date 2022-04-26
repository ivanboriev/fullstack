<?php

namespace App\Http\Controllers;

use App\Actions\Worker\WorkerDeleteAction;
use App\Actions\Worker\WorkersCreateAction;
use App\Actions\Worker\WorkerUpdateAction;
use App\Filters\WorkersFilter;
use App\Http\Resources\WorkerResource;
use App\Models\Worker;
use Illuminate\Http\Request;

class WorkerController extends Controller
{

    /**
     * @param Request $request
     * @param WorkersFilter $filters
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request, WorkersFilter $filters): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        return WorkerResource::collection(Worker::filter($filters)->paginate($request->get('paginate')));
    }

    /**
     * @param Request $request
     * @param WorkersCreateAction $action
     * @return void
     */
    public function store(Request $request, WorkersCreateAction $action)
    {
        return $action($request);
    }


    /**
     * @param Request $request
     * @param Worker $worker
     * @param WorkerUpdateAction $action
     * @return void
     */
    public function update(Request $request, Worker $worker, WorkerUpdateAction $action)
    {
        return $action($request, $worker);
    }


    /**
     * @param Worker $worker
     * @param WorkerDeleteAction $action
     * @return void
     */
    public function destroy(Worker $worker, WorkerDeleteAction $action)
    {
        return $action($worker);
    }
}
