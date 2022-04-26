<?php

namespace App\Http\Controllers;

use App\Actions\Department\DepartmentCreateAction;
use App\Actions\Department\DepartmentDeleteAction;
use App\Actions\Department\DepartmentUpdateAction;
use App\Filters\DepartmentsFilter;
use App\Http\Resources\DepartmentResource;
use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{

    /**
     * @param Request $request
     * @param DepartmentsFilter $filters
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request, DepartmentsFilter $filters): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        return DepartmentResource::collection(Department::filter($filters)->paginate($request->get('paginate')));
    }


    /**
     * @param Request $request
     * @param DepartmentCreateAction $action
     * @return void
     */
    public function store(Request $request, DepartmentCreateAction $action)
    {
        return $action($request);
    }


    /**
     * @param Request $request
     * @param Department $department
     * @param DepartmentUpdateAction $action
     * @return void
     */
    public function update(Request $request, Department $department, DepartmentUpdateAction $action)
    {
        return $action($request, $department);
    }


    /**
     * @param Department $department
     * @param DepartmentDeleteAction $action
     * @return void
     */
    public function destroy(Department $department, DepartmentDeleteAction $action)
    {
        return $action($department);
    }
}
