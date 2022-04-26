<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Route;

Route::get('batch/{batchId}', function (string $batchId) {
    return Bus::findBatch($batchId);
});
Route::get('stats', \App\Actions\GetStatsAction::class);
Route::get('application_config', \App\Actions\GetApplicationConfigAction::class);
Route::post('set_database_connection', \App\Actions\SetDatabaseConnectionAction::class);
Route::post('queue_start', function () {
    Artisan::call('queue:work');
});
Route::post('install_sample_data', \App\Actions\InstallSampleData::class);


Route::get('department_list', \App\Actions\Department\DepartmentListAction::class);
Route::get('departments/find', \App\Actions\Department\DepartmentFindAction::class);
Route::get('workers/find', \App\Actions\Worker\WorkerFindAction::class);

Route::resource('departments', \App\Http\Controllers\DepartmentController::class);
Route::resource('workers', \App\Http\Controllers\WorkerController::class);

