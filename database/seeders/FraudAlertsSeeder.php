<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class FraudAlertsSeeder extends Seeder
{
    public function run()
    {
        // ambil data dari tabel sumber
        $customers = DB::table('data_loan_mob')
            ->select('cif', 'Cust_Short_name')
            ->limit(20)
            ->get();

        $data = [];

        foreach ($customers as $cust) {
            $data[] = [
                'cif' => $cust->cif,
                'nama' => $cust->Cust_Short_name,
                'tgl_tagih' => '2026-03-' . rand(25, 30),
                'flag_status' => collect(['LOW', 'MEDIUM', 'HIGH'])->random(),
                'flag_reason' => collect([
                    'Telat bayar > 30 hari',
                    'Over limit kredit',
                    'Transaksi mencurigakan',
                    'Data tidak valid',
                    'Duplikasi CIF'
                ])->random(),
                'created_at' => Carbon::now(),
            ];
        }

        DB::table('fraud_alerts')->insert($data);
    }
}