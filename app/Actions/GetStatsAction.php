<?php

namespace App\Actions;

use Illuminate\Support\Facades\DB;

class GetStatsAction
{

    public function __invoke(): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'departments' => DB::table('departments')->count(),
            'workers' => DB::table('workers')->count(),
        ]);
    }

}
