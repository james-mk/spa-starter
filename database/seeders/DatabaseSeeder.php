<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\UserSeeder;
use Database\Seeders\VehicleMakeSeeder;
use Database\Seeders\VehicleModelSeeder;
use Database\Seeders\RolesAndPermissionsSeeder;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();


        // Role and Permission Seeder
        $this->call(RolesAndPermissionsSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(CurrencySeeder::class);
        $this->call(CountrySeeder::class);
        $this->call(VehicleMakeSeeder::class);
        $this->call(VehicleModelSeeder::class);
        $this->call(DriverStatusSeeder::class);
    }
}
