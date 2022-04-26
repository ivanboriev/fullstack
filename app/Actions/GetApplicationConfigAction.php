<?php

namespace App\Actions;

use Illuminate\Http\Request;

class GetApplicationConfigAction extends AppManager
{
    public function __invoke(Request $request): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'name' => env('APP_NAME'),
            'db_connection' => $this->checkDBConnection(),
            'db_config' => $this->checkDBConnection() ? [] : $this->getDbConfig(),
            'sample_data' => $this->checkInstalledSampleData(),

        ]);
    }

}
