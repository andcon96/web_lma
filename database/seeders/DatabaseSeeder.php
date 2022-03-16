<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\Master\User::factory(10)->create();
        $this->call([
            RolesTableSeeder::class,
            RoleTypesTableSeeder::class,
            DepartmentsTableSeeder::class,
            SuppliersTableSeeder::class,
            UsersTableSeeder::class,
            DomainTableSeeder::class,
            QxWsaTableSeeder::class,
        ]);
    }
}
