<?php

namespace App\Actions;

use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class SetDatabaseConnectionAction extends AppManager
{
    public function __invoke(Request $request): void
    {
        $path = $this->envPath();

        foreach ($request->all() as $key => $value) {
            $this->setEnv($path, $key, $value);
        }
    }

}
