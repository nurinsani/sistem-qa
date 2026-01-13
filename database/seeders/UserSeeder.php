<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::insert([
            [
                'name' => 'QA User',
                'email' => 'qa@ni',
                'password' => bcrypt('123456'),
                'role_id' => '1' // 'qa'
            ],
            [
                'name' => 'QAL User',
                'email' => 'qal@ni',
                'password' => bcrypt('123456'),
                'role_id' => '2' // 'qal'
            ],
            [
                'name' => 'QAM User',
                'email' => 'qam@ni',
                'password' => bcrypt('123456'),
                'role_id' => '3' // 'qam'
            ],
            [
                'name' => 'Pengurus',
                'email' => 'pengurus@ni',
                'password' => bcrypt('123456'),
                'role_id' => '4' // 'pengurus'
            ],
        ]);
    }
}
