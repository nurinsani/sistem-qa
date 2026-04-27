<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class MasterQaToUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        // hapus data lama di tabel users yang memiliki code_qa yang sama dengan masterqa
        $masterQaCodes = DB::table('masterqa')->pluck('code_qa');
        DB::table('users')->whereIn('code_qa', $masterQaCodes)->delete();

        // 1. Ambil semua data dari master_qa
        $masterQa = DB::table('masterqa')->get();

        foreach ($masterQa as $data) {
            $email = Str::lower(str_replace(' ', '.', $data->code_qa)) . '@ni';

            // 3. Masukkan ke tabel users
            DB::table('users')->updateOrInsert(
                ['code_qa' => $data->code_qa], // Cek apakah code_qa sudah ada (biar tidak duplikat)
                [
                    'name'      => $data->nama_qa,
                    'email'     => $email,
                    'password'  => Hash::make('ni123456'), 
                    'role_id'   => 1, // Sesuaikan dengan role (misal: 2 = QAL)
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}
