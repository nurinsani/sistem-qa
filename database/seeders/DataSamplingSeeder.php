<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\DataSampling;


class DataSamplingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DataSampling::insert([
            [
             'id' => '1',
             'id_ref_sampling'=> '1',
             'unit'=> '001',
             'cif'=> '000807',
             'nama' => 'SUSANTI',
             'kode_kel' => '2020-0670',
             'cao'=> '202006',
             'jenis_audit' => 'audit_rutin',
             'user_id' => '110101'
            ]
     ]);
    }
}