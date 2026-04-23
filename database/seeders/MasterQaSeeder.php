<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasterQASeeder extends Seeder
{
    public function run(): void
    {
        DB::table('masterqa')->truncate();

        DB::table('masterqa')->insert([
            ['code_qa'=>'1101','nama_qa'=>'OCKY MONTERI','kode_unit'=>'1101','no_tlp'=>'','atasan'=>'2220','nik_qa'=>'024010314'],
            ['code_qa'=>'1103','nama_qa'=>'URIP SAPUTRA','kode_unit'=>'1101','no_tlp'=>'','atasan'=>'2220','nik_qa'=>'026090614'],
            ['code_qa'=>'110101','nama_qa'=>'ALAN HERMAWAN','kode_unit'=>'1101','no_tlp'=>'','atasan'=>'1101','nik_qa'=>'338151018'],
            ['code_qa'=>'1102','nama_qa'=>'AI MULYANI','kode_unit'=>'1102','no_tlp'=>'','atasan'=>'2220','nik_qa'=>'230201804'],
            ['code_qa'=>'2200','nama_qa'=>'SLAMET RIYANTO','kode_unit'=>'2200','no_tlp'=>'','atasan'=>'2220','nik_qa'=>'043010515'],
            ['code_qa'=>'220002','nama_qa'=>'WAHYUDI','kode_unit'=>'2200','no_tlp'=>'','atasan'=>'2200','nik_qa'=>'119200916'],
            ['code_qa'=>'220001','nama_qa'=>'NOVARISNANTO ANUGRAH ADINATA','kode_unit'=>'2200','no_tlp'=>'','atasan'=>'2200','nik_qa'=>'2405220002'],
            ['code_qa'=>'2205','nama_qa'=>'IRMA ARTIKA NINGRUM','kode_unit'=>'2205','no_tlp'=>'','atasan'=>'2220','nik_qa'=>'050060715'],
            ['code_qa'=>'220502','nama_qa'=>'YUDDY ARDIYADI, SE','kode_unit'=>'2205','no_tlp'=>'','atasan'=>'2205','nik_qa'=>'888090621'],
            ['code_qa'=>'220501','nama_qa'=>'NUR AIN KHUSNUDHIN','kode_unit'=>'2205','no_tlp'=>'','atasan'=>'2205','nik_qa'=>'997181021'],
            ['code_qa'=>'2203','nama_qa'=>'SIDQI NURFAIZ','kode_unit'=>'2203','no_tlp'=>'','atasan'=>'3330','nik_qa'=>'373150219'],
            ['code_qa'=>'220301','nama_qa'=>'AHMAD RIZA HIDAYAT','kode_unit'=>'2203','no_tlp'=>'','atasan'=>'2203','nik_qa'=>'2309220007'],
            ['code_qa'=>'220401','nama_qa'=>'ADE MURTI IRAWAN','kode_unit'=>'2204','no_tlp'=>'','atasan'=>'2204','nik_qa'=>'2305201503'],
            ['code_qa'=>'2204','nama_qa'=>'WAHYU SUPRIYADI','kode_unit'=>'2204','no_tlp'=>'','atasan'=>'3330','nik_qa'=>'2307202004'],
            ['code_qa'=>'2202','nama_qa'=>'MUHAMMAD SIGIT KURNIADI','kode_unit'=>'2202','no_tlp'=>'','atasan'=>'2220','nik_qa'=>'133071116'],
            ['code_qa'=>'220201','nama_qa'=>'ERWIN ANDRIAN','kode_unit'=>'2202','no_tlp'=>'','atasan'=>'2202','nik_qa'=>'320130818'],
            ['code_qa'=>'220602','nama_qa'=>'TRI WAHYU AULIA HUDA','kode_unit'=>'2206','no_tlp'=>'','atasan'=>'2206','nik_qa'=>'978100921'],
            ['code_qa'=>'2206','nama_qa'=>'MUHAMMAD HIDAYATULLAH','kode_unit'=>'2206','no_tlp'=>'','atasan'=>'2220','nik_qa'=>'2212205202'],
            ['code_qa'=>'220601','nama_qa'=>'NANANG ADI PRIYANTO','kode_unit'=>'2206','no_tlp'=>'','atasan'=>'2206','nik_qa'=>'2308203205'],
            ['code_qa'=>'2207','nama_qa'=>'DANU TIRTO YOSO','kode_unit'=>'2207','no_tlp'=>'','atasan'=>'2220','nik_qa'=>'176080517'],
            ['code_qa'=>'220702','nama_qa'=>'SUSTRISNO BIN KASTARI','kode_unit'=>'2207','no_tlp'=>'','atasan'=>'2207','nik_qa'=>'604030220'],
            ['code_qa'=>'220701','nama_qa'=>'SAPTIA DWI PRAYOGI','kode_unit'=>'2207','no_tlp'=>'','atasan'=>'2207','nik_qa'=>'1014081121'],
            ['code_qa'=>'2208','nama_qa'=>'TANWIRUL FUAD','kode_unit'=>'2208','no_tlp'=>'','atasan'=>'2220','nik_qa'=>'693060820'],
            ['code_qa'=>'220801','nama_qa'=>'VELITA LUKHIANA','kode_unit'=>'2208','no_tlp'=>'','atasan'=>'2208','nik_qa'=>'1047100122'],
            ['code_qa'=>'2209','nama_qa'=>'KATON NURYANGSAH','kode_unit'=>'2209','no_tlp'=>'','atasan'=>'3330','nik_qa'=>'733261020'],
            ['code_qa'=>'220901','nama_qa'=>'NOVITA SEPTIANI','kode_unit'=>'2209','no_tlp'=>'','atasan'=>'2209','nik_qa'=>'2207204801'],
            ['code_qa'=>'2210','nama_qa'=>'BUCHORI HASBULLAH','kode_unit'=>'2210','no_tlp'=>'','atasan'=>'3330','nik_qa'=>'367060219'],
            ['code_qa'=>'221001','nama_qa'=>'SOFYAN FUADI','kode_unit'=>'2210','no_tlp'=>'','atasan'=>'2210','nik_qa'=>'2302221002'],
            ['code_qa'=>'2211','nama_qa'=>'IBNU GALUH PRAMUDITA','kode_unit'=>'2211','no_tlp'=>'','atasan'=>'3330','nik_qa'=>'290250618'],
            ['code_qa'=>'3330','nama_qa'=>'BAMBANG SUKAMTO','kode_unit'=>'3330','no_tlp'=>'','atasan'=>'1010','nik_qa'=>'035010415'],
            ['code_qa'=>'2220','nama_qa'=>'JOHAN PAMULARSIH','kode_unit'=>'2220','no_tlp'=>'','atasan'=>'1010','nik_qa'=>'048060715'],
        ]);
    }
}