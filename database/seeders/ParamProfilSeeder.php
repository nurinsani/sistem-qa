<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ParamProfilSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('param_profil')->insert([
            // wawancara
            [  
                'deskripsi' => 'Apakah MM melakukan validasi anggota jatuh tempo dan angsuran ke 18',
                'level' => 'HIGH',
                'kategori_param' => 'Wawancara',
            ],
            [  
                'deskripsi' => 'Apakah akad Murabahah dibacakan dan dijelaskan sebelum penandatangan?',
                'level' => 'HIGH',
                'kategori_param' => 'Wawancara',
            ],
            [  
                'deskripsi' => 'Apakah anggota kelompok 5 - 25 orang?',
                'level' => 'HIGH',
                'kategori_param' => 'Wawancara',
            ],
            [  
                'deskripsi' => 'Apakah yang di tanda tangani bersamaan dan merupakan satu kesatuaan dengan akad murabahah..?',
                'level' => 'MEDIUM',
                'kategori_param' => 'Wawancara',
            ],
            [  
                'deskripsi' => 'Apakah yang di tanda tangani bersamaan dan merupakan satu kesatuaan dengan akad murabahah..?',
                'level' => 'LOW',
                'kategori_param' => 'Wawancara',
            ],
            // observasi
             [  
                'deskripsi' => 'Apakah MM/SAO mengunjungi rumah atau tempat anggota serta mengecek foto gmaps AO pada saat survey',
                'level' => 'HIGH',
                'kategori_param' => 'Observasi',
            ],
            [  
                'deskripsi' => 'Apakah cs tagihan AO sesuai dengan yang terjadi di lapangan',
                'level' => 'HIGH',
                'kategori_param' => 'Observasi',
            ],
            [  
                'deskripsi' => 'Apakah MM memonitoring hasil inputan setor bank AO di mobcol dan cek rekapan mobcol pada H+1',
                'level' => 'MEDIUM',
                'kategori_param' => 'Observasi',
            ],
            [  
                'deskripsi' => 'Apakah MM melakukan validasi kartu angsuran anggota',
                'level' => 'LOW',
                'kategori_param' => 'Observasi',
            ],
        ]);
    }
}
