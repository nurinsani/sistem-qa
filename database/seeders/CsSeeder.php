<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class CsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $path = database_path('cs.sql');

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
