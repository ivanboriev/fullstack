<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Worker;
use Illuminate\Database\Seeder;

class SampleData extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Department::factory(100)->create()->each(function ($department) {
            $department->workers()->attach(
               Worker::factory(100)->create()
            );
        });
    }
}
