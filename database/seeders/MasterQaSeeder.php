<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasterQaSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('masterqa')->truncate(); 

        DB::table('masterqa')->insert([
            [
                'code_qa'   => '1101',
                'nama_qa'   => 'Ocky Monteri',
                'no_tlp'    => '081234567890',
                'kode_unit' => '1101',
                'atasan'    => '2220',
                'nik_qa'    => '024010314'
            ]
        ]);
    }
}