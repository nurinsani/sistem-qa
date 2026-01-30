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
                'kode_status' => '111',
            ],
            [
                'cif' => '00AK2E',
                'unit' => '001',
                'kode_status' => '111',
            ],
            [
                'cif' => '00KJ50',
                'unit' => '001',
                'kode_status' => '112',
            ],
            [
                'cif' => '016BC2',
                'unit' => '001',
                'kode_status' => '112',
            ],
            [
                'cif' => '01FI79',
                'unit' => '001',
                'kode_status' => '112',
            ],
            [
                'cif' => '01I486',
                'unit' => '001',
                'kode_status' => '112',
            ],
            [
                'cif' => '01LF8F',
                'unit' => '001',
                'kode_status' => '113',
            ],
            [
                'cif' => '022EE6',
                'unit' => '001',
                'kode_status' => '113',
            ],
            [
                'cif' => '024F44',
                'unit' => '001',
                'kode_status' => '113',
            ],
            [
                'cif' => '026BA3',
                'unit' => '001',
                'kode_status' => '113',
            ],

            [
                'cif' => '026DLE',
                'unit' => '001',
                'kode_status' => '221',
            ],
            [
                'cif' => '02B2KJ',
                'unit' => '001',
                'kode_status' => '221',
            ],
            [
                'cif' => '02E5C5',
                'unit' => '001',
                'kode_status' => '222',
            ],
            [
                'cif' => '02FHEI',
                'unit' => '001',
                'kode_status' => '222',
            ],
            [
                'cif' => '0374E2',
                'unit' => '001',
                'kode_status' => '222',
            ],
            // end high sampling

            // medium sampling
            [  
                'cif' => '038269',
                'unit' => '001',
                'kode_status' => '114',
            ],
            [
                'cif' => '03AAHC',
                'unit' => '001',
                'kode_status' => '114',
            ],
            [
                'cif' => '03I9EA',
                'unit' => '001',
                'kode_status' => '114',
            ],
            [
                'cif' => '03JD27',
                'unit' => '001',
                'kode_status' => '115',
            ],
            [
                'cif' => '040E77',
                'unit' => '001',
                'kode_status' => '115',
            ],
            [
                'cif' => '04BD0I',
                'unit' => '001',
                'kode_status' => '115',
            ],
            [
                'cif' => '04JDED',
                'unit' => '001',
                'kode_status' => '116',
            ],
            [
                'cif' => '0519DB',
                'unit' => '001',
                'kode_status' => '116',
            ],
            [
                'cif' => '0675I1',
                'unit' => '001',
                'kode_status' => '117',
            ],
            [
                'cif' => '06AKA6',
                'unit' => '001',
                'kode_status' => '117',
            ],

            [
                'cif' => '07CB5A',
                'unit' => '001',
                'kode_status' => '331',
            ],
            [
                'cif' => '0A8ADD',
                'unit' => '001',
                'kode_status' => '331',
            ],
            [
                'cif' => '0AA60A',
                'unit' => '001',
                'kode_status' => '332',
            ],
            [
                'cif' => '0AGJ61',
                'unit' => '001',
                'kode_status' => '332',
            ],
            [
                'cif' => '0B4D20',
                'unit' => '001',
                'kode_status' => '332',
            ],
            // end medium sampling

            // low sampling
            [
                'cif' => '0B5503',
                'unit' => '001',
                'kode_status' => '441',
            ],
            [
                'cif' => '0BJ930',
                'unit' => '001',
                'kode_status' => '441',
            ],
            [
                'cif' => '0C6K84',
                'unit' => '001',
                'kode_status' => '441',
            ],
            [
                'cif' => '0CAEF8',
                'unit' => '001',
                'kode_status' => '441',
            ],
            [
                'cif' => '0CIE1K',
                'unit' => '001',
                'kode_status' => '441',
            ],
            // end low sampling

        ]);
    }
}
