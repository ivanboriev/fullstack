<?php

namespace App\Actions\Worker;

use App\Models\Worker;

class WorkerDeleteAction
{
    public function __invoke(Worker $worker)
    {
        $worker->departments()->detach();
        $worker->delete();
    }
}
