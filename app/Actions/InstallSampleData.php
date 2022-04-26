<?php

namespace App\Actions;

use App\Jobs\CreateDepartments;
use App\Jobs\CreateWorkers;
use App\Models\Department;
use App\Models\Worker;
use Illuminate\Bus\Batch;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Bus;
use Throwable;

class InstallSampleData extends AppManager
{

    private Batch $batch;


    /**
     * @throws Throwable
     */
    public function __invoke(): string
    {
        Artisan::call('migrate:fresh');

        $this->install();

        return $this->batch->id;

    }

    private function install()
    {
        $this->batch = Bus::batch([
            new CreateDepartments,
            new CreateWorkers,
            function () {
                $offset = 0;
                Department::all()->each(function (Department $department) use (&$offset) {
                    $department->workers()->attach(Worker::inRandomOrder()->take(10)->get());
                    $offset += 10;
                });
            }
        ])->dispatch();
    }

}
