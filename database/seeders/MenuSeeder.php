<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('menus')->truncate();

        // dashboard QA
        Menu::create([
            'name' => 'Dashboard',
            'icon' => 'fas fa-tachometer-alt nav-icon',
            'url' => '/qa/dashboard',
            'role_id' => 1,
            'order' => 1,
        ]);

        // dashboard QAL
        Menu::create([
            'name' => 'Dashboard',
            'icon' => 'fas fa-tachometer-alt nav-icon',
            'url' => '/qal/dashboard',
            'role_id' => 2,
            'order' => 1,
        ]);

        // dashboard QAM
        Menu::create([
            'name' => 'Dashboard',
            'icon' => 'fas fa-tachometer-alt nav-icon',
            'url' => '/qam/dashboard',
            'role_id' => 3,
            'order' => 1,
        ]);

        // dashboard Pengurus
        Menu::create([
            'name' => 'Dashboard',
            'icon' => 'fas fa-tachometer-alt nav-icon',
            'url' => '/pengurus/dashboard',
            'role_id' => 4,
            'order' => 1,
        ]);

        Menu::create([
            'name' => 'Rencana Audit',
            'icon' => 'fas fa-clipboard-list nav-icon',
            'url' => '/qa/rencana-audit',
            'role_id' => 1,
            'order' => 1,
        ]);

        Menu::create([
            'name' => 'Audit Rutin',
            'icon' => 'fas fa-tasks nav-icon',
            'url' => '/qa/audit-rutin',
            'role_id' => 1,
            'order' => 2,
        ]);

        Menu::create([
            'name' => 'Audit Khusus',
            'icon' => 'fas fa-tasks nav-icon',
            'url' => '/qa/audit-khusus',
            'role_id' => 1,
            'order' => 3,
        ]);

        Menu::create([
            'name' => 'Tanggapan',
            'icon' => 'fas fa-reply nav-icon',
            'url' => '/qa/tanggapan',
            'role_id' => 1,
            'order' => 4,
        ]);

        Menu::create([
            'name' => 'Laporan',
            'icon' => 'fas fa-file-alt nav-icon',
            'url' => '/qa/laporan',
            'role_id' => 1,
            'order' => 5,
        ]);

        Menu::create([
            'name' => 'Fraud Alert',
            'icon' => 'fas fa-exclamation-triangle nav-icon',
            'url' => '/qa/fraud-alert',
            'role_id' => 1,
            'order' => 6,
        ]);
    }
}