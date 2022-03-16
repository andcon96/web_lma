<?php

namespace Database\Seeders;

use App\Models\Master\Department;
use App\Models\Master\Role;
use App\Models\Master\RoleType;
use App\Models\Master\Supplier;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();

        for($i = 0; $i < 2; $i++)
        {
            $data[$i] = [
                'name' => $faker->name,
                'username' => $faker->userName,
                'supp_id' => Supplier::first()->id,
                'email' => $faker->unique()->safeEmail,
                'domain' => '10USA',
                'role_id' => Role::all()->random()->id,
                'role_type_id' => RoleType::all()->random()->id,
                'department_id' => Department::all()->random()->id,
                'isActive' => 1,
                'email_verified_at' => now(),
                'password' => bcrypt('password'),
                'remember_token' => STR::random(10),
                'created_at' => now(),
                'updated_at' => now()
            ];
        }
        DB::table('users')->insert($data);
    }
}
