<?php

namespace Database\Seeders;

use App\Models\Sampling;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SamplingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Sampling::insert([
            // high sampling
            [  
                'cif' => '003063',
                'unit' => '001',
                'kode_profil' => '111',
                'status_sampling' => 'HIGH',
                'kode_kel' => '001-0828',
                'kode_ao' => '00101',
                'tgl_pull' => now(),
            ],
            [
                'cif' => '00AK2E',
                'unit' => '001',
                'kode_profil' => '111',
                'status_sampling' => 'HIGH',
                'kode_kel' => '001-0828',
                'kode_ao' => '00101',
                'tgl_pull' => now(),
            ],
            [
                'cif' => '00KJ50',
                'unit' => '001',
                'kode_profil' => '112',
                'status_sampling' => 'HIGH',
                'kode_kel' => '001-0828',
                'kode_ao' => '00101',
                'tgl_pull' => now(),
            ],
            [
                'cif' => '016BC2',
                'unit' => '001',
                'kode_profil' => '112',
                'status_sampling' => 'HIGH',
                'kode_kel' => '001-0828',
                'kode_ao' => '00101',
                'tgl_pull' => now(),
            ],
            [
                'cif' => '01FI79',
                'unit' => '001',
                'kode_profil' => '112',
                'status_sampling' => 'HIGH',
                'kode_kel' => '001-0828',
                'kode_ao' => '00101',
                'tgl_pull' => now(),
            ],
            [
                'cif' => '01I486',
                'unit' => '001',
                'kode_profil' => '112',
                'status_sampling' => 'HIGH',
                'kode_kel' => '001-0828',
                'kode_ao' => '00101',
                'tgl_pull' => now(),
            ],
            [
                'cif' => '01LF8F',
                'unit' => '001',
                'kode_profil' => '113',
                'status_sampling' => 'HIGH',
                'kode_kel' => '001-0828',
                'kode_ao' => '00101',
                'tgl_pull' => now(),
            ],
            [
                'cif' => '022EE6',
                'unit' => '001',
                'kode_profil' => '113',
                'status_sampling' => 'HIGH',
                'kode_kel' => '001-0828',
                'kode_ao' => '00101',
                'tgl_pull' => now(),
            ],
            [
                'cif' => '024F44',
                'unit' => '001',
                'kode_profil' => '113',
                'status_sampling' => 'HIGH',
                'kode_kel' => '001-0828',
                'kode_ao' => '00101',
                'tgl_pull' => now(),
            ],
            [
                'cif' => '026BA3',
                'unit' => '001',
                'kode_profil' => '113',
                'status_sampling' => 'HIGH',
                'kode_kel' => '001-0828',
                'kode_ao' => '00101',
                'tgl_pull' => now(),
            ],
            // end high sampling

            // medium sampling
            [  
                'cif' => '038269',
                'unit' => '001',
                'kode_profil' => '114',
                'status_sampling' => 'MEDIUM',
                'kode_kel' => '001-0828',
                'kode_ao' => '00101',
                'tgl_pull' => now(),
            ],
            [
                'cif' => '03AAHC',
                'unit' => '001',
                'kode_profil' => '114',
                'status_sampling' => 'MEDIUM',
                'kode_kel' => '001-0828',
                'kode_ao' => '00101',
                'tgl_pull' => now(),
            ],
            [
                'cif' => '03I9EA',
                'unit' => '001',
                'kode_profil' => '114',
                'status_sampling' => 'MEDIUM',
                'kode_kel' => '001-0828',
                'kode_ao' => '00101',
                'tgl_pull' => now(),
            ],
            [
                'cif' => '03JD27',
                'unit' => '001',
                'kode_profil' => '115',
                'status_sampling' => 'MEDIUM',
                'kode_kel' => '001-0828',
                'kode_ao' => '00101',
                'tgl_pull' => now(),
            ],
            [
                'cif' => '040E77',
                'unit' => '001',
                'kode_profil' => '115',
                'status_sampling' => 'MEDIUM',
                'kode_kel' => '001-0828',
                'kode_ao' => '00101',
                'tgl_pull' => now(),
            ],
            [
                'cif' => '04BD0I',
                'unit' => '001',
                'kode_profil' => '115',
                'status_sampling' => 'MEDIUM',
                'kode_kel' => '001-0828',
                'kode_ao' => '00101',
                'tgl_pull' => now(),
            ],
            [
                'cif' => '04JDED',
                'unit' => '001',
                'kode_profil' => '116',
                'status_sampling' => 'MEDIUM',
                'kode_kel' => '001-0828',
                'kode_ao' => '00101',
                'tgl_pull' => now(),
            ],
            [
                'cif' => '0519DB',
                'unit' => '001',
                'kode_profil' => '116',
                'status_sampling' => 'MEDIUM',
                'kode_kel' => '001-0828',
                'kode_ao' => '00101',
                'tgl_pull' => now(),
            ],
            [
                'cif' => '0675I1',
                'unit' => '001',
                'kode_profil' => '117',
                'status_sampling' => 'MEDIUM',
                'kode_kel' => '001-0828',
                'kode_ao' => '00101',
                'tgl_pull' => now(),
            ],
            [
                'cif' => '06AKA6',
                'unit' => '001',
                'kode_profil' => '117',
                'status_sampling' => 'MEDIUM',
                'kode_kel' => '001-0828',
                'kode_ao' => '00101',
                'tgl_pull' => now(),
            ],
            // end medium sampling

            // low sampling
            [
                'cif' => '0B5503',
                'unit' => '001',
                'kode_profil' => '441',
                'status_sampling' => 'LOW',
                'kode_kel' => '001-0828',
                'kode_ao' => '00101',
                'tgl_pull' => now(),
            ],
            [
                'cif' => '0BJ930',
                'unit' => '001',
                'kode_profil' => '441',
                'status_sampling' => 'LOW',
                'kode_kel' => '001-0828',
                'kode_ao' => '00101',
                'tgl_pull' => now(),
            ],
            [
                'cif' => '0C6K84',
                'unit' => '001',
                'kode_profil' => '441',
                'status_sampling' => 'LOW',
                'kode_kel' => '001-0828',
                'kode_ao' => '00101',
                'tgl_pull' => now(),
            ],
            [
                'cif' => '0CAEF8',
                'unit' => '001',
                'kode_profil' => '441',
                'status_sampling' => 'LOW',
                'kode_kel' => '001-0828',
                'kode_ao' => '00101',
                'tgl_pull' => now(),
            ],
            [
                'cif' => '0CIE1K',
                'unit' => '001',
                'kode_profil' => '441',
                'status_sampling' => 'LOW',
                'kode_kel' => '001-0828',
                'kode_ao' => '00101',
                'tgl_pull' => now(),
            ],
            // end low sampling

        ]);
    }
}
