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

        $masterData = Menu::create([
            'name' => 'Master Data',
            'icon' => 'fas fa-database nav-icon',
            'url' => null,
            'role_id' => 1,
            'order' => 1,
        ]);

        Menu::create([
            'name' => 'User',
            'icon' => 'far fa-circle nav-icon',
            'url' => '/qa/user',
            'parent_id' => $masterData->id,
            'role_id' => 1,
            'order' => 1,
        ]);

            //informasi anggota
           Menu::create([
            'name' => 'Informasi Anggota',
            'icon' => 'fas fa-database nav-icon',
            'url' => '/informasi_anggota',
            'role_id' => 1,
            'order' => 1,
        ]);

    }
}