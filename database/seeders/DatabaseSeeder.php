<?php

namespace Database\Seeders;

use App\Models\User;
use Dflydev\DotAccessData\Data;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use PhpParser\Builder\Param;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            MenuSeeder::class,
            UserSeeder::class,
            KelompokSeeder::class,
            BranchSeeder::class,
            DataLoanMobSeeder::class,
            SamplingSeeder::class,
            AoSeeder::class,
            ParamProfilSeeder::class,
            ParamKetentuanSeeder::class,
            MasterQASeeder::class,
            FraudAlertsSeeder::class,
        ]);
    }
}
