<?php

namespace App\Actions;

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class AppManager
{

    private Application $application;

    public function __construct()
    {
        $this->application = app();
    }


    protected function envPath(): string
    {
        if (method_exists($this->application, 'environmentFilePath')) {
            return app()->environmentFilePath();
        }

        if (version_compare($this->application->version(), '5.4.17', '<')) {
            return app()->basePath() . DIRECTORY_SEPARATOR . '.env';
        }

        return $this->application->basePath('.env');
    }

    protected function getDbConfig(): array
    {
        return [
            "DB_CONNECTION" => env('DB_CONNECTION'),
            "DB_DATABASE" => env('DB_DATABASE'),
            "DB_HOST" => env('DB_HOST'),
            "DB_PORT" => env('DB_PORT'),
            "DB_USERNAME" => env('DB_USERNAME'),
            "DB_PASSWORD" => env('DB_PASSWORD'),
        ];

    }

    protected function checkDBConnection(): bool
    {
        try {
            Schema::getConnection()->getPdo();
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    protected function checkInstalledSampleData(): bool
    {
        try {
            return Schema::hasTable('departments') && DB::table('departments')->count() > 0
                && Schema::hasTable('workers') && DB::table('workers')->count() > 0
                && Schema::hasTable('department_worker') && DB::table('department_worker')->count() > 0;
        } catch (\Exception $e) {
            return false;
        }

    }

    protected function setEnv($path, $key, $value): void
    {
        if (Str::contains(file_get_contents($path), $key) === false) {
            file_put_contents($path, PHP_EOL . "$key=$value", FILE_APPEND);
        } else {
            file_put_contents($path, str_replace(
                "$key=" . env($key),
                "$key=$value", file_get_contents($path)
            ));
        }
    }

}
