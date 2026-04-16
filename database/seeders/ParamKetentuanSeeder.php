<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ParamKetentuan;


class ParamKetentuanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         ParamKetentuan::insert([
            [
                'id_param_profil' => '1',
                'nomor_ketentuan' => 'KBJ-III-16/FN-001/01',
                'heading' => 'KEBIJAKAN PEMBIAYAAN',
                'sub_heading' => 'Persyaratan Umum',
                'sub_sub_heading' => 'Persyaratan Penerima Pembiayaan',
                'sub_sub_sub_heading' => 'Usia saat pembiayaan pertama maksimal 60 tahun dan pembiayaan lanjutan maksimal
usia 65 tahun.',
                'sub_sub_sub_sub_heading' => ''
            ],
            [
                'id_param_profil' => '1',
                'nomor_ketentuan' => 'KBJ-III-16/FN-001/01',
                'heading' => 'KEBIJAKAN PEMBIAYAAN',
                'sub_heading' => 'Persyaratan Umum',
                'sub_sub_heading' => 'Persyaratan Dokumentasi Pembiayaan',
                'sub_sub_sub_heading' => 'Foto e-KTP asli dan foto KK Asli atau KK ber barcode atau DK-1 atau DK-2 (KK
sementara dari Kelurahan atau desa)',
                'sub_sub_sub_sub_heading' => 'Jika tidak ada e-KTP(karena hilang/proses pembuatan e-KTP baru), maka
diperbolehkan menggunakan resi perekaman e-KTP asli yang berbarcode dari
Dinas dukcapil.'
            ],
            [
                'id_param_profil' => '1',
                'nomor_ketentuan' => 'KBJ-III-16/FN-001/01',
                'heading' => 'KEBIJAKAN PEMBIAYAAN',
                'sub_heading' => 'Persyaratan Umum',
                'sub_sub_heading' => 'Persyaratan Khusus',
                'sub_sub_sub_heading' => 'Jangka Waktu Pembiayaan (tenor)',
                'sub_sub_sub_sub_heading' => '50 pekan, hanya boleh diajukan untuk PL atas Anggota yang tenor pembiayaan
sebelumnya 50 pekan dan semua Anggota dalam satu kelompok tersebut lancar,
dengan plafond pembiayaan diatas Rp. 5.000.000,- s/d Rp. 10 juta
'           ],
            [
                'id_param_profil' => '1',
                'nomor_ketentuan' => 'KBJ-III-16/FN-001/01',
                'heading' => 'KEBIJAKAN PEMBIAYAAN',
                'sub_heading' => 'Persyaratan Umum',
                'sub_sub_heading' => 'Uji Kelayakan (UK)',
                'sub_sub_sub_heading' => 'AO wajib melakukan kunjungan ke tempat tinggal dan atau tempat usaha Anggota/calon
anggota pembiayaan untuk melakukan Uji Kelayakan (UK) dengan menggunakan form
Analisa pembiayaan (sesuai lampiran 3A untuk P1 dan lampiran 3B untuk PL).
',
                'sub_sub_sub_sub_heading' => '',
            ],
            [
                'id_param_profil' => '1',
                'nomor_ketentuan' => 'KBJ-III-16/FN-001/01',
                'heading' => 'KEBIJAKAN PEMBIAYAAN',
                'sub_heading' => 'Persyaratan Umum',
                'sub_sub_heading' => 'Uji Kelayakan (UK)',
                'sub_sub_sub_heading' => 'AO mengambil foto anggota di depan usahanya menggunakan GPS map camera. Jika
usaha anggota yang dibiayai berada di luar kota, diganti foto anggota didepan rumahnya.',
                'sub_sub_sub_sub_heading' => '',
            ],
            [
                'id_param_profil' => '1',
                'nomor_ketentuan' => 'KBJ-III-16/FN-001/01',
                'heading' => 'KEBIJAKAN PEMBIAYAAN',
                'sub_heading' => 'Persyaratan Umum',
                'sub_sub_heading' => 'Validasi',
                'sub_sub_sub_heading' => 'Validasi dilakukan dengan mengunjungi rumah dan atau tempat usaha anggota.',
                'sub_sub_sub_sub_heading' => '',
            ],
             [
                'id_param_profil' => '1',
                'nomor_ketentuan' => 'KBJ-III-16/FN-001/01',
                'heading' => 'KEBIJAKAN PEMBIAYAAN',
                'sub_heading' => 'Persyaratan Umum',
                'sub_sub_heading' => 'Akad wakalah',
                'sub_sub_sub_heading' => 'Petugas wajib mendokumentasikan (memfoto) pelaksanaan penandatanganan akad
wakalah (wefie petugas wakalah, anggota maximal 5 anggota sekali capture;
contoh: jika dalam 1 center meeting ada 10 anggota, dilakukan 2 kali foto) sebagai
bagian dokumen yang harus diserahkan kepada Bagian Admin.',
                'sub_sub_sub_sub_heading' => '',
             ],
             [
                'id_param_profil' => '1',
                'nomor_ketentuan' => 'KBJ-III-16/FN-001/01',
                'heading' => 'KEBIJAKAN PEMBIAYAAN',
                'sub_heading' => 'Persyaratan Umum',
                'sub_sub_heading' => 'Akad Murabahah',
                'sub_sub_sub_heading' => ' Penandatangan akad dilakukan sesuai kesepakatan Kelompok dan Koperasi
selambatnya sepekan setelah pencairan dana (akad wakalah), setelah dipastikan
barang telah dibeli dan dilakukan pemeriksaan atas dokumen :',
                'sub_sub_sub_sub_heading' => 'Apabila barang sudah dijual sebelum akad murabahah dilakukan, maka
anggota harus menunjukan barang yang dibeli dengan memperlihatkan foto
atau barang yang sama dan telah melakukan konfirmasi kepada koperasi
setelah melakukan pembelian barang.',
             ],
              [
                'id_param_profil' => '1',
                'nomor_ketentuan' => 'KBJ-III-16/FN-001/01',
                'heading' => 'KEBIJAKAN PEMBIAYAAN',
                'sub_heading' => 'Persyaratan Umum',
                'sub_sub_heading' => 'Syarat Pembiayaan Area Pemasaran (AP)',
                'sub_sub_sub_heading' => 'Bagi Account Officer (AO) yang timbul penunggak baru (bukan karena anggota
meninggal), pada 2 (dua) pekan berikut tidak diperkenankan mengajukan pembiayaan
baik bagi anggota baru maupun anggota lanjutan.
',
                'sub_sub_sub_sub_heading' => '',
              ],
              [
                'id_param_profil' => '1',
                'nomor_ketentuan' => 'KBJ-III-16/FN-001/01',
                'heading' => 'KEBIJAKAN PEMBIAYAAN',
                'sub_heading' => 'Persyaratan Umum',
                'sub_sub_heading' => 'Kewajiban Setoran 5% plafond pembiayaan',
                'sub_sub_sub_heading' => 'Simpanan pokok dan wajib yang berasal dari 5% dapat diambil apabila anggota telah
keluar dari keanggotaan Koperasi.',
                'sub_sub_sub_sub_heading' => '',
             ]
     ]);
    }
}
