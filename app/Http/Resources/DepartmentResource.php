<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DepartmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->resource->id,
            'name' => $this->resource->name,
            'started_at' => $this->resource->started_at->format('Y-m-d'),
            "workers" => $this->resource->workers->map(function ($worker) {
                return [
                    'id' => $worker->id,
                    'name' => $worker->name,
                    'salary' => $worker->salary,
                ];
            })
        ];
    }
}
