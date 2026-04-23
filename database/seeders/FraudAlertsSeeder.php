<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;

class FraudAlertsSeeder extends Seeder
{
    public function run(): void
    {
        $path = database_path('fraud_alerts.sql');

        if (!File::exists($path)) {
            $this->command->error("File not found: $path");
            return;
        }

        $sql = File::get($path);

        $sql = str_replace(
            [
                "'0000-00-00 00:00:00'",
                "'0000-00-00'",
                "0000-00-00 00:00:00",
                "0000-00-00"
            ],
            "NULL",
            $sql
        );

        DB::unprepared($sql);
    }
}