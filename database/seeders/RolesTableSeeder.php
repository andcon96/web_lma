<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roleName = ['Super_User', 'User'];

        for($i = 0; $i < count($roleName); $i++)
        {
            $data[$i] = [
                'role' => $roleName[$i],
                'created_at' => now(),
                'updated_at' => now()
            ];
        }
        DB::table('roles')->insert($data);
    }
}
