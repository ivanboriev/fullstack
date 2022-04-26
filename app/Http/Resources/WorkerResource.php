<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class WorkerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->resource->id,
            'name' => $this->resource->name,
            'salary' => $this->resource->salary,
            'departments' => $this->resource->departments->map(function ($department){
                return [
                    'id' => $department->id,
                    'name' => $department->name
                ];
            })
        ];
    }
}
